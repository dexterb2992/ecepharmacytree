<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Input;
use DB;

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
        $a = Input::get('startfrom');
        $startfrom = isset($a) ? $a : '';
        $products = Product::whereRaw("deleted_at is null")
            ->where('name', 'like', "$startfrom%")
            ->paginate(100);

        $products->setPath(route('Products::index'));

        $categories = ProductCategory::orderBy('name')->get();
        $subcategories = ProductSubcategory::orderBy('name')->get();
        $category_names = array();
        foreach ($categories as $category) {
            $category_names[$category->id] = $category->name;
        }

        return view('admin.products')
            ->withProducts($products)
            ->withCategories($categories)
            ->withSubcategories($subcategories)
            ->withCategory_names($category_names)
            ->withStartfrom($startfrom)
            ->withTitle('Products');
    }

    public function search(){
        $input = Input::all();
        $keyword = isset($input['q']) ? $input['q'] : '';
        $products = Product::whereRaw("name like '%$keyword%'")->paginate(100);

        $categories = ProductCategory::orderBy('name')->get();
        $subcategories = ProductSubcategory::orderBy('name')->get();
        $category_names = array();
        foreach ($categories as $category) {
            $category_names[$category->id] = $category->name;
        }

        return view('admin.products')->withProducts($products)
            ->withCategories($categories)->withSubcategories($subcategories)
            ->withCategory_names($category_names)->withTitle('Products')->withSource("search");
    }

    public function get_json(){
        $input = Input::all();
        if( isset($input['action']) && $input['action'] == "get_count" ){
            $count = Product::all()->count();
            if( $input['cached_count'] == $count )
                return json_encode(array('status' => 200));
        }
        $products = Product::select('id', 'name', 'packing', 'unit', 'qty_per_packing')->get();
        return $products;
    }

    public function all_include_deleted(){
        // $products = Product::withTrashed()->get();
        $listing = DB::table('products')->paginate(100);
        $product_count = Product::onlyTrashed()->count();
        $products = array();
        foreach ($listing as $list) {
            $product = Product::onlyTrashed()->find($list->id);
            if( !is_null($product) )
                array_push($products, $product);
        }
        
        $categories = ProductCategory::orderBy('name')->get();
        $subcategories = ProductSubcategory::orderBy('name')->get();
        $category_names = array();
        foreach ($categories as $category) {
            $category_names[$category->id] = $category->name;
        }

        return view('admin.products')->withProducts($products)
            ->withCategories($categories)->withSubcategories($subcategories)
            ->withCategory_names($category_names)->withTitle('Products')
            ->withPaginated_lists($listing)->withProduct_count($product_count);
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
        $product->is_freebie = isset($input['is_freebie']) ? $input['is_freebie'] : 0;

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
        $product->sku = $input['sku'];
        $product->critical_stock = $input["critical_stock"] != "" ? $input["critical_stock"] : null;
        $product->is_freebie = isset($input['is_freebie']) ? $input['is_freebie'] : 0;
        
        if( $product->save() )
            return Redirect::back()->withFlash_message([
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
            sleep(1);
            return json_encode( array("status" => "success") );
        }
        
        session()->flash("flash_message", ["msg" => "Sorry we failed process your request. Please try again later.", "type" => "danger"]);                  
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function restore(){

        if( Request::ajax() ){
            $product = Product::withTrashed()->findOrFail( Input::get('id') );

            if( $product->restore() )  {
                session()->flash("flash_message", ["msg" => "$product->name has been restored.", "type" => "info"]);
                sleep(1);
                return json_encode( array("status" => "success") );
            }
            return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );

        }

        session()->flash("flash_message", array("msg" => "Error 403. Forbidden..You are not allowed to access this feature.", "type" => "danger"));
        return json_encode( array("status" => "403", "msg" => "Error 403. Forbidden..You are not allowed to access this feature.") );
    }
}
