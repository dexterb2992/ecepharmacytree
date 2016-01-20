<?php 

namespace ECEPharmacyTree\Repositories;

use DB;
use ECEPharmacyTree\Basket;

class BasketRepository {

	function check_and_adjust_basket($patient_id, $branch_id){

		$response = array();
		$basket_quantity_changed = false;

		$results = DB::select("call check_basket(".$patient_id.", ".$branch_id.")");

		foreach($results as $result){
			if($result->quantity > $result->available_quantity) {
				$basket = Basket::findOrFail($result->basket_id);
				$basket->quantity = $result->available_quantity;
				if($basket->save()){
					$result->quantity = $result->available_quantity;     
					$basket_quantity_changed = true;               
				}
			}

		}
		$response['baskets'] = $results;
		$response['success'] = 1;
		$response['server_timestamp'] = date("Y-m-d H:i:s", time());
		$response['basket_quantity_changed'] = $basket_quantity_changed;

		return json_encode($response);

	}
}