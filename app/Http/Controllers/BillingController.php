<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Billing;
use Input;
use ECEPharmacyTree\Repositories\PointsRepository;
use ECEPharmacyTree\Repositories\GCMRepository;


class BillingController extends Controller
{

    protected $points;
    protected $gcm;

    function __construct(PointsRepository $points, GCMRepository $gcm)
    {
        $this->points = $points;
        $this->gcm = $gcm;
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
        $billing->or_txn_number = $input['or_txn_number'];
        

        if( $billing->save() ){

            $message = json_decode($this->points->process_points($input['referral_id']));

            if($message->status == 500)
                return redirect()->route('get_order', $input['order_id'])->withFlash_message(['type' => 'danger', 'msg' => $message->msg ]);

            if($message->status == 200){

                $order = $billing->order()->first();
                $patient = $order->patient()->first();

                // $multilined_notif = array(1 => 'Congratulations '.get_person_fullname($patient).' ! ', 2 => 'You just acquired '.$message->points_earned.' points.', 3 => 'Thank you for your order. Ref#'.$order->id.'.');

                $data = array('title' => 'Pharmacy Tree', 'intent' => 'ReferralFragment', 
                    'order_id' => $order->id, 'text' => 'You acquired '.$message->points_earned.' from your payment. Order #'.$order_id);

                $this->gcm->sendGoogleCloudMessage($data, $patient->regId);

                return redirect()->route('get_order', $input['order_id'])->withFlash_message(['type' => 'success', 'msg' => $message->msg ]);
            }
        }
        
        return redirect()->route('get_order', $input['order_id'])->withFlash_message(['type' => 'danger', 'msg' => 'Sorry. Unable to mark payment.' ]);

    }
}
