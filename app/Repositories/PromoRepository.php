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
        // dd($input);
        $dfp = DiscountsFreeProduct::find($input['id']);
        $dfp->quantity_required = $input["discount_detail_minimum_type"] == "minimum_purchase" ? 0 : $input['quantity_required'];
        $dfp->minimum_purchase = $input["discount_detail_minimum_type"] == "minimum_purchase" ? $input['minimum_purchase'] : 0;


        if( !isset($input["discount_detail_discount_type"]) ){
            $dfp->percentage_discount = 0;
            $dfp->peso_discount = 0;
        }else{
            $dfp->percentage_discount = $input["discount_detail_discount_type"] == "peso_discount" ? 0 : $input["percentage_discount"];
            $dfp->peso_discount = $input["discount_detail_discount_type"] == "peso_discount" ? $input["peso_discount"] : 0;
        }
        

        $dfp->has_free_gifts = isset($input["has_free_gifts"]) ? $input["has_free_gifts"] : 0;

        if( isset( $input['has_free_gifts'] ) && $input['has_free_gifts'] == 0 ){
            FreeProduct::where("dfp_id", $dfp->id)->delete();
        }

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

    function save($input, $promoID = null){
        $promo = new Promo;
        if( $promoID !== null ){
            $promo = Promo::findOrFail($promoID);
        }
        
        // dd($input);
        $promo->long_title = $input["long_title"];
        $promo->start_date = $input["start_date"];
        $promo->end_date = $input["end_date"];
        $promo->product_applicability = $input["product_applicability"];

        $promo->offer_type = $input["offer_type"];
        $promo->generic_redemption_code = $input["generic_redemption_code"];

        if( $input["product_applicability"] == "PER_TRANSACTION" ){
            $promo->minimum_purchase_amount = $input["minimum_purchase_amount"]; // optional
            $promo->is_free_delivery = isset($input["is_free_delivery"]) ? $input["is_free_delivery"] : 0;

            if( isset($input['discount_type']) ){
                $promo->peso_discount = $input['discount_type'] == 'peso_discount' ? $input["peso_discount"] : 0;
                $promo->percentage_discount = $input['discount_type'] == 'peso_discount' ? 0 : $input["percentage_discount"];
            }
            
            if( isset($input["per_transaction_has_free_gifts"]) && $input["per_transaction_has_free_gifts"] == 1 ){
                $free_gifts = array();
                foreach ($input['per_transaction_gift_quantities'] as $key => $value) {
                    $free_gifts[] = array('product_id' => $key, 'quantity' => $value);
                }

                $promo->free_gifts = json_encode($free_gifts);
            }
        }

        if( $promo->save() ){
            if( isset($input['product_id']) && count($input['product_id']) > 0 &&  $input["product_applicability"] == "SPECIFIC_PRODUCTS" ){
                foreach ($input['product_id'] as $key => $value) {
                    $dfp = new DiscountsFreeProduct;
                    $dfp->promo_id = $promo->id;
                    $dfp->product_id = $value;
                    $dfp->save();
                }
            }

            return true;
        }
        return false;
    }

    function update($input){
        if( $this->save($input, $input['id']) ){
            $dfps = DiscountsFreeProduct::where('promo_id', $input['id'])->delete();

            if( isset($input['product_id']) && (count($input['product_id']) > 0) && ($input["product_applicability"] == 'SPECIFIC_PRODUCTS') ){
                foreach ($input['product_id'] as $key => $value) {
                    $dfp = new DiscountsFreeProduct;
                    $dfp->promo_id = $input['id'];
                    $dfp->product_id = $value;
                    $dfp->save();
                }
            }
            return true;
        }
        return false;
    }
}