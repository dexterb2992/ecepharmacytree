<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\ProductCategory;
use ECEPharmacyTree\ProductSubcategory;
use Input;
use Redirect;


class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = ProductCategory::orderBy('name')->get();
        $subcategories = ProductSubcategory::orderBy('name')->get();
        $category_names = array();
        foreach ($categories as $category) {
            $category_names[$category->id] = $category->name;
        }

        return view('admin.product-categories')->withCategories($categories)->withSubcategories($subcategories)
            ->withCategory_names($category_names);
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
    public function store()
    {
        $category = new ProductCategory;
        $category->name = Input::get('name');
        if( $category->save() ){
            return Redirect::to( route('Products::index') )->withFlash_message([
                "msg" => "New product category has been added successfully.",
                "type" => "info"
            ]);
        }
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
        $category = ProductCategory::find($id);
        return $category->toJson();
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
        $category = ProductCategory::find( Input::get('id') );
        $category->name = Input::get('name');
        if( $category->save() ){
           return Redirect::to( route('Products::index') )->withFlash_message([
                "msg" => "Product category has been updated successfully.",
                "type" => "info"
            ]);
        }
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
        if( ProductCategory::destroy( Input::get( 'id' ) ) ){
            return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}
