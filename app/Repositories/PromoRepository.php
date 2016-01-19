<?php  

namespace ECEPharmacyTree\Repositories;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Carbon\Carbon; 

use ECEPharmacyTree\Promo;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\DiscountsFreeProduct;
use ECEPharmacyTree\FreeProduct;

class PromoRepository {
	
	function update_details($input){
        $dfp = DiscountsFreeProduct::find($input['id']);
        $dfp->quantity_required = $input['quantity_required'];
        $dfp->percentage_discount = $input["percentage_discount"];
        $dfp->peso_discount = $input["peso_discount"];
        $dfp->has_free_gifts = isset($input["has_free_gifts"]) ? $input["has_free_gifts"] : 0;

        if( isset($input['gift_quantities']) ){
            FreeProduct::where("dfp_id", $dfp->id)->delete();
            foreach ($input['gift_quantities'] as $key => $value) {
                $free_product = new FreeProduct;
                $free_product->dfp_id = $dfp->id;
                $free_product->product_id = $key;
                $free_product->quantity_free = $value;
                $free_product->save();
            }
        }
        $promoID = $dfp->promo->id;

        if( $dfp->save() )
            return true;

       return false;

    }
}