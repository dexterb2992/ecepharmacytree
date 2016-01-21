<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Order;
use DB;
use Redirect;
use Auth;
use ECEPharmacyTree\Inventory;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $orders = Order::where('branch_id', session()->get('selected_branch'))->orderBy('id', 'DESC')->get();

        return view('admin.orders')->withOrders($orders);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $order_details = DB::select("SELECT * from order_details where order_id = 108");
        $order_details_with_prescriptions = $order->order_details()->whereRaw('prescription_id > 0')->get();

        return view('admin.order')->withOrder($order)->withOrderDetails($order_details)->withOrderDetailsWithPrescriptions($order_details_with_prescriptions);
    }


    public function show_all(){
        // $orders = Order::all();
        $orders = Order::where('branch_id', Auth::user()->branch->id)->orderBy('id', 'DESC')->get();
        $orders->load('patient');
        $orders->load('order_details');
        $orders->load('billing');
        $orders->load('branch');
        $orders->load('stock_returns');


        foreach ($orders as $order) {

            $order->branch->load('barangay');
            $order->branch->barangay->load('municipality');
            $order->branch->barangay->municipality->load('province');
            $order->branch->barangay->municipality->province->load('region');

            foreach ($order->stock_returns as $stock_return) {
                $stock_return->load('product_stock_returns');
            }

            foreach ($order->order_details as $order_detail) {
                $order_detail->load('product');
                // todo tomorrow:
                // logics here to determine the max returnable quantity when there are already stocks that had
                // been returned by the customer
                // 
            }

            
            
        }

        return $orders;
    }

     /**
     * Fulfill Orders
     *
     * @param  int  $id
     * @return Response
     */
     function fulfill_orders() {
        $input = Input::all();

        $when_and_thens = "";
        $where_ids = "";

        foreach($input['order_fulfillment_qty'] as $key => $value) {
            $when_and_thens .= " WHEN " . $key . " THEN qty_fulfilled+".$value;
            $where_ids .= $key . ",";

            $this->deductInventory($key, $value, $input['branch_id']);
        }

        $where_ids = substr($where_ids, 0, strlen($where_ids) - 1);

        $sql = "UPDATE order_details SET qty_fulfilled = CASE id ".$when_and_thens." END WHERE id IN (".$where_ids.")";

        $affected = DB::update($sql);

        //move this code to fulfill items on admin
        // if($order_saved) {
            
        // }

        return Redirect::back();
    }

    function deductInventory($product_id, $quantity, $branch_id){
        $inventories = Inventory::where('product_id', $product_id)->where('branch_id', $branch_id)->orderBy('expiration_date', 'ASC')->get();
        $_quantity = $quantity;
        $remains = 0;

        foreach ($inventories as $inventory) {
            if($remains > 0)
                $_quantity = $remains;

            if($_quantity  > $inventory->available_quantity){
                $remains = $_quantity - $inventory->available_quantity;
                $inventory->available_quantity = 0;
                $inventory->save();
            } else {
                $inventory->available_quantity = $inventory->available_quantity - $_quantity;
                $inventory->save();
                break;
            }
        }
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
