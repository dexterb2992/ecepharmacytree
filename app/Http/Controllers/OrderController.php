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
use Input;
use ECEPharmacyTree\Log;
use URL;
use ECEPharmacyTree\OrderLotNumber;
use ECEPharmacyTree\Repositories\GCMRepository;


class OrderController extends Controller
{
    protected $gcm;

    function __construct(GCMRepository $gcm)
    {
        $this->gcm = $gcm;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $orders = Order::where('branch_id', session()->get('selected_branch'))->orderBy('id', 'DESC')->get();


        // $this->firstOrderFirstServeSort($orders);

        return view('admin.orders')->withOrders($orders);
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
        $order_details = DB::select("call get_order_details_with_availablestocks(".$order->id.", ".$order->branch_id.")");
        $order_details_with_prescriptions = $order->order_details()->whereRaw('prescription_id > 0')->get();

        return view('admin.order')->withOrder($order)->withOrderDetails($order_details)->withOrderDetailsWithPrescriptions($order_details_with_prescriptions);
    }


    public function show_all(){
        // $orders = Order::all();
        $orders = Order::where('branch_id', session()->get('selected_branch'))
        ->where('is_returned', '=', 0)->orderBy('id', 'DESC')->get();
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

            foreach ($order->order_details as $key => $order_detail) {
                $order_detail->load('product');
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

        if(isset($input['order_fulfillment_qty'])){
            $order_detail_pid = $input['order_detail_pid'];
            foreach($input['order_fulfillment_qty'] as $key => $value) {
                $when_and_thens .= " WHEN " . $key . " THEN qty_fulfilled+".$value;
                $where_ids .= $key . ",";

                $prod_id = $order_detail_pid[$key];

                $this->deductInventory($prod_id, $value, $input['branch_id'], $input['order_id']);
            }

            $where_ids = substr($where_ids, 0, strlen($where_ids) - 1);

            $sql = "UPDATE order_details SET qty_fulfilled = CASE id ".$when_and_thens." END WHERE id IN (".$where_ids.")";

            $affected = DB::update($sql);

            if($affected > 0){
                $order = Order::findOrFail($input['order_id']);
                $patient = $order->patient()->first();

                if($order->modeOfDelivery == 'delivery'){
                    $multilined_notif = array(1 => 'Your order is ready for delivery !', 2 => 'Your order must arrive on or before specified date', 3 => 'Thank you for your order.', 4 => 'Order#'.$order->id);
                } else if($order->modeOfDelivery == 'pickup'){
                    $multilined_notif = array(1 => 'Your order is ready for pickup !', 2 => 'You may now visit your selected ECE branch.', 3 => 'Thank you for your order.', 4 => 'Order#'.$order->id);
                } else {
                    $multilined_notif = array(1 => 'Your order is ready!', 2 => 'You may now visit your selected ECE branch.', 3 => 'Thank you for your order.', 4 => 'Order#'.$order->id);
                }

                $data = array( 'message' => json_encode($multilined_notif), 'title' => 'Pharmacy Tree', 'intent' => 'OrderDetailsActivity', 
                    'order_id' => $order->id);

                $this->gcm->sendGoogleCloudMessage($data, $patient->regId);
            }
        }

        return Redirect::back();
    }

    function deductInventory($product_id, $quantity, $branch_id, $order_id) {
        $inventories = Inventory::where('product_id', $product_id)->where('branch_id', $branch_id)->orderBy('expiration_date', 'ASC')->get();

        $_quantity = $quantity;
        $remains = 0;

        foreach ($inventories as $inventory) {
            if($remains > 0)
                $_quantity = $remains;

            if($_quantity  > $inventory->available_quantity){
                $remains = $_quantity - $inventory->available_quantity;
                $old_qty = $inventory->available_quantity;
                $inventory->available_quantity = 0;
                $new_qty = $inventory->available_quantity;

                if($inventory->save()){
                    $this->logAdjustment($inventory, $old_qty, $new_qty, $order_id);
                }
            } else {
                $old_qty = $inventory->available_quantity;
                $inventory->available_quantity = $inventory->available_quantity - $_quantity;
                $new_qty = $inventory->available_quantity;

                if($inventory->save()){
                    if($inventory->save()){
                        $this->logAdjustment($inventory, $old_qty, $new_qty, $order_id);
                    }
                }
                break;
            }
        }
    }

    function logAdjustment($inventory, $old_qty, $new_qty, $order_id){
        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->action = 'Adjusted an inventory information with <a href="'.route('Inventory::index').'?q='.$inventory->lot_number.'" 
        target="blank">Lot #'.$inventory->lot_number.'</a>'
        .' - changed quantity from '.$old_qty.' to '.$new_qty
        .'. <br/><code>Order Fulfillment: </code> <a href="'.URL::route('orders').'/'.$order_id.'" 
        target="blank">Order #'.$order_id.'</a>';
        $log->table = 'inventories';
        if( $log->save() ){
            $qty_used = $old_qty-$new_qty;
            $oln = new OrderLotNumber;
            $oln->order_id = $order_id;
            $oln->inventory_id = $inventory->id;
            $oln->quantity = $qty_used;
            $oln->save();
        }
    }
}
