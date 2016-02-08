<?php
namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Intput;
use Redirect;
use Input;
use Carbon\Carbon;
use Auth;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Support\Facades\DB;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Inventory;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\Log;
use ECEPharmacyTree\Order;
use ECEPharmacyTree\StockReturnCode;
use ECEPharmacyTree\StockReturn;
use ECEPharmacyTree\InventoryAdjustment;
use ECEPharmacyTree\OrderLotNumber;
use ECEPharmacyTree\ProductStockReturn;

class InventoryController extends Controller
{

    public function index()
    {   
        $inventories = Inventory::where('available_quantity', '>', 0)
            ->where('branch_id', session()->get('selected_branch'))->paginate(100);
        // $products = Product::all();
        $logs = Log::where('table', 'inventories')->orderBy('id', 'desc')->paginate(100);
        $reason_codes = StockReturnCode::all();
        $orders = Order::where('branch_id', session()->get('selected_branch'))->get();
        // $stock_returns = StockReturn::all();
        $stock_returns = DB::table('stock_returns')
            ->join('orders', function ($join) {
                $join->on('orders.id', '=', 'stock_returns.order_id');
            })->where('orders.branch_id', '=', session()->get('selected_branch'))
            ->get(['stock_returns.id']);

        $new_stock_returns = [];
        foreach ($stock_returns as $stock_return) {
            array_push($new_stock_returns, StockReturn::find($stock_return->id));
           
        }

        $stock_returns = $new_stock_returns;
        
        foreach ($stock_returns as $stock_return) {
            $stock_return->load('order');
            $stock_return->load('inventory');
            $stock_return->order->load('patient');
        }

        return view('admin.inventories')->withInventories($inventories)
            // ->withProducts($products)
            ->withTitle('Stocks Receiving')->withLogs($logs)
            ->withOrders($orders)
            ->withReason_codes($reason_codes)
            ->withStock_returns($stock_returns);
    }

    public function show_all(){
        $inventories = Inventory::where('branch_id', session()->get('selected_branch'))->paginate(100);
        // $products = Product::all();
        $logs = Log::where('table', 'inventories')->orderBy('id', 'desc')->paginate(100);
        $reason_codes = StockReturnCode::all();
        $orders = Order::where('branch_id', session()->get('selected_branch'))->get();
        // $stock_returns = StockReturn::all();
        $stock_returns = DB::table('stock_returns')
            ->join('orders', function ($join) {
                $join->on('orders.id', '=', 'stock_returns.order_id');
            })->where('orders.branch_id', '=', session()->get('selected_branch'))
            ->get(['stock_returns.id']);

        $new_stock_returns = [];
        foreach ($stock_returns as $stock_return) {
            array_push($new_stock_returns, StockReturn::find($stock_return->id));
           
        }

        $stock_returns = $new_stock_returns;
        
        foreach ($stock_returns as $stock_return) {
            $stock_return->load('order');
            $stock_return->load('inventory');
            $stock_return->order->load('patient');
        }

        return view('admin.inventories')->withInventories($inventories)
            // ->withProducts($products)
            ->withTitle('Stocks Receiving')->withLogs($logs)
            ->withOrders($orders)
            ->withReason_codes($reason_codes)
            ->withStock_returns($stock_returns);
    }

    public function store(Request $request)
    {
        // Note: the quantity that will be saved will be 
        //  per product packing

        $input = Input::all();
        
        $inventory = new Inventory;
        $inventory->product_id = $input["product_id"];

        $product = Product::find( $inventory->product_id );
        $quantity = $input["quantity"];

        $inventory->quantity = $quantity;
        $inventory->available_quantity = $quantity;
        $inventory->expiration_date = $input["expiration_date"] != "" ? $input["expiration_date"] : null;
        $inventory->lot_number = $input['lot_number'];
        $inventory->branch_id = session()->get('selected_branch');
        if( $inventory->save() ){
            Log::create([
                'user_id' => Auth::user()->id,
                'action'  => 'Added an inventory with <a href="'.route('Inventory::index').'?q='.$inventory->lot_number.'" 
                                target="blank">Lot# '.$inventory->lot_number.'</a>',
                'table' => 'inventories',
                'branch_id' => session()->get('selected_branch')
            ]);
            return Redirect::to( route('Inventory::index') )->withFlash_message([
                "type" => "success",
                "msg" => "A new stock for $product->name with Lot# $inventory->lot_number has been added successfully."
            ]);
        }
        return Redirect::to( route('Inventory::index') )
            ->withFlash_message([
                "type" => "danger",
                "msg" => "Sorry, but we can't process your request right now. Please try again later."
            ]);
    }

    public function show($id)
    {
        if( Request::ajax() ){
            $inventory = Inventory::findOrFail($id);
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $inventory->created_at)
                ->copy()->tz(Auth::user()->timezone)
                ->format('g:ia \o\n l jS F Y');
            $inventory->date_added = $date;
            $inventory->load('product');
            $inventory->inventories_product_id = $inventory->product->id;

            // get # of sold products on this inventory based on branch_id                                       // available: 15; recived: 20;  naay gi return nga 9 & 2 -> 5 nalang ang sold qty
            $sold_products_count = OrderLotNumber::where('inventory_id', '=', $inventory->id)->sum('quantity');  // 16
            // check order_lot_numbers & product_stock_returns for each inventory 
            $data = ProductStockReturn::where('inventory_id', '=', $inventory->id)->get();
            $returned_sold_products_count = $data->sum('quantity'); 
            $returned_sold_defective_products_count = $data->sum('defective_quantity');

            $sold_products_count = $sold_products_count - ($returned_sold_products_count - $returned_sold_defective_products_count);
            
            $inventory->sold_products_count = $sold_products_count." ".str_auto_plural($inventory->product->packing, $sold_products_count);

            return $inventory->toJson();
        }   
        return 'Whoops! It seems like you are lost. Let\'s go back to <a href="'.url('/').'">dashboard</a>.';
    }

    public function update()
    {
        $input = Input::all();
        $inventory = Inventory::findOrFail( $input["id"] );
        $inventory->product_id = $input["product_id"];
        
        /*$inventory->quantity = $input["quantity"]; // q1 = 4, av = 2, update: q2 = 5;
                                                   // def = q2-q1 = 1, av += def
        $def_q = $input["quantity"] - $inventory->quantity;
        $old_av = $inventory->available_quantity;
        
        $new_av = $old_av + $def_q;

        $inventory->available_quantity = $new_av >= 1 ? $new_av : 0;*/

        $inventory->lot_number = $input['lot_number'];

        $inventory->expiration_date = $input["expiration_date"];
        if( $inventory->save() ){
            Log::create([
                'user_id' => Auth::user()->id,
                'action'  => 'Updated an inventory information with <a href="'.route('Inventory::index').'?q='.$inventory->lot_number.'" 
                                target="blank">Lot #'.$inventory->lot_number.'</a>',
                'table' => 'inventories',
                'branch_id' => session()->get('selected_branch')
            ]);
            return Redirect::to( route('Inventory::index') )->withFlash_message([
                'type' => 'info',
                'msg' => "Inventory information for ".$inventory->product->name." with Lot # $inventory->lot_number has been updated."
            ]);
        }
        return Redirect::to( route('Inventory::index') )
            ->withFlash_message(['type' => 'warning', 'msg' => "Sorry, but we can't process your request right now. Please try again later."]);
    
    }

    public function add_adjustments(){
        $input = Input::all();
        $inventory = Inventory::findOrFail( $input['id'] );
        $old_qty = $inventory->quantity;
        
        $new_quantity = $input["new_quantity"];
        $old_av = $inventory->available_quantity;

        // get # of sold products on this inventory based on branch_id                                       // available: 15; recived: 20;  naay gi return nga 9 & 2 -> 5 nalang ang sold qty
        $sold_products_count = OrderLotNumber::where('inventory_id', '=', $inventory->id)->sum('quantity');  // 16
        $data = ProductStockReturn::where('inventory_id', '=', $inventory->id)->get();
        $returned_sold_products_count = $data->sum('quantity'); 
        $returned_sold_defective_products_count = $data->sum('defective_quantity');

        $sold_products_count = $sold_products_count - ($returned_sold_products_count - $returned_sold_defective_products_count);
        // TODO TOMORROW: check order_lot_numbers & product_stock_returns for each inventory 
        
        if( $new_quantity < $sold_products_count ){
            return redirect()->back()->withFlash_message([
                'type' => 'danger',
                'msg' => "Sorry, you can't adjust an inventory with a new quantity greater than the number of sold products in a particular inventory."
            ]);
        }

        $new_av = $new_quantity - $sold_products_count;

        $inventory->available_quantity = $new_av >= 1 ? $new_av : 0;
        $inventory->quantity = $input['new_quantity'];


        $adjustment = new InventoryAdjustment;
        $adjustment->old_quantity = $old_qty;
        $adjustment->new_quantity = $input['new_quantity'];



        $adjustment->inventory_id = $inventory->id;
        $adjustment->user_id = Auth::user()->id;
        $adjustment->reason = $input['reason'];

        if( $inventory->save() && $adjustment->save() ){
            Log::create([
                'user_id' => Auth::user()->id,
                'action'  => "Adjusted an inventory information with <a href='".route('Inventory::index')."?q=$inventory->lot_number'
                                target='blank'>Lot# $inventory->lot_number</a>
                                and changed received quantity from $old_qty to ".$input['new_quantity']."
                                <br/>Available quantity was changed from $old_av to $new_av. The number of sold items for inventory 
                                with ID# $inventory->id:  $sold_products_count. <br/>
                                <code>Reason:</code> ".$input['reason'],
                'table' => 'inventories',
                'branch_id' => session()->get('selected_branch')
            ]);

            return Redirect::back()->withFlash_message([
                'msg' => 'Stock adjustment successfully processed.',
                'type' => 'success'
            ]);
        }

        
        
        return Redirect::back()->withFlash_message([
            'msg' => 'Sorry, we can\'t process your request right now. Please try again later.',
            'type' => 'warning'
        ]);
    }


    public function destroy()
    {   

        if( Request::ajax() ){
            $input = Input::all();
            $inventory = Inventory::findOrFail($input['id']);

            if( $inventory->delete() )  
                Log::create([
                    'user_id' => Auth::user()->id,
                    'action'  => "Deleted inventory with Lot# $inventory->lot_number",
                    'table' => 'inventories',
                    'branch_id' => session()->get('selected_branch')
                ]);
                session()->flash("flash_message", ["msg" => "A stock with Lot# $inventory->lot_number has been deleted.", "type" => "danger"]);
                sleep(1);
                return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later." ) );
    }

    public function get_lot_numbers(){
        if( Request::ajax() ){
            DB::enableQueryLog();
            $lot_numbers = DB::table('inventories')
                ->join('products', function ($join){           
                    $join->on('inventories.product_id', '=', 'products.id');
                })->whereRaw('inventories.branch_id= '.session()->get("selected_branch").' and products.deleted_at is null')
                ->select('products.name as product_name', 'inventories.id', 'inventories.product_id', 
                    'inventories.lot_number', 'inventories.expiration_date')->get();
                // pre(DB::getQueryLog());
            return $lot_numbers;
        }

        return json_encode( array(
            "error" => "Sorry, you don't have permission to do this action.",
            "status" => 403
        ) );
    }
}
