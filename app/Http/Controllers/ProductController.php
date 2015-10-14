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
        $input = Input::all();
        $product = new Product;
        $product->name = ucfirst( $input['name'] );
        $product->generic_name = ucfirst( $input['generic_name'] );
        $product->description = ucfirst( $input['description'] );
        $product->prescription_required = $input['prescription_required'];
        $product->price = $input['price'];
        $product->unit = str_singular( $input['unit'] );
        $product->packing = $input['packing'];
        $product->qty_per_packing = $input['qty_per_packing'];
        $product->subcategory_id = $input['subcategory_id'];
        $product->sku = generateSku();
        $product->safety_stock = $input["safety_stock"] != "" ? $input["safety_stock"] : null;

        if( $product->save() )
            return Redirect::to( route('Products::index') );
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
        // return Redirect::to( route('products') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
        $product->price = $input['price'];
        $product->unit = str_singular( $input['unit'] );
        $product->packing = $input['packing'];
        $product->qty_per_packing = $input['qty_per_packing'];
        $product->subcategory_id = $input['subcategory_id'];
        $product->sku = generateSku();
        $product->safety_stock = $input["safety_stock"] != "" ? $input["safety_stock"] : null;

        if( $product->save() )
            return Redirect::to( route('Products::index') );
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
            return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}
