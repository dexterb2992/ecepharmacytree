<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;
use DB;
use Carbon\Carbon;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payment;
use ECEPharmacyTree\Order;
use ECEPharmacyTree\OrderDetail;
use ECEPharmacyTree\Billing;
use ECEPharmacyTree\Basket;
use ECEPharmacyTree\Inventory;
use ECEPharmacyTree\Setting;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\BasketPromo;
use ECEPharmacyTree\Payment as InServerPayment;
use Illuminate\Mail\Mailer;


class VerifyPaymentController extends Controller
{

	function __construct(Mailer $mailer) {
		$this->mailer = $mailer;
	}
	// private $order_id;
	function verification() {
		$apiContext = new ApiContext(
			new OAuthTokenCredential(
                        'ASwYbh_BDLIbNLiQXNXrtQvCvl8OrUpjpM9Uup0oEOMrqWp4BUK-gBkFduSigVE27vtuUFm_tCkpkN2h', // ClientID
                        'EA3Zqz_ly0v0wcTsmUiVd-yg1DYVoGwziZsbcdPHonoYxo2FZBahRNguy9FSD0j4Yc8Mw2qujbY9Nvyf'      // ClientSecret
                        )
			);
		$input = Input::all();
		try {
			$paymentId = $input['paymentId'];
			$payment_client = json_decode($input['paymentClientJson'], true);
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
			$coupon_discount = $input['coupon_discount'];
			$points_discount = $input['points_discount'];
			$senior_discount = $input['senior_discount'];
			$promo_id = $input['promo_id'];
			$promo_type = $input['promo_type'];
			$email = $input['email'];
                // Gettin payment details by making call to paypal rest api
			$_payment = Payment::get($paymentId, $apiContext);
                // Verifying the state approved
			if ($_payment->getState() != 'approved') {
				$response["error"] = true;
				$response["message"] = "Payment has not been verified. Status is " . $_payment->getState();
				$this->echoResponse(200, $response);
				return;
			}

			$amount_client = $payment_client["amount"];

			$currency_client = $payment_client["currency_code"];

			$transaction = $_payment->getTransactions()[0];

			$amount_server = $transaction->getAmount()->getTotal();

			$currency_server = $transaction->getAmount()->getCurrency();

			$sale_state = $transaction->getRelatedResources()[0]->getSale()->getState();

			$server_timestamp = Carbon::now('Asia/Manila');

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

				$quantity = $result->quantity;
				$product_id = $result->product_id;
				$prescription_id = $result->prescription_id;
				$current_product_price = $result->price;

				if($result->promo_type == "peso_discount"){
					$totalAmount += ($quantity * $result->price) - $result->peso_discount;
				} else if ($result->promo_type == "percentage_discount") {
					$totalAmount += ($quantity * $result->price) - $result->percentage_discount;
				} else {
					$totalAmount += $quantity * $result->price;					
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

					if(BasketPromo::where('basket_id', '=', $result->id)->delete()) 
						$response['basket_promo_message_'.$counter] = "basket promos deleted on database";
					else 
						$response['basket_promo_message_'.$counter] = "basket promos not deleted on database";
				}

				//move this code to fulfill items on admin
				// if($order_saved) {
				// 	$inventories = Inventory::where('product_id', $product_id)->orderBy('expiration_date', 'ASC')->get();
				// 	$_quantity = $quantity;
				// 	$remains = 0;

				// 	foreach ($inventories as $inventory) {
				// 		if($remains > 0)
				// 			$_quantity = $remains;

				// 		if($_quantity  > $inventory->available_quantity){
				// 			$remains = $_quantity - $inventory->available_quantity;
				// 			$inventory->available_quantity = 0;
				// 			$inventory->save();
				// 		} else {
				// 			$inventory->available_quantity = $inventory->available_quantity - $_quantity;
				// 			$inventory->save();
				// 			break;
				// 		}
				// 	}
				// }


				if(count($results) == $counter) {
					$gross_total = $totalAmount;
					$totalAmount_final  = $totalAmount - $coupon_discount - $points_discount - $senior_discount + $delivery_charge;

					$billing = new Billing;
					$billing->order_id = $order_id;
					$billing->gross_total = $gross_total;
					$billing->total = $totalAmount_final;
					$billing->payment_status = $payment_status;
					$billing->payment_method = $payment_method;
					$billing->points_discount = $points_discount;
					$billing->coupon_discount = $coupon_discount;
					$billing->senior_discount = $senior_discount;

					if($billing->save()) {
						$billing_id = $billing->id;
						$response['billing_message'] = "order saved on database";
						$response['billing_id'] = $billing_id;
						$billing_saved = true;
					} else
					$response["billing_message"] = "Sorry, we can't process your request right now.";


					// $payment = new InServerPayment;
					// $payment->billing_id = $billing_id;
					// $payment->txn_id = $_payment->id;
					// $payment->or_no = 'official_receipt_number';

					// if($payment->save())
					// 	$response['payment_message'] = "payment saved on database";
					// else
					// 	$response['payment_message'] = "error saving payment";


					if(Basket::where('patient_id', '=', $user_id)->delete()) 
						$response['basket_message'] = "basket/s deleted on database";
					else 
						$response['basket_message'] = "basket/s not deleted on database";

					$setting = Setting::first();

					//send email invoice here
					$order_details = DB::select("SELECT od.id, p.name as product_name, od.price, od.quantity, o.created_at as ordered_on, o.status,  p.packing, p.qty_per_packing, p.unit from order_details as od inner join orders as o on od.order_id = o.id inner join products as p on od.product_id = p.id inner join branches as br on o.branch_id = br.id where od.order_id =  ".$order_id." order by od.created_at DESC");

					//$this->emailtestingservice($email, $order_details, $recipient_name, $recipient_address, $recipient_contactNumber, $payment_method, $modeOfDelivery, $coupon_discount, $points_discount, $totalAmount_final, $gross_total, $order_id, $order_details, $order_date, $status);

				}
			}

                // Verifying the amount
			if ($amount_server != $amount_client) {
				$response["error"] = true;
				$response["message"] = "Payment amount doesn't matched.";
				$this->echoResponse(200, $response);
				return;
			}

                // Verifying the currency
			if ($currency_server != $currency_client) {
				$response["error"] = true;
				$response["message"] = "Payment currency doesn't matched.";
				$this->echoResponse(200, $response);
				return;
			}

                // Verifying the sale state
			// if ($sale_state != 'completed') {
			// 	$response["error"] = true;
			// 	$response["message"] = "Sale not completed";
			// 	$this->echoResponse(200, $response);
			// 	return;
			// }

			$this->echoResponse(200, $response);
		} catch (\PayPal\Exception\PayPalConnectionException $exc) {
			if ($exc->getCode() == 404) {
				$response["error"] = true;
				$response["message"] = "Payment not found!";
				$this->echoResponse(404, $response);
			} else {
				$response["error"] = true;
				$response["message"] = "Unknown error occurred!" . $exc->getMessage();
				$this->echoResponse(500, $response);
			}
		} catch (Exception $exc) {
			$response["error"] = true;
			$response["message"] = "Unknown error occurred!" . $exc->getMessage();
			$this->echoResponse(500, $response);
		}
	}

	function echoResponse($statuws_code, $response) {
		echo json_encode($response);
	}

	function emailtestingservice($email, $order_details, $recipient_name, $recipient_address, $recipient_contactNumber, $payment_method, $modeOfDelivery, $coupon_discount, $points_discount, $totalAmount_final, $gross_total, $order_id, $order_details, $order_date, $status){
		$res = $this->mailer->send( 'emails.sales_invoice_remastered', 
			compact('email', 'recipient_name', 'recipient_address', 'recipient_contactNumber', 'payment_method', 'modeOfDelivery', 'coupon_discount', 'points_discount', 'totalAmount_final', 'gross_total', 'order_id', 'order_details', 'order_date', 'status'), function ($m) use ($email) {
				$m->subject('Pharmacy Tree Invoice');
				$m->to($email);
			});
	}

}