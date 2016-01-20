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

class VerifyCashPaymentController extends Controller
{

	function __construct(Mailer $mailer, BasketRepository $basket) {
		$this->mailer = $mailer;
		$this->basket = $basket;
	}

	function verification() {
		$input = Input::all();

		// dd($input);
		// exit(0);

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

		$basket_response = json_decode($this->basket->check_and_adjust_basket($input));

		if($basket_response->basket_quantity_changed){
			$basket_response;	
			exit(0);
		}

		$results = DB::select("call get_baskets_and_products(".$user_id.")");

		$counter = 0;
		$totalAmount = 0;
		$gross_total = 0;
		$totalAmount_final = 0;
		$order_id = 0;
		$order_saved = false;
		$billing_saved = false;
		$prescription_id = 0;
		$current_product_price = 0;
		$order_date = null;

		foreach($results as $result) {
			$counter += 1;
			$current_product_price = $result->price;
			$quantity = $result->quantity;
			$product_id = $result->product_id;
			$prescription_id = $result->prescription_id;
			$totalAmount += $quantity * $result->price;

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

				if($order_detail->save())
					$response['order_details_message_'.$counter] = "order detail saved on database";
				else
					$response['order_details_message_'.$counter] = "Sorry, we can't process your request right now. ";
			}

			if($order_saved) {
				$inventories = Inventory::where('product_id', $product_id)->orderBy('expiration_date', 'ASC')->get();
				$_quantity = $quantity;
				$remains = 0;

				foreach ($inventories as $inventory) {
					if($remains > 0)
						$_quantity = $remains;

					if($_quantity  > $inventory->available_quantity){
						$remains = $_quantity - $inventory->available_quantity;
						$inventory->available_quantity = 0;
						$inventory->save();
					} else {
						$inventory->available_quantity = $inventory->available_quantity - $_quantity;
						$inventory->save();
						break;
					}
				}
			}


			if(count($results) == $counter) {
				$gross_total = $totalAmount;
				$totalAmount_final  = $totalAmount - $coupon_discount - $points_discount;

				$billing = new Billing;
				$billing->order_id = $order_id;
				$billing->gross_total = $gross_total;
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


				$payment = new InServerPayment;
				$payment->billing_id = $billing_id;
				$payment->txn_id = 'transaction_id';
				$payment->or_no = 'official_receipt_number';

				if($payment->save())
					$response['payment_message'] = "payment saved on database";
				else
					$response['payment_message'] = "error saving payment";


				if(Basket::where('patient_id', '=', $user_id)->delete()) 
					$response['basket_message'] = "basket/s deleted on database";
				else 
					$response['basket_message'] = "basket/s not deleted on database";


				$setting = Setting::first();

				/*$earned_points = round(($setting->points/100) * $totalAmount, 2);
				$patient = Patient::findOrFail($user_id);
				$patient->points = $earned_points;

				if($patient->save())
					$response['points_update_message'] = "points updated";
				else 
					$response['points_update_message'] = "points not updated";*/
				
				$order_details = DB::select("SELECT od.id, p.name as product_name, od.price, od.quantity, o.created_at as ordered_on, o.status,  p.packing, p.qty_per_packing, p.unit from order_details as od inner join orders as o on od.order_id = o.id inner join products as p on od.product_id = p.id inner join branches as br on o.branch_id = br.id where od.order_id =  ".$order_id." order by od.created_at DESC");

				$this->emailtestingservice($email, $order_details, $recipient_name, $recipient_address, $recipient_contactNumber, $payment_method, $modeOfDelivery, $coupon_discount, $points_discount, $totalAmount_final, $gross_total, $order_id, $order_details, $order_date, $status);				

			}
		}

		echo json_encode($response);
	}

	function check_if_stocks_valid(){
		
	}

	function emailtestingservice($email, $order_details, $recipient_name, $recipient_address, $recipient_contactNumber, $payment_method, $modeOfDelivery, $coupon_discount, $points_discount, $totalAmount_final, $gross_total, $order_id, $order_details, $order_date, $status){
		$res = $this->mailer->send( 'emails.sales_invoice_remastered', 
			compact('email', 'recipient_name', 'recipient_address', 'recipient_contactNumber', 'payment_method', 'modeOfDelivery', 'coupon_discount', 'points_discount', 'totalAmount_final', 'gross_total', 'order_id', 'order_details', 'order_date', 'status'), function ($m) use ($email) {
				$m->subject('Pharmacy Tree Invoice');
				$m->to($email);
			});
	}

}