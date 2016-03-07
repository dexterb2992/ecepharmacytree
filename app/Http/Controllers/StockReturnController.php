<?php

namespace ECEPharmacyTree\Http\Controllers;
use Input; 
use Redirect;
use Auth;
use \stdClass;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\StockReturnCode;
use ECEPharmacyTree\StockReturn;
use ECEPharmacyTree\OrderLotNumber;
use ECEPharmacyTree\Order;
use ECEPharmacyTree\OrderDetail;
use ECEPharmacyTree\ProductStockReturn;
use ECEPharmacyTree\Inventory;
use ECEPharmacyTree\Product;
use ECEPharmacyTree\Log;

use ECEPharmacyTree\Repositories\StockReturnRepository;

class StockReturnController extends Controller
{
	private $stock_return;

	function __construct(StockReturnRepository $stock_return)
	{
		$this->stock_return = $stock_return;
	}

	public function store(){
		$input = Input::all();
		if( !isset($input["order_id"]) )
			return Redirect::back()->withInput()->withFlash_message([
				'msg' => 'Sorry, some things are missing when you submit your request. Please try again.',
				'type' => 'error'
			]);

		$response = $this->stock_return->store($input);

		if( $response['status'] == 'success' ){
			return redirect('inventory/all')->withFlash_message($response['flash_message']);
		}else{
			return Redirect::back()->withInput()->withFlash_message($response['flash_message']);
		}

	}


	public function stock_return_codes(){
		$codes = StockReturnCode::all();
		return $codes;
	}

	public function show_all_returned_products($id){

		return $this->stock_return->show_all_returned_products($id);
	}

	public function update_defective_stocks(){
		$input = Input::all();

		return $this->stock_return->update_defective_stocks($input);
		
	}


	public function replace(){
		$input = Input::all();

		$response = $this->stock_return->replace($input);
		return $response;

		return json_encode( array("status" => 500) );
	}
}
