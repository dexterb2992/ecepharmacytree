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

use ECEPharmacyTree\Repositories\InventoryRepository;

class InventoryController extends Controller
{
    protected $inventory;
    function __construct(InventoryRepository $inventory)
    {
        $this->inventory = $inventory;
    }

    public function index()
    {   
        $data = $this->inventory->index();

        return view('admin.inventories')->withInventories($data["inventories"])
            ->withTitle('Stocks Receiving')->withLogs($data["logs"])
            ->withOrders($data["orders"])
            ->withReason_codes($data["reason_codes"])
            ->withStock_returns($data["stock_returns"]);
    }

    public function show_all(){
        $data = $this->inventory->show_all();

        return view('admin.inventories')->withInventories($data["inventories"])
            ->withTitle('Stocks Receiving')->withLogs($data["logs"])
            ->withOrders($data["orders"])
            ->withReason_codes($data["reason_codes"])
            ->withStock_returns($data["stock_returns"]);
    }

    public function store(Request $request)
    {

        $input = Input::all();
        $response = $this->inventory->store($input);

        if( $response['status'] == 'success' )

            return Redirect::to( route('Inventory::index') )->withFlash_message([
                "type" => "info",
                "msg" => $response['msg']
            ]);

        return Redirect::to( route('Inventory::index') )
            ->withFlash_message([
                "type" => "danger",
                "msg" => $response['msg']
            ]);
    }

    public function show($id)
    {
        if( Request::ajax() ){
            return $this->inventory->show($id);
        }   
        return 'Whoops! It seems like you are lost. Let\'s go back to <a href="'.url('/').'">dashboard</a>.';
    }

    public function update()
    {
        $input = Input::all();
        $inventory = Inventory::findOrFail( $input["id"] );
        $inventory->product_id = $input["product_id"];

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
                })->whereRaw('inventories.branch_id= '.session()->get("selected_branch").' and products.deleted_at is null
                    and inventories.available_quantity > 0
                ')
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

    public function get_product_lot_numbers(){
        if( Request::ajax() ){
            $product_id = Input::get('product_id');
            DB::enableQueryLog();
            $lot_numbers = DB::table('inventories')
                ->join('products', function ($join){           
                    $join->on('inventories.product_id', '=', 'products.id');
                })->whereRaw("inventories.branch_id= ".session()->get("selected_branch")." 
                    and products.deleted_at is null and products.id='$product_id' and inventories.available_quantity > 0")
                ->select('products.name as product_name', 'inventories.id', 'inventories.product_id', 
                    'inventories.lot_number', 'inventories.expiration_date', 'inventories.available_quantity')->get();

            return json_encode( array("status" => 200, "data" => $lot_numbers) );
        }

        return json_encode( array(
            "error" => "Sorry, you don't have permission to do this action.",
            "status" => 403
        ) );
    }

    public function get_logs(){
        $logs = Log::where('table', 'inventories')
                ->where('branch_id', '=', session()->get('selected_branch'))
                ->orderBy('id', 'desc')->paginate(100);

        $tbl = '
            <div style="margin-bottom: 20px;">
                <a href="javascript:void(0);" class="refresh-inventory-logs">
                    <i class="fa fa-refresh"></i> Refresh
                </a>
            </div>
            <table class="table table-bordered table-hover" id="tbl_inventory_logs">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
        ';
        
        $tbody = '';
        foreach($logs->items() as $log){
            $date_added = $log->created_at;
            $tbody .= "
                <tr>
                    <td>$log->id</td>
                    <td>".get_person_fullname($log->user)."</td>
                    <td>$log->action</td>
                    <td>
                        <span class='label label-primary' data-toggle='tooltip' data-original-title='".$date_added->toDayDateTimeString()."'>
                            <i class='fa-clock-o fa'></i> 
                            $date_added
                        </span>
                    </td>
                </tr>";
        }
        
        $pagination = render_pagination($logs);
        $tbl.= "$tbody</tbody></table>$pagination";
        return $tbl;
    }

    public function get_items(){
        $inventories = Inventory::where('available_quantity', '>', 0)
            ->where('branch_id', session()->get('selected_branch'))->paginate(100);
        $tbl = '<table class="table table-bordered table-hover datatable" id="tbl_inventory_items">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lot #</th>
                    <th>SKU</th>
                    <th>Product name</th>
                    <th>Quantity Received</th>
                    <th>Available Quantity</th>
                    <th>Stock Expiration</th>
                    <th>Date Added</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
        $tbody = '';
                foreach($inventories->items() as $inventory){
                    if(!is_null($inventory->product)){
                        $check_stock_availability = check_stock_availability($inventory->product);
                        $total = $inventory->available_quantity * $inventory->product->qty_per_packing; 
                        $date_added = $inventory->created_at;
                        $expiration = Carbon::parse($inventory->expiration_date);
                        $availability = '';
                        if($check_stock_availability == 'out_of_stock'){
                            $availability = '<i class="fa-warning fa" style="color:#dd4b39;" data-toggle="tooltip" data-original-title="Out of Stock"></i>';
                        }else if($check_stock_availability == 'critical'){
                            $availability = '<i class="fa-warning fa" style="color:#f0ad4e;" data-toggle="tooltip" data-original-title="Critical Stock"></i>';
                        }
                        

                        $tbody.= '<tr data-pid="'.$inventory->product_id.'" data-id="'. $inventory->id.'">
                            <td>'.$inventory->id.'</td>
                            <td>
                                '.$inventory->lot_number.'
                            </td>
                            <td>
                                '.$availability.'<span>'.$inventory->product->sku.'</span>
                            </td>
                            <td>
                                <a href="'.url('search/products?q='.$inventory->product->name).'" target="_blank" class="show-product-info" data-id="'.$inventory->product->id.'">
                                    '.$inventory->product->name.'
                                </a>
                            </td>
                            <td>
                                '.$inventory->quantity.' '.str_auto_plural($inventory->product->packing, $inventory->quantity).'
                            </td>
                            <td>
                                <b>'.$inventory->available_quantity." ".str_auto_plural($inventory->product->packing, $inventory->available_quantity)."</b> "
                                    ."(".$total." ".str_auto_plural($inventory->product->unit, $total).')
                            
                            </td>
                            <td>
                                <span class="label label-success" data-toggle="tooltip" data-original-title="'.$expiration->formatLocalized('%A %d %B %Y').'">
                                    <i class="fa-clock-o fa"></i> 
                                    '.$expiration->diffForHumans().'
                                </span>
                            </td>
                            <td>
                                <span class="label label-primary" data-toggle="tooltip" data-original-title="'.$date_added->toDayDateTimeString().'">
                                    <i class="fa-clock-o fa"></i> 
                                    '.$date_added->diffForHumans().'
                                </span>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <span class="btn btn-danger btn-xs action-icon remove-product pull-right" data-action="remove" data-title="inventory" data-urlmain="/inventory/"
                                         data-id="'.$inventory->id.'" title="Remove">
                                         <i class="fa fa-trash-o"></i>
                                    </span>
                                    <span class="btn-warning btn btn-xs pull-right btn-adjustment" data-id="'.$inventory->id.'" data-toggle="modal" data-target="#modal-add-adjustments">
                                        <i class="glyphicon glyphicon-list-alt" data-toggle="tooltip" data-original-title="Stock Adjustment"></i>
                                    </span>
                                    <span class="btn btn-info btn-xs add-edit-btn pull-right" data-action="edit" data-modal-target="#modal-add-edit-inventory" 
                                        data-title="inventory" data-target="#form_edit_inventory" data-id="'.$inventory->id.'" title="Edit" data-toggle="tooltip" data-original-title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>';
                    }
                }
        $pagination = render_pagination($inventories);
        $tbl.= "$tbody</tbody></table>$pagination";
        return $tbl;
    }
}
