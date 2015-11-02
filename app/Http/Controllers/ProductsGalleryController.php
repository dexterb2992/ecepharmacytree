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

            $filename  = generateRandomString(6).time() . '.' . $image->getClientOriginalExtension();

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
                    "status_code" => "200",
                    "filename" => $filename,
                    "id" => $gallery->id,
                    "product_id" => $product_id
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

    public function get_primary($product_id, $is_json = true){
        $product = Product::findOrFail($product_id);
        // dd($product->galleries);

        if( $is_json )
            return isset($product->galleries[0]) ? json_encode([
                    'filename' => $product->galleries[0]['filename']
                ]) : json_encode([
                    'filename' => 'nophoto.jpg'
                ]) ;

        return isset($product->galleries[0]) ? $product->galleries[0] : false ;
    }

    public function change_primary($id){
        $gallery = ProductsGallery::findOrFail($id);
        $primary_photo = $this->get_primary($gallery->product->id, false);


        if( !empty($primary_photo) ){
            $old_primary_photo = new ProductsGallery;
            $old_primary_photo->product_id = $primary_photo->product_id;
            $old_primary_photo->filename = $primary_photo->filename;
            $gallery->id = $primary_photo->id;

            if( $primary_photo->delete() && $old_primary_photo->save() && $gallery->save() )
                return json_encode(['msg' => 'Product primary photo successfully updated.', 'status_code' => '200']);
        }
        
        return json_encode(['msg' => 'Sorry, we can\'t process your request right now. Please try again later.', 'status_code' => '500']);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Request::ajax() ){
            $gallery = ProductsGallery::findOrFail($id);
            $filename = $gallery->filename;
            if( $gallery->delete() ){
                $path = public_path('images/product-photo-gallery/');

                // delete photo
                if( file_exists($path.$filename) )
                    unlink($path.$filename);
                    

                return json_encode(["msg" => "Successfully deleted.", "status_code" => 200]);
                    

            }else{
                return json_encode(["msg" => "Sorry, we can't process your request right now. Please try again later.", "status_code" => 500]);
            }
        }else{
            abort(404);
        }
    }
}
