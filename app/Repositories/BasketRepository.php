<?php 

namespace ECEPharmacyTree\Repositories;

use DB;
use ECEPharmacyTree\Basket;
use ECEPharmacyTree\BasketPromo;

class BasketRepository {

	function check_and_adjust_basket($patient_id, $branch_id){

		$response = array();
		$basket_quantity_changed = false;
		$basket_promo_removed = false;

		$results = DB::select("call check_basket(".$patient_id.", ".$branch_id.")");

		foreach($results as $result){
			if($result->quantity > $result->available_quantity) {
				$basket = Basket::findOrFail($result->basket_id);
				$basket->quantity = $result->available_quantity;
				if($basket->save()){
					$result->quantity = $result->available_quantity;     
					$basket_quantity_changed = true;               
					$basketpromo = BasketPromo::where('basket_id', $basket->id)->first();
					$response['basket_promo'] = $basketpromo;
					$response['basket_id'] = $basket->id;
					if($basketpromo->delete())
						$basket_promo_removed = true;
				}
			}

		}
		$response['baskets'] = $results;
		$response['success'] = 1;
		$response['server_timestamp'] = date("Y-m-d H:i:s", time());
		$response['basket_quantity_changed'] = $basket_quantity_changed;
		$response['basket_promo_removed'] = $basket_promo_removed;
		return json_encode($response);

	}
}