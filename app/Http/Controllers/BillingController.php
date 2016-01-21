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
            $message = json_decode($this->points->process_points($input['referral_id']));

            if($message->status == 500)
                return redirect()->route('get_order', $input['order_id'])->withFlash_message(['type' => 'danger', 'msg' => $message->msg ]);

            if($message->status == 200)
                return redirect()->route('get_order', $input['order_id'])->withFlash_message(['type' => 'success', 'msg' => $message->msg ]);
        }
        
        return redirect()->route('get_order', $input['order_id'])->withFlash_message(['type' => 'danger', 'msg' => 'Sorry. Unable to mark payment.' ]);

    }
}
