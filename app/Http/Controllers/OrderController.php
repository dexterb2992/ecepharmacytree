<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Order;
use DB;
use Redirect;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orders = Order::all();

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
        $order_details = $order->order_details()->get();

        return view('admin.order')->withOrder($order)->withOrderDetails($order_details);
    }

     /**
     * Fulfill Orders
     *
     * @param  int  $id
     * @return Response
     */
     function fulfill_orders() {
        $when_and_thens = "";
        $where_ids = "";

        foreach($_POST['order_fulfillment_qty'] as $key => $value) {
            echo "key = ".$key." value=".$value;
            $when_and_thens .= " WHEN " . $key . " THEN ".$value;
            $where_ids .= $key . ",";
        }
        $where_ids = substr($where_ids, 0, strlen($where_ids) - 1);

        $sql = "UPDATE order_details SET qty_fulfilled = CASE id ".$when_and_thens." END WHERE id IN (".$where_ids.")";

        $affected = DB::update($sql);

        return Redirect::back();
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
