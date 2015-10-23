<?php
namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Intput;
use Redirect;
use ECEPharmacyTree\Inventory;
use ECEPharmacyTree\Product;
use Input;
use Carbon\Carbon;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $inventories = Inventory::where('quantity', '>', '0')
            ->where('branch_id', session()->get('selected_branch'))->get();
        $products = Product::all();
        return view('admin.inventories')->withInventories($inventories)
            ->withProducts($products)->withTitle('Manage Inventory');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        /**
         *  Note: the quantity that will be saved will be per product unit
         *        so, if per packing there are 12 units and the quantity
         *        we received is 6, therefore, the quantity that will be saved 
         *        to database is 72
         */
        $input = Input::all();
        $inventory = new Inventory;
        $inventory->product_id = $input["product_id"];

        $product = Product::find( $inventory->product_id );
        $quantity = $product->qty_per_packing * $input["quantity"];

        $inventory->quantity = $quantity;
        $inventory->expiration_date = $input["expiration_date"];
        $inventory->branch_id = session()->get('selected_branch');
        if( $inventory->save() ){
            return Redirect::to( route('Inventory::index') );
        }
        return Redirect::to( route('Inventory::index') )->withFlash_message("Sorry, but we can't process your request right now. Please try again later.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if( Request::ajax() ){
            $inventory = Inventory::findOrFail($id);
            return $inventory->toJson();
        }   
        return false;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit()
    {
       
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
        $inventory = Inventory::findOrFail( $input["id"] );
        $inventory->product_id = $input["product_id"];
        $inventory->quantity = $input["quantity"];
        $inventory->expiration_date = $input["expiration_date"];
        if( $inventory->save() ){
            return Redirect::to( route('Inventory::index') );
        }
        return Redirect::to( route('Inventory::index') )->withFlash_message("Sorry, but we can't process your request right now. Please try again later.");
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {   

        if( Request::ajax() ){

            if( Inventory::destroy( Input::get('id') ) )  
                return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later." ) );
    }
}
