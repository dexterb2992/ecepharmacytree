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

        $check_response = $this->promo->check($input);
        if( $check_response['is_allowed'] == false ){
            session()->flash("flash_message", ["msg" => $check_response['msg'], "type" => "important"]);
            return Redirect::to( route('Promo::index') );
        }

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
            return $promo;
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

        if( $this->promo->update($input) ){

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
