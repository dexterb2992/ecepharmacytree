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
        $today =  Carbon::today('Asia/Manila');
        // $products = Product::all();

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

        return view('admin.promo')->withPromos($promos)->withTitle('Promotions and Discounts');
            // ->withProducts($products);
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
        // dd($input);
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

        return $this->promo->show($id);
        
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
        if( $this->promo->destroy(Input::get("id")) ){
            session()->flash("flash_message", ["msg" => "Promo has been successfully removed.", "type" => "danger"]);
            return json_encode( array("status" => "success") );
        }

        session()->flash("flash_message", ["msg" => "Sorry, we can't process your request right now. Please try again later.", "type" => "warning"]);
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
       
    }

    public function details($id){

        return $this->promo->discount_details($id);
    }

    public function update_details(){
        
        if( $this->promo->update_details(Input::all()) )
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
