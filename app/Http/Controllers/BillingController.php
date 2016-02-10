<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Billing;
use Input;
use ECEPharmacyTree\Repositories\PointsRepository;
use ECEPharmacyTree\Repositories\GCMRepository;
use Illuminate\Mail\Mailer;

class BillingController extends Controller
{

    protected $points;
    protected $gcm;
    protected $mailer;

    function __construct(PointsRepository $points, GCMRepository $gcm, Mailer $mailer)
    {
        $this->points = $points;
        $this->gcm = $gcm;
        $this->mailer = $mailer;
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
                    'order_id' => $order->id, 'text' => 'You acquired '.$message->points_earned.' from your payment. Order #'.$order->id);

                $this->emailtestingservice($patient->email_address, $order->id, $message->points_earned);
                $this->gcm->sendGoogleCloudMessage($data, $patient->regId);

                return redirect()->route('get_order', $input['order_id'])->withFlash_message(['type' => 'success', 'msg' => $message->msg ]);
            }
        }
        
        return redirect()->route('get_order', $input['order_id'])->withFlash_message(['type' => 'danger', 'msg' => 'Sorry. Unable to mark payment.' ]);

    }

    function emailtestingservice($email, $order_id, $points){   
        $res = $this->mailer->send( 'emails.points_received_email', 
            compact('email', 'order_id', 'points'), function ($m) use ($email) {
                $m->subject('You received points');
                $m->to($email);
            });
    }
}
