<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Billing;
use Input;
use ECEPharmacyTree\Repositories\PointsRepository;


class BillingController extends Controller
{

    protected $points;

    function __construct(PointsRepository $points)
    {
        $this->points = $points;
    }


     /**
     * Mark Order as Paid
     *
     * @param  int  $id
     * @return Response
     */
    function mark_order_as_paid(){
        $input = Input::all();
        $billing = Billing::where('order_id', $input['order_id'])->first();
        $billing->payment_status = "paid";
        $billing->or_txt_number = $input['or_txn_number'];

        if( $billing->save() ){
            $message = $this->points->process_points($input['referral_id']);

           return redirect()->route('get_order', $input['order_id'])->withFlash_message([
                'type' => 'info', 'msg' => $message->msg ]);
       }

       return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
   }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
