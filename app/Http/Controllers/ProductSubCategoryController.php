<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Redirect;
use Input;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\ProductSubcategory;
use ECEPharmacyTree\ProductCategory;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ProductSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
        $subcategory = new ProductSubcategory;
        $subcategory->name = Input::get('name');
        $subcategory->category_id = Input::get('category_id');
        if( $subcategory->save() )
            return Redirect::to( route('Products::index') )->withFlash_message([
                'msg' => ucfirst($subcategory->name)." has been added to products sub-categories.",
                'type' => 'info'
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
        $subcategory = ProductSubcategory::find($id);
        return $subcategory->toJson();
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
        $subcategory = ProductSubcategory::find( Input::get('id') );
        $subcategory->name = Input::get('name');
        $subcategory->category_id = Input::get('category_id');
        if( $subcategory->save() )
            return Redirect::to( route('Products::index') )->withFlash_message([
                'msg' => "Your changes has been saved successfully.",
                'type' => 'info'
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
        $subcategory = ProductSubcategory::withTrashed()->findOrFail(Input::get('id'));
        $name = $subcategory->name;
        if( $subcategory->delete() ){
            session()->flash("flash_message", ["msg" => "$name has been deleted from sub-categories.", "type" => "danger"]);
            return json_encode( array("status" => "success") );
        }
        session()->flash("flash_message", ["msg" => "Sorry we failed to delete $name from sub-categories. Please try again later.", "type" => "danger"]);
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}
