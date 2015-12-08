<?php

namespace ECEPharmacyTree\Http\Controllers;

use Carbon\Carbon;
use Input;
use Redirect;
use Request;

use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Promo;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\DiscountsFreeProduct;
use ECEPharmacyTree\FreeProduct;


class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $today =  Carbon::today('Asia/Manila')->addHours(23 );
        $promos = Promo::where('end_date', '>=', $today)->get();
        $products = Product::all();

        return view('admin.promo')->withPromos($promos)->withTitle('Promotions and Discounts')
            ->withProducts($products);
        // return view('admin.promo');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $promo = new Promo;
        // dd($input);
        $promo->long_title = $input["long_title"];
        $promo->start_date = $input["start_date"];
        $promo->end_date = $input["end_date"];
        $promo->product_applicability = $input["product_applicability"];
        $promo->minimum_purchase_amount = $input["minimum_purchase_amount"]; // optional
        $promo->offer_type = $input["offer_type"];
        $promo->generic_redemption_code = $input["generic_redemption_code"];

        if( $promo->save() ){
            if( isset($input['product_id']) && count($input['product_id']) > 0 ){
                foreach ($input['product_id'] as $key => $value) {
                    $dfp = new DiscountsFreeProduct;
                    $dfp->promo_id = $promo->id;
                    $dfp->product_id = $value;
                    $dfp->save();
                }
            }

            session()->flash("flash_message", ["msg" => "New promo has been added successfully.", "type" => "success"]);
            return Redirect::to( route('Promo::index') );
        }

        session()->flash("flash_message", ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "warning"]);
        return Redirect::to( route('Promo::index') );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

        $promo = Promo::find($id);
        if( isset( $promo->id ) ){
            if( $promo->product_applicability == "SPECIFIC_PRODUCTS" && count($promo->discounts) >= 1){
                $promo->load('product');
            }
            return $promo->toJson();
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update()
    {
        $input = Input::all();
        $promo = Promo::findOrFail($input['id']);
        $promo->long_title = $input["long_title"];
        $promo->start_date = $input["start_date"];
        $promo->end_date = $input["end_date"];
        $promo->generic_redemption_code = $input['generic_redemption_code'];
        $promo->product_applicability = $input["product_applicability"];
        $promo->minimum_purchase_amount = $input["minimum_purchase_amount"];
        $promo->offer_type = $input["offer_type"];
        $promo->generic_redemption_code = $input["generic_redemption_code"];

        if( $promo->save() ){
            $dfps = DiscountsFreeProduct::where('promo_id', $promo->id)->delete();

            if( isset($input['product_id']) && (count($input['product_id']) > 0) && ($input["product_applicability"] == 'SPECIFIC_PRODUCTS') ){
                foreach ($input['product_id'] as $key => $value) {
                    $dfp = new DiscountsFreeProduct;
                    $dfp->promo_id = $promo->id;
                    $dfp->product_id = $value;
                    $dfp->save();
                }
            }
            session()->flash("flash_message", ["msg" => "Promo information has been updateed.", "type" => "info"]);
            return Redirect::to( route('Promo::index') );
        }
        session()->flash("flash_message", ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "warning"]);

        return Redirect::to( route('Promo::index') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $product = Promo::findOrFail(Input::get("id"));
        if( $product->delete() ){
            session()->flash("flash_message", ["msg" => "Promo has been successfully removed.", "type" => "danger"]);
            return json_encode( array("status" => "success") );
        }
        session()->flash("flash_message", ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "warning"]);
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function details($id){
        $dfp = DiscountsFreeProduct::find($id);
        if( $dfp->type == "2" ){ // Free Gift
            $free_products = $dfp->free_products;
            if( count($free_products) >= 1 ){
                $free_products->load('product');
            }
        } 
        return $dfp;
    }

    public function update_details(){
        $input = Input::all();
        // dd($input);
        $dfp = DiscountsFreeProduct::find($input['id']);
        $dfp->quantity_required = $input['quantity_required'];
        $dfp->is_free_delivery = isset($input["is_free_delivery"]) ? $input["is_free_delivery"] : 0;
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
            return Redirect::back()->withFlash_message([
                'type' => 'success',
                'msg' => "Promo details has been successfully saved. PromoID: {$promoID}"
            ])->withAffected_promo_id($promoID);

        return Redirect::back()->withInput()->withFlash_message([
            'type' => 'danger',
            'msg' => "Sorry, we can't process your request right now. Please try again later."
        ]);

    }

    public function gifts(){
        $input = Input::all();
        $dfp = DiscountsFreeProduct::find($input['id']);

        $free_products = $dfp->free_products;
        $free_products->load('product');
        return $free_products;
    }
}
