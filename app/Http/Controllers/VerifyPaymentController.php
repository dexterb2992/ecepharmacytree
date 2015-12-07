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

class ApiController extends Controller
{
	private $apicontext;
	private $payment;


	function __construct(){
		$this->apicontext = new ApiContext(
			new OAuthTokenCredential(
                        'AcCQ4PKc6AtjThCsPPkGSH01nPPe7yJKB1oRT39hpgpUGrFkORy9gmuY5_OF4loXc45RaNrUq4h94PP1', // ClientID
                        'EH7LOgghxkz-pebLoT1dXSuDo0GiyPI3s1kKaMkp7fKQ25ezZovq5PGQqwVfAAjUpPFcPrAZYA33DcTC'      // ClientSecret
                        )
			);
	}

	function verification() {
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
			$payment_method = $input['payment_method'];
			$payment_status = "pending";
			$status = $input['status'];

                // Gettin payment details by making call to paypal rest api
			$_payment = Payment::get($paymentId, $this->apiContext);

                // Verifying the state approved
			if ($_payment->getState() != 'approved') {
				$response["error"] = true;
				$response["message"] = "Payment has not been verified. Status is " . $_payment->getState();
				echoResponse(200, $response);
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
			$order_saved = false;
			$billing_saved = false;
			$prescription_id = 0;

			foreach($results as $result) {
				$quantity = $result->quantity;
				$product_id = $result->product_id;
				$prescription_id = $result->prescription_id;
				$totalAmount += $quantity * $result->price;

				if($counter == 0) {
					$order = new Order;
					$order->patient_id = $user_id;
					$order->recipient_name = $recipient_name;
					$order->recipient_address = $recipient_address;
					$order->recipient_contactNumber = $recipient_contactNumber;
					$order->branch_id = $branch_server_id;
					$order->modeOfDelivery = $modeOfDelivery;
					$order->status = 'pending';

					if($order->save()){
						$order_id = $order->id; 
						$response['order_message'] = "order saved on database";
						$order_saved = true;
					} else 
					$response['order_message'] = "Sorry, we can't process your request right now. ".mysql_error();

				}

				$counter += 1;

				if($order_saved) {
					$sql_order_details_save = "INSERT INTO order_details VALUES ('', $order_id, $product_id, $prescription_id, $quantity, 'type', 0, '$server_timestamp', '', '')";
					$order_detail = new OrderDetail;
					$order_detail->order_id = $order_id;
					$order_detail->product_id = $product_id;
					$order_detail->prescription_id = $prescription_id;
					$order_detail->quantity = $quantity;
					$order_detail->type = $type;

					if($order_detail->save())
						$response['order_details_message_'.$counter] = "order detail saved on database";
					else
						$response['order_details_message_'.$counter] = "Sorry, we can't process your request right now. ".mysql_error();
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
			}

			$billing = new Billing;
			$billing->order_id = $order_id;
			$billing->gross_total = $totalAmount;
			$billing->total = $totalAmount;
			$billing->payment_status = $payment_status;
			$billing->payment_method = $payment_method;

			if($billing->save()) {
				$billing_id = mysql_insert_id();
				$response['billing_message'] = "order saved on database";
				$response['billing_id'] = $billing_id;
				$billing_saved = true;
			} else
			$response["billing_message"] = "Sorry, we can't process your request right now. ".mysql_error();


			$payment = new Payment;
			$payment->billing_id = $billing_id;
			$payment->txn_id = $_payment->id;
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

			$earned_points = round(($setting->points/100) * $totalAmount, 2);
			$patient = Patient::findOrFail($user_id);
			$patient->points = $earned_points;

			if($patient->save())
				$response['points_update_message'] = "points updated";
			else 
				$response['points_update_message'] = "points not updated";


                // Verifying the amount
			if ($amount_server != $amount_client) {
				$response["error"] = true;
				$response["message"] = "Payment amount doesn't matched.";
				echoResponse(200, $response);
				return;
			}

                // Verifying the currency
			if ($currency_server != $currency_client) {
				$response["error"] = true;
				$response["message"] = "Payment currency doesn't matched.";
				echoResponse(200, $response);
				return;
			}

                // Verifying the sale state
			if ($sale_state != 'completed') {
				$response["error"] = true;
				$response["message"] = "Sale not completed";
				echoResponse(200, $response);
				return;
			}

			echoResponse(200, $response);
		} catch (\PayPal\Exception\PayPalConnectionException $exc) {
			if ($exc->getCode() == 404) {
				$response["error"] = true;
				$response["message"] = "Payment not found!";
				echoResponse(404, $response);
			} else {
				$response["error"] = true;
				$response["message"] = "Unknown error occurred!" . $exc->getMessage();
				echoResponse(500, $response);
			}
		} catch (Exception $exc) {
			$response["error"] = true;
			$response["message"] = "Unknown error occurred!" . $exc->getMessage();
			echoResponse(500, $response);
		}
	}

	function echoResponse($status_code, $response) {
		echo json_encode($response);
	}

}