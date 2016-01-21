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
use ECEPharmacyTree\Repositories\PromoRepository;


class PromoController extends Controller
{
    function __construct(PromoRepository $promo) {
        $this->promo = $promo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $today =  Carbon::today('Asia/Manila')->addHours(23 );
        // $promos = Promo::where('end_date', '>=', $today)->get();
        $products = Product::all();

        $promos = Promo::where('end_date', '>=', $today)
            ->with([
                'discounts' => function($query) {
                    $query->where('deleted_at', '=', null)->with([
                        'product' => function($query){
                            $query->where('deleted_at', '=', null);
                        }
                    ]);
                }
            ])->get();
       
        if( isset($promo->discounts) && !empty($promo->discounts) ){
            foreach ($promo->discounts as $discount) {
                $discount->load('product');
            }
        }

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
        $response = $this->promo->save($input);

        if( $response ){
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
        $product_ids = [];

        if( isset( $promo->id ) ){
            foreach ($promo->discounts as $discount) {
                $product_ids[] = ['id' => $discount->product_id];
            }
            $promo->product_id = $product_ids;
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
            session()->flash("flash_message", ["msg" => "Promo information has been updated.", "type" => "info"]);
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
        $dfp = DiscountsFreeProduct::findOrFail($id);
        if( $dfp->has_free_gifts == 1 ){ // get Free Gifts
            $free_products = $dfp->free_products;
            if( count($free_products) >= 1 ){
                $free_products->load('product');
            }

        } 

        if( $dfp->minimum_purchase != 0 && $dfp->quantity_required == 0 ){
            $dfp->discount_detail_minimum_type = 'minimum_purchase';
        }else{
            $dfp->discount_detail_minimum_type = 'quantity_required';
        }
        return $dfp;
    }

    public function update_details(){
        $input = Input::all();
        
        if( $this->promo->update_details($input) )
            return Redirect::back()->withFlash_message([
                'type' => 'success',
                'msg' => "Promo details has been successfully saved."
            ]);

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
