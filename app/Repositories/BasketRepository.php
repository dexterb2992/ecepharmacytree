<?php 

namespace ECEPharmacyTree\Repositories;

use DB;
use ECEPharmacyTree\Basket;
use ECEPharmacyTree\BasketPromo;

class BasketRepository {

	function check_and_adjust_basket($patient_id, $branch_id){

		$response = array();
		$final_array = array();
		$basket_quantity_changed = false;
		$basket_promo_removed = false;

		$results = DB::select("call check_basket(".$patient_id.", ".$branch_id.")");

		foreach($results as $result){

			if($result->available_quantity < 1 || $result->quantity < 1) {
				$basket = Basket::find($result->basket_id);
				if(!empty($basket) && $basket->delete()){
					$basket_quantity_changed = true;
					$basketpromo = BasketPromo::where('basket_id', $basket->id)->first();
					if(!empty($basketpromo) && $basketpromo->delete()){
						$response['basket_promo'] = $basketpromo;
						$response['basket_id'] = $basket->id;
						$basket_promo_removed = true;
					}
				}
			} else if($result->quantity > $result->available_quantity && $result->available_quantity > 0) {
				$basket = Basket::find($result->basket_id);
				$basket->quantity = $result->available_quantity;
				if(!empty($basket) && $basket->save()){
					$result->quantity = $result->available_quantity;     
					$basket_quantity_changed = true;               
					$basketpromo = BasketPromo::where('basket_id', $basket->id)->first();
					if(!empty($basketpromo) && $basketpromo->delete()){
						$response['basket_promo'] = $basketpromo;
						$response['basket_id'] = $basket->id;
						$basket_promo_removed = true;
					}
				}
				array_push($final_array, $result);
			} else {
				array_push($final_array, $result);
			} 
			
		}

		$response['baskets'] = $final_array;
		$response['success'] = 1;
		$response['server_timestamp'] = date("Y-m-d H:i:s", time());
		$response['basket_quantity_changed'] = $basket_quantity_changed;
		$response['basket_promo_removed'] = $basket_promo_removed;
		return json_encode($response);

	}

	function flush_user_basket_promos($id){
		$response = array();
		$flag = true;

		$baskets = Basket::where("patient_id", $id)->get();
		
		foreach($baskets as $basket) 
		{
			if(!$basket->basket_promo()->first()->delete())
				$flag = false;
		}

		$response['success'] = $flag;

		return $response;
	}
}