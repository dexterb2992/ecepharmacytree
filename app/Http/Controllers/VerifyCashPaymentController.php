<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;
use DB;
use Carbon\Carbon;
use ECEPharmacyTree\Order;
use ECEPharmacyTree\OrderDetail;
use ECEPharmacyTree\Billing;
use ECEPharmacyTree\Basket;
use ECEPharmacyTree\Inventory;
use ECEPharmacyTree\Setting;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Payment as InServerPayment;
use Response;
use Illuminate\Mail\Mailer;
use ECEPharmacyTree\Repositories\BasketRepository;
use ECEPharmacyTree\BasketPromo;

class VerifyCashPaymentController extends Controller
{

	function __construct(Mailer $mailer, BasketRepository $basket) {
		$this->mailer = $mailer;
		$this->basket = $basket;
	}

	function verification() {
		$input = Input::all();
		$response = array();

		$user_id = $input['user_id'];         
		$branch_server_id = $input['branch_server_id'];
		$recipient_name = $input['recipient_name'];
		$recipient_address = $input['recipient_address'];
		$recipient_contactNumber = $input['recipient_contactNumber'];
		$modeOfDelivery = $input['modeOfDelivery'];
		$delivery_charge = $input['delivery_charge'];
		$payment_method = $input['payment_method'];
		$payment_status = "pending";
		$status = $input['status'];
		$coupon_discount  = $input['coupon_discount'];
		$points_discount = $input['points_discount'];
		$email = $input['email'];
		$promo_id = $input['promo_id'];
		$promo_type = $input['promo_type'];

		if($promo_type == 'free_delivery'){
			$delivery_charge = 0;
		}

		$basket_response = json_decode($this->basket->check_and_adjust_basket($user_id, $branch_server_id));

		if($basket_response->basket_quantity_changed){
			echo json_encode($basket_response);	
			exit(0);
		}

		$response['basket_quantity_changed'] = false;

		$results = DB::select("call get_baskets_and_products(".$user_id.")");

		$counter = 0;
		$totalAmount = 0;
		$grosstotalAmount = 0;
		$gross_total = 0;
		$totalAmount_final = 0;
		$order_id = 0;
		$order_saved = false;
		$billing_saved = false;
		$prescription_id = 0;
		$current_product_price = 0;
		$order_date = null;
		$undiscounted_total = 0;
		

		foreach($results as $result) {
			$counter += 1;
			$current_product_price = $result->price;
			$quantity = $result->quantity;
			$product_id = $result->product_id;
			$prescription_id = $result->prescription_id;
			$undiscounted_total += $quantity * $result->price;
			$per_item_total = $quantity * $result->price;

			$grosstotalAmount += $per_item_total;
			
			if($result->promo_type == "peso_discount"){
				$totalAmount += $per_item_total - $result->peso_discount;
			} else if ($result->promo_type == "percentage_discount") {
				$totalAmount += $per_item_total - $result->percentage_discount;
			} else {
				$totalAmount += $per_item_total;					
			}

			if($counter == 1) {
				$order = new Order;
				$order->patient_id = $user_id;
				$order->recipient_name = $recipient_name;
				$order->recipient_address = $recipient_address;
				$order->recipient_contactNumber = $recipient_contactNumber;
				$order->branch_id = $branch_server_id;
				$order->modeOfDelivery = $modeOfDelivery;
				$order->delivery_charge = $delivery_charge;
				$order->status = 'pending';

				if($promo_id > 0){
					$order->promo_id = $promo_id;
					$order->promo_type = $promo_type;
				}

				if($order->save()){
					$order_id = $order->id; 
					$order_date = $order->created_at;
					
					$response['order_message'] = "order saved on database";
					$order_saved = true;
				} else 
				$response['order_message'] = "Sorry, we can't process your request right now. ";

			}

			if($order_saved) {
				$order_detail = new OrderDetail;
				$order_detail->order_id = $order_id;
				$order_detail->product_id = $product_id;
				$order_detail->prescription_id = $prescription_id;
				$order_detail->quantity = $quantity;
				$order_detail->price = $current_product_price;
				$order_detail->type = 'type';
				$order_detail->promo_id = $result->promo_id;
				$order_detail->promo_type = $result->promo_type;
				$order_detail->percentage_discount = $result->percentage_discount;
				$order_detail->peso_discount = $result->peso_discount;
				$order_detail->free_gift = $result->free_gift;
				$order_detail->promo_free_product_qty = $result->promo_free_product_qty;

				if($order_detail->save())
					$response['order_details_message_'.$counter] = "order detail saved on database";
				else
					$response['order_details_message_'.$counter] = "Sorry, we can't process your request right now. ";

				// if(BasketPromo::where('basket_id', '=', $result->id)->delete()) 
				// 	$response['basket_promo_message_'.$counter] = "basket promos deleted on database";
				// else 
				// 	$response['basket_promo_message_'.$counter] = "basket promos not deleted on database";
			}


			if(count($results) == $counter) {
				$gross_total = $grosstotalAmount + $delivery_charge;
				$totalAmount_final  = $totalAmount - $coupon_discount - $points_discount + $delivery_charge;

				$billing = new Billing;
				$billing->order_id = $order_id;
				$billing->gross_total = $undiscounted_total;
				$billing->total = $totalAmount_final;
				$billing->payment_status = $payment_status;
				$billing->payment_method = $payment_method;
				$billing->points_discount = $points_discount;
				$billing->coupon_discount = $coupon_discount;

				if($billing->save()) {
					$billing_id = $billing->id;
					$response['billing_message'] = "order saved on database";
					$response['billing_id'] = $billing_id;
					$billing_saved = true;
				} else
				$response["billing_message"] = "Sorry, we can't process your request right now.";


				// if(Basket::where('patient_id', '=', $user_id)->delete()) 
				// 	$response['basket_message'] = "basket/s deleted on database";
				// else 
				// 	$response['basket_message'] = "basket/s not deleted on database";


				$setting = Setting::first();
				$order = Order::findOrFail($order_id);
				$order_details = DB::select("SELECT od.id, od.promo_id, od.promo_type, od.peso_discount, od.percentage_discount, od.free_gift, od.promo_free_product_qty, p.name as product_name, od.price, od.quantity, o.created_at as ordered_on, o.status,  p.packing, p.qty_per_packing, p.unit from order_details as od inner join orders as o on od.order_id = o.id inner join products as p on od.product_id = p.id inner join branches as br on o.branch_id = br.id where od.order_id =  ".$order_id." order by od.created_at DESC");
				$order_date = Carbon::parse($order_date)->toFormattedDateString();
				$this->emailtestingservice($email, $order_details, $recipient_name, $recipient_address, $recipient_contactNumber, $payment_method, $modeOfDelivery, $coupon_discount, $points_discount, $totalAmount_final, $gross_total, $order_id, $order_details, $order_date, $status, $order);				
			}
		}

		echo json_encode($response);
	}

	function emailtestingservice($email, $order_details, $recipient_name, $recipient_address, $recipient_contactNumber, $payment_method, $modeOfDelivery, $coupon_discount, $points_discount, $totalAmount_final, $gross_total, $order_id, $order_details, $order_date, $status, $order){	
		$res = $this->mailer->send( 'emails.invoice_remastered', 
			compact('email', 'recipient_name', 'recipient_address', 'recipient_contactNumber', 'payment_method', 'modeOfDelivery', 'coupon_discount', 'points_discount', 'totalAmount_final', 'gross_total', 'order_id', 'order_details', 'order_date', 'status', 'order'), function ($m) use ($email) {
				$m->subject('Pharmacy Tree Invoice');
				$m->to($email);
			});
	}

}