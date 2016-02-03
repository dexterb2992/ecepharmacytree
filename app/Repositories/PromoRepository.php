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
        $dfp->is_every = isset($input["is_every"]) ? $input['is_every'] : 0;

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
            }else{
                $promo->free_gifts = "";
            }
        }

        if( $promo->save() ){
            if( isset($input['product_id']) && count($input['product_id']) > 0 &&  $input["product_applicability"] == "SPECIFIC_PRODUCTS" ){
                foreach ($promo->discounts as $discount) {
                    $discount->delete();
                }
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

    public function check($input){
        $today =  Carbon::today('Asia/Manila');
        $products = Product::all();

        $number_of_active_per_transaction_promo = Promo::where('end_date', '>=', $today)->where('product_applicability', '=', 'PER_TRANSACTION')->count();
        if( $number_of_active_per_transaction_promo > 0 ){
            return array(
                    'msg' => "Sorry, but currently, there's an active Per Transaction Promo. 
                            You can add a new promo only when no other Per Transaction Promo is running..",
                    "is_allowed" => false
                    );
        }
        return ["is_allowed" => true];
    }

    public function discount_details($id){
        $dfp = DiscountsFreeProduct::findOrFail($id);
        if( $dfp->has_free_gifts == 1 ){ // get Free Gifts
            $free_products = $dfp->free_products;
            if( count($free_products) >= 1 ){
                $free_products->load('product');
            }

        } 
        
        $dfp->discount_detail_minimum_type = 'quantity_required';

        if( $dfp->minimum_purchase != 0 && $dfp->quantity_required == 0 ){
            $dfp->discount_detail_minimum_type = 'minimum_purchase';
        }

        $dfp->discount_detail_discount_type = "percentage_discount";
        if(  $dfp->peso_discount != 0 && $dfp->percentage_discount == 0 ){
            $dfp->discount_detail_discount_type = "peso_discount";
        }



        return $dfp;
    }

    public function destroy($id){
        $product = Promo::findOrFail($id);
        if( $product->delete() )
            return true;
        return false;
    }

    public function show($id){
        $promo = Promo::find($id);
        $product_ids = [];

        if( isset( $promo->id ) ){
            if( $promo->product_applicability == "PER_TRANSACTION" ){
                $promo->discount_type = "peso_discount";
                if( $promo->percentage_discount != 0 && $promo->peso_discount == 0 ){
                    $promo->discount_type = "percentage_discount";
                }

                // if product_applicability = PER TRANSACTION
                if( $promo->free_gifts != "" ){
                    $free_gifts = [];
                    $promo->per_transaction_has_free_gifts = 1;
                    $arr_free_gifts = json_decode($promo->free_gifts);

                    $product_ids = [];

                    // dd($arr_free_gifts);
                    foreach ($arr_free_gifts as $gift) {
                        $product = Product::find($gift->product_id);
                        $product->quantity_free = $gift->quantity;
                        array_push($free_gifts, $product);
                        array_push($product_ids, $gift->product_id);
                    }
                    $promo->free_gifts = $free_gifts;
                    $promo->product_id = $product_ids;
                }
            }else{
                if( isset($promo->free_gifts) ){
                    $promo->free_gifts = json_decode($promo->free_gifts);
                }
                foreach ($promo->discounts as $discount) {
                    $product_ids[] = ['id' => $discount->product_id];
                }
                $promo->product_id = $product_ids;
                $promo->specific_promo_product_ids = $product_ids;
            }

            

            
            return $promo;
        }
    }
}