<?php
namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Intput;
use Redirect;
use Input;
use Carbon\Carbon;
use Auth;
use Symfony\Component\HttpFoundation\Session\Session;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Inventory;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\Log;
use ECEPharmacyTree\Order;
use ECEPharmacyTree\StockReturnCode;
use ECEPharmacyTree\StockReturn;
use ECEPharmacyTree\InventoryAdjustment;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {   
        $inventories = Inventory::where('available_quantity', '>', '0')
            ->where('branch_id', session()->get('selected_branch'))->get();
        $products = Product::all();
        $logs = Log::where('table', 'inventories')->orderBy('id', 'desc')->get();
        $reason_codes = StockReturnCode::all();
        $orders = Order::all();
        $stock_returns = StockReturn::all();
        foreach ($stock_returns as $stock_return) {
            $stock_return->load('order');
            $stock_return->load('inventory');
            $stock_return->order->load('patient');
        }

        return view('admin.inventories')->withInventories($inventories)
            ->withProducts($products)->withTitle('Stocks Receiving')->withLogs($logs)
            ->withOrders($orders)
            ->withReason_codes($reason_codes)
            ->withStock_returns($stock_returns);
    }

    public function show_all(){
        $inventories = Inventory::where('branch_id', session()->get('selected_branch'))->get();
        $products = Product::all();
        $orders = Order::all();
        $logs = Log::where('table', 'inventories')->orderBy('id', 'desc')->get();
        $reason_codes = StockReturnCode::all();
        $stock_returns = StockReturn::all();
        foreach ($stock_returns as $stock_return) {
            $stock_return->load('order');
            $stock_return->load('inventory');
            $stock_return->order->load('patient');
        }

        return view('admin.inventories')->withInventories($inventories)
            ->withProducts($products)->withTitle('Stocks Receiving')->withLogs($logs)
            ->withOrders($orders)
            ->withReason_codes($reason_codes)
            ->withStock_returns($stock_returns);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
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
                                target="blank">Lot #'.$inventory->lot_number.'</a>',
                'table' => 'inventories'
            ]);
            return Redirect::to( route('Inventory::index') );
        }
        return Redirect::to( route('Inventory::index') )
            ->withFlash_message("Sorry, but we can't process your request right now. Please try again later.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if( Request::ajax() ){
            $inventory = Inventory::findOrFail($id);
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $inventory->created_at)
                ->copy()->tz(Auth::user()->timezone)
                ->format('g:ia \o\n l jS F Y');
            $inventory->date_added = $date;
            $inventory->load('product');
            return $inventory->toJson();
        }   
        return 'Whoops! It seems like you are lost. Let\'s go back to <a href="'.url('/').'">dashboard</a>.';
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
        $inventory = Inventory::findOrFail( $input["id"] );
        $inventory->product_id = $input["product_id"];
        
        $inventory->quantity = $input["quantity"]; // q1 = 4, av = 2, update: q2 = 5;
                                                   // def = q2-q1 = 1, av += def
        $def_q = $input["quantity"] - $inventory->quantity;
        $old_av = $inventory->available_quantity;
        
        $new_av = $old_av + $def_q;

        $inventory->available_quantity = $new_av >= 1 ? $new_av : 0;

        $inventory->lot_number = $input['lot_number'];

        $inventory->expiration_date = $input["expiration_date"];
        if( $inventory->save() ){
            Log::create([
                'user_id' => Auth::user()->id,
                'action'  => 'Updated an inventory information with <a href="'.route('Inventory::index').'?q='.$inventory->lot_number.'" 
                                target="blank">Lot #'.$inventory->lot_number.'</a>',
                'table' => 'inventories'
            ]);
            return Redirect::to( route('Inventory::index') );
        }
        return Redirect::to( route('Inventory::index') )
            ->withFlash_message("Sorry, but we can't process your request right now. Please try again later.");
    
    }

    public function add_adjustments(){
        $input = Input::all();
        $inventory = Inventory::findOrFail( $input['id'] );
        $old_qty = $inventory->quantity;
        
        $def_q = $input["new_quantity"] - $inventory->quantity;
        $old_av = $inventory->available_quantity;
        
        $new_av = $old_av + $def_q;

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
                'action'  => 'Adjusted an inventory information with <a href="'.route('Inventory::index').'?q='.$inventory->lot_number.'" 
                                target="blank">Lot #'.$inventory->lot_number.'</a>'
                                .' and change quantity from '.$old_qty.' to '.$input['new_quantity']
                                .'. <br/><code>Reason:</code> '.$input['reason'],
                'table' => 'inventories'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {   

        if( Request::ajax() ){
            $input = Input::all();
            $inventory = Inventory::findOrFail($input['id']);

            if( $inventory->delete() )  
                Log::create([
                    'user_id' => Auth::user()->id,
                    'action'  => 'Deleted inventory with Lot #'.$inventory->lot_number,
                    'table' => 'inventories'
                ]);
                return json_encode( array("status" => "success") );
        }
        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later." ) );
    }
}
