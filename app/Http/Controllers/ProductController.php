<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Input;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Redirect;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\ProductCategory;
use ECEPharmacyTree\ProductSubcategory;

class ProductController extends Controller
{

    function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $products = Product::all();

        $categories = ProductCategory::orderBy('name')->get();
        $subcategories = ProductSubcategory::orderBy('name')->get();
        $category_names = array();
        foreach ($categories as $category) {
            $category_names[$category->id] = $category->name;
        }

        return view('admin.products')->withProducts($products)
            ->withCategories($categories)->withSubcategories($subcategories)
            ->withCategory_names($category_names)->withTitle('Products');
    }

    public function all_include_deleted(){
        $products = Product::withTrashed()->get();
        $categories = ProductCategory::orderBy('name')->get();
        $subcategories = ProductSubcategory::orderBy('name')->get();
        $category_names = array();
        foreach ($categories as $category) {
            $category_names[$category->id] = $category->name;
        }

        return view('admin.products')->withProducts($products)
            ->withCategories($categories)->withSubcategories($subcategories)
            ->withCategory_names($category_names)->withTitle('Products');
        return Redirect::to('/products')->withProducts($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = Input::all();
        $product = new Product;
        $product->name = ucfirst( $input['name'] );
        $product->generic_name = ucfirst( $input['generic_name'] );
        $product->description = ucfirst( $input['description'] );
        $product->prescription_required = $input['prescription_required'];
        $product->unit_cost = $input['unit_cost'];
        $product->price = $input['price'];
        $product->unit = str_singular( $input['unit'] );
        $product->packing = $input['packing'];
        $product->qty_per_packing = $input['qty_per_packing'];
        $product->subcategory_id = $input['subcategory_id'];
        $product->sku = $input['sku'];
        $product->critical_stock = $input["critical_stock"] != "" ? $input["critical_stock"] : null;

        if( $product->save() )
            return Redirect::to( route('Products::index') )->withFlash_message([
                'type' => 'info', 'msg' => "New product has been added successfully."
            ]);
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if( isset( $product->id ) )
            return $product->toJson();
        return [];
        // return Redirect::to( route('products') );
    }

    public function show_all(){
        $products = Product::all();
        return $products;
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
        $product = Product::findOrFail($input['id'] );
        $product->name = ucfirst( $input['name'] );
        $product->generic_name = ucfirst( $input['generic_name'] );
        $product->description = ucfirst( $input['description'] );
        $product->prescription_required = $input['prescription_required'];
        $product->unit_cost = $input['unit_cost'];
        $product->price = $input['price'];
        $product->unit = str_singular( $input['unit'] );
        $product->packing = $input['packing'];
        $product->qty_per_packing = $input['qty_per_packing'];
        $product->subcategory_id = $input['subcategory_id'];
        $product->sku = generate_sku();
        $product->critical_stock = $input["critical_stock"] != "" ? $input["critical_stock"] : null;

        if( $product->save() )
            return Redirect::to( route('Products::index') )->withFlash_message([
                'type' => 'success', 'msg' => "Changes has been saved successfully."
            ]);
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $product = Product::findOrFail(Input::get("id"));
        if( $product->delete() ){
            session()->flash("flash_message", ["msg" => "$product->name has been deleted.", "type" => "danger"]);
            return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function restore(){

        if( Request::ajax() ){
            $product = Product::withTrashed()->findOrFail( Input::get('id') );

            if( $product->restore() )  {
                session()->flash("flash_message", ["msg" => "$product->name has been restored.", "type" => "info"]);
                return json_encode( array("status" => "success") );
            }
            return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );

        }

        session()->flash("flash_message", array("msg" => "Error 403. Forbidden..You are not allowed to access this feature.", "type" => "danger"));
        return json_encode( array("status" => "403", "msg" => "Error 403. Forbidden..You are not allowed to access this feature.") );
    }
}
