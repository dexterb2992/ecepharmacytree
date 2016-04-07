<?php  
namespace ECEPharmacyTree\Repositories;

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

class InventoryRepository {

    public function index(){
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

        return array(
            "inventories" => $inventories,
            "logs" => $logs,
            "orders" => $orders,
            "reason_codes" => $reason_codes,
            "stock_returns" => $stock_returns
        );
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

        return array(
            "inventories" => $inventories,
            "logs" => $logs,
            "orders" => $orders,
            "reason_codes" => $reason_codes,
            "stock_returns" => $stock_returns
        );
    }

    public function store($input){
        // Note: the quantity that will be saved will be 
        //  per product packing
        
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
                'action'  => "Added an inventory of ".generate_product_link($product->name)." ($inventory->available_quantity "
                            .str_auto_plural($product->packing, $inventory->available_quantity).") with 
                            <a href='".route('Inventory::index')."?q=$inventory->lot_number' target='blank'>
                            Lot# $inventory->lot_number</a>.",
                'table' => 'inventories',
                'branch_id' => session()->get('selected_branch')
            ]);

            return [
                "status" => "success",
                "msg" => "A new stock for $product->name with Lot# $inventory->lot_number has been added successfully."
            ];
        }

        return [
            "status" => "failed",
            "msg" => "Sorry, but we can't process your request right now. Please try again later."
        ];
    }

    public function show($id){
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

}