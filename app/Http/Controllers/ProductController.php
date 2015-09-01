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
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $products = Product::all();
        $categories = ProductCategory::all();
        $subcategories = ProductSubcategory::all();

        return view('admin.products')->withProducts($products)
            ->withCategories($categories)->withSubcategories($subcategories);
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
        $product->name = $input['name'];
        $product->generic_name = $input['generic_name'];
        $product->description = $input['description'];
        $product->prescription_required = $input['prescription_required'];
        $product->price = $input['price'];
        $product->unit = $input['unit'];
        $product->packing = $input['packing'];
        $product->qty_per_packing = $input['qty_per_packing'];
        $product->subcategory_id = $input['subcategory_id'];
        $product->sku = generateSku();

        if( $product->save() )
            return Redirect::to( route('products') );
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
