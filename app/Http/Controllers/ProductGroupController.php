<?php

namespace ECEPharmacyTree\Http\Controllers;

use Input;
use Validator;
use Request;

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

        if( $this->validator($input)->passes() ){

            $group = new ProductGroup;
            $group->name = $input['name'];
            $group->points = $input['points'];
            if( $group->save() )
                if( isset($input['products_involved']) && count($input['products_involved']) > 0 ){
                    foreach ($input['products_involved'] as $key => $value) {
                        $product = Product::find($value);
                        $product->product_group_id = $group->id;
                        $product->save();
                    }
                }

                return redirect()->back()->withFlash_message([
                    'msg' => 'New product group has been added successfully.',
                    'type' => 'info'
                ]);

            return redirect()->back()->withFlash_message([
                'msg' => 'Sorry, we can\'t process your request right now. Please try again later.',
                'type' => 'danger'
            ]);
        }

        return redirect()->back()->withInput()->withErrors();
        
    }

    public function validator(array $data){
        $rules = [
            'name' => 'required|max:255|min:3',
            'points' => 'required',
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( Request::ajax() ){
            $product_group = ProductGroup::findOrFail($id);
            $product_group->products_involved = $product_group->products;
            return $product_group;
        }

        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later." ) );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $input = Input::all();
        if( $this->validator($input)->passes() ){
            $group = ProductGroup::findOrFail($input['id']);
            $group->name = $input['name'];
            $group->points = $input['points'];
            if( $group->save() )
                if( isset($input['products_involved']) && count($input['products_involved']) > 0 ){
                    // make sure to remove the products which are removed from this group
                    $o_prods = $group->products;

                    foreach ($o_prods as $prod) {
                       Product::where('product_group_id', $prod->product_group_id)->update(['product_group_id' => 0]);
                    }

                    foreach ($input['products_involved'] as $key => $value) {
                        $product = Product::find($value);
                        $product->product_group_id = $group->id;
                        $product->save();
                    }
                }

                return redirect()->back()->withFlash_message([
                    'msg' => 'New product group has been added successfully.',
                    'type' => 'info'
                ]);

            return redirect()->back()->withFlash_message([
                'msg' => 'Sorry, we can\'t process your request right now. Please try again later.',
                'type' => 'danger'
            ]);
        }

        return redirect()->back()->withInput()->withErrors();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $id = Input::get("id");
        $group = ProductGroup::findOrFail($id);
        if( $group->delete() ){
            Product::where('product_group_id', $id)->update([
                'product_group_id' => 0
            ]);

            return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}
