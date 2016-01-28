<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use Input;
use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Doctor;
use ECEPharmacyTree\User;
use ECEPharmacyTree\ProductGroup;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\Promo;
use ECEPharmacyTree\Inventory;
use ECEPharmacyTree\StockReturn;
use ECEPharmacyTree\Order;
use ECEPharmacyTree\Branch;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( null === session()->get('selected_branch') )
            return json_encode( array( 'error' => 'Please select branch.', 'status' => 500 ) );

        $patients = Patient::where('is_new', '=', 1)->count();
        $users = User::where('is_new', '=', 1)->where('branch_id', '=', session()->get('selected_branch'))->count();
        $product_groups = ProductGroup::where('is_new', '=', 1)->count();
        $products = Product::where('is_new', '=', 1)->count();
        $doctors = Doctor::where('is_new', '=', 1)->count();
        $promos = Promo::where('is_new', '=', 1)->count();
        $inventories = Inventory::where('is_new', '=', 1)->where('branch_id', '=', session()->get('selected_branch'))->count();
        $stock_returns = StockReturn::where('is_new', '=', 1)->with([
            'order' => function ($query){
                $query->where('branch_id', '=', session()->get('selected_branch'));
            }
        ])->count();

        $orders = Order::where('is_new', '=', 1)->where('branch_id', '=', session()->get('selected_branch'))->count();

        $branches = Branch::where('is_new', '=', 1)->count();

        $unseen_notifications = [];

        $sources = array(
            "sidebar_members"  => $patients,
            "sidebar_employees" => $users,
            "sidebar_product_groups"    => $product_groups,
            "sidebar_products"  => $products,
            "sidebar_doctors"  => $doctors,
            "sidebar_promos"    => $promos,
            "sidebar_stock_items"   => $inventories,
            "sidebar_stock_returns" => $stock_returns,
            "sidebar_orders"    => $orders,
            "sidebar_branches"  => $branches
        );

        foreach ($sources as $key => $value) {
            if( $value > 0 ){
                $unseen_notifications[$key] = $value;
            }
        }

        return json_encode($unseen_notifications);
    }

    public function update(){
        $input = Input::all();

        $sources = array(
            'sidebar_members' => 'ECEPharmacyTree\Patient',
            'sidebar_employees' => 'ECEPharmacyTree\User',
            'sidebar_product_groups' => 'ECEPharmacyTree\ProductGroup',
            'sidebar_products' => 'ECEPharmacyTree\Product',
            'sidebar_doctors' => 'ECEPharmacyTree\Doctor',
            'sidebar_promos' => 'ECEPharmacyTree\Promo',
            'sidebar_stock_items' => 'ECEPharmacyTree\Inventory' ,
            'sidebar_stock_returns' => 'ECEPharmacyTree\StockReturn',
            'sidebar_orders' => 'ECEPharmacyTree\Order',
            'sidebar_branches' => 'ECEPharmacyTree\Branch'
        );
        
        foreach ($sources as $key => $value) {
            if( $key == $input['source'] ){
                $value::where('is_new', '=', 1)->update(['is_new'=> 0]);
                return json_encode(['status' => 200]);
            }
        } 

        return json_encode(['status' => 500]);
        
    }
}
