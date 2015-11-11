<?php

namespace ECEPharmacyTree\Http\Controllers;

use Input;
use Validator;

use Illuminate\Http\Request;
use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Product;
use ECEPharmacyTree\ProductGroup;

class ProductGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        $product_groups = ProductGroup::all();

        return view('admin.product-groups')->withProducts($products)
            ->withTitle('Product Groups')->withProduct_groups($product_groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $input = Input::all();
        dd($input);
        $group = new ProductGroup;
        $group->name = $input['name'];
        $group->points = $input['points'];
        if( $group->save() )
            return redirect()->back()->withFlash_message([
                'msg' => 'New product group has been added successfully.',
                'type' => 'info'
            ]);
        return redirect()->back()->withFlash_message([
            'msg' => 'Sorry, we can\'t process your request right now. Please try again later.',
            'type' => 'danger'
        ]);
    }

    public function validator(array $data){
        $rules = [
            'name' => 'required|max:255|min:3',
            'points' => 'required',
        ];

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
