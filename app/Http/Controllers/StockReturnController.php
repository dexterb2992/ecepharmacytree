<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\StockReturnCode;
use ECEPharmacyTree\StockReturn;

class StockReturnController extends Controller
{

    public function index(){
        //
    }

    public function create(){
        //
    }


    public function store(Request $request){
        //
    }

    public function show($id){
        //
    }

    public function stock_return_codes(){
        $codes = StockReturnCode::all();
        return $codes;
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
}
