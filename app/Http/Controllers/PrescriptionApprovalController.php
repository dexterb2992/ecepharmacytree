<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Basket;
use Input;

class PrescriptionApprovalController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
         $prescription_approvals = Basket::where('prescription_id', '!=', 0)->get();
        //  echo "<pre>";
        // var_dump($prescription_approvals);
        // echo "</pre>";
        return view('admin.prescription-approvals')->withPrescriptionApprovals($prescription_approvals);
    }

    function disapprove(){
         $id = Input::get('id');
        $prescription_approval = Basket::findOrFail($id);
        $prescription_approval->is_approved = 2;

        if($prescription_approval->save())
            return json_encode( array("status" => "success") );
         
         return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );       
    }

    function approve(){
         $id = Input::get('id');
        $prescription_approval = Basket::findOrFail($id);
        $prescription_approval->is_approved = 1;

        if($prescription_approval->save())
            return json_encode( array("status" => "success") );
         
         return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );       
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
        //
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
