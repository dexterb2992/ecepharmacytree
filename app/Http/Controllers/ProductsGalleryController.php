<?php
namespace ECEPharmacyTree\Http\Controllers;

use Redirect;
use Input;
use Request;
use Image;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\ProductsGallery;

class ProductsGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
    public function store(Request $request)
    {
        $product_id = Input::get('product_id');

        if( Input::file() ){
            $image = Input::file('file');

            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = public_path('images/product-photo-gallery/');

            // save original image
            $original_imagepath = $path.$filename; 
            Image::make($image->getRealPath())->save($original_imagepath);

            $gallery = new ProductsGallery;
            $gallery->filename = $filename;
            $gallery->product_id = $product_id;
            
            if( $gallery->save() )
                return json_encode([
                    "msg" => "New photos has been added to gallery.", 
                    "status_code" => "200"
                ]);
                
            return json_encode([
                "msg" => "Sorry, we can't process your request right now. Please try again later.",
                "status_code" => "500"
            ]);
            
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);
        return $product->galleries;
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
