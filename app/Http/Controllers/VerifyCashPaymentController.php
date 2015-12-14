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

class VerifyCashPaymentController extends Controller
{
	function verification() {
		$input = Input::all();

		$user_id = $input['user_id'];         
		$branch_server_id = $input['branch_server_id'];
		$recipient_name = $input['recipient_name'];
		$recipient_address = $input['recipient_address'];
		$recipient_contactNumber = $input['recipient_contactNumber'];
		$modeOfDelivery = $input['modeOfDelivery'];
		$payment_method = $input['payment_method'];
		$payment_status = "pending";
		$status = $input['status'];

		$results = DB::select("call get_baskets_and_products(".$user_id.")");

		$counter = 0;
		$totalAmount = 0;
		$order_id = 0;
		$order_saved = false;
		$billing_saved = false;
		$prescription_id = 0;
		$current_product_price = 0;

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
				$order->status = 'pending';

				if($order->save()){
					$order_id = $order->id; 
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

				$billing = new Billing;
				$billing->order_id = $order_id;
				$billing->gross_total = $totalAmount;
				$billing->total = $totalAmount;
				$billing->payment_status = $payment_status;
				$billing->payment_method = $payment_method;

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

				$earned_points = round(($setting->points/100) * $totalAmount, 2);
				$patient = Patient::findOrFail($user_id);
				$patient->points = $earned_points;

				if($patient->save())
					$response['points_update_message'] = "points updated";
				else 
					$response['points_update_message'] = "points not updated";
			}
		}

		echo json_encode($response);
	}

}