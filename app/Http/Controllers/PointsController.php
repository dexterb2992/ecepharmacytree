<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\PointsActivityLog;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Doctor;
use ECEPharmacyTree\ReferralCommissionActivityLog;

class PointsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($referral_id)
    {
        $user = Patient::where('referral_id', '=', $referral_id)->first();
        $settings = get_recent_settings();
        

        if( empty($user) ) 
            $user = Doctor::where('referral_id', '=', $referral_id)->first();
        
        if( empty($user) )
            return json_encode(array('msg' => "Sorry, but we can't find a user with Referral ID of $referral_id.", 'status' => 500));

        // dd($user);

        $uplines = get_uplines($referral_id);
        
        $orders = $user->orders;
        // dd(orders);
        $billings = array();
        foreach ($orders as $order) {
            // $billing = $order->billing->where('points_computation_status', '=', 0)->get();
            $billings[] = $order->billing;

        }

        
        $points = 0;
        $limit = $settings->level_limit;
        $billings_has_uncomputed_points = false;

        krsort($uplines);

        foreach ($billings as $billing) {
            if( $billing->payment_status == 'paid' && $billing->points_computation_status == 0 ){
                $billings_has_uncomputed_points = true;

                $points_earned = compute_points($billing->gross_total);

                // add points   
                $user_old_points = $user->points; 
                $user->points += $points_earned;

                if( $user->save() ){
                    // log for points changes
                    $ref_com_log = new ReferralCommissionActivityLog;
                    $ref_com_log->billing_id = $billing->id;
                    $ref_com_log->to_upline_id = $user->id;   // means self, no upline
                    $ref_com_log->to_upline_type = isset($user->prc_no) ? 'doctor' : 'patient';
                    $ref_com_log->referral_level = 0; // if 0, it means to the user itself, it's not a referral points
                    $ref_com_log->points_earned = $points_earned;
                    $ref_com_log->referral_points_earned = 0;
                    $ref_com_log->old_upline_points = $user_old_points;
                    $ref_com_log->new_upline_points = $user->points;
                    $ref_com_log->save();
                }
                // dd($uplines);
                $counter = 0; // note that 0 is the primary upline

                foreach ($uplines as $upline) {
                    // $counter++;
                    if( $limit > 0 ){
                        
                        $variation = $settings->commission_variation/100;
                        for ($i=1; $i < $limit; $i++) { 
                            $variation *= $settings->commission_variation/100;
                        }
                        $referral_points_earned = $points_earned * $variation;
                        $old_upline_points = $upline->points;
                        $upline->points = $old_upline_points + $referral_points_earned;
                        if( $upline->save() ){
                            // save a referral commission changes log
                            $ref_com_log = new ReferralCommissionActivityLog;
                            $ref_com_log->billing_id = $billing->id;
                            $ref_com_log->to_upline_id = $upline->id;
                            $ref_com_log->to_upline_type = isset($upline->prc_no) ? 'doctor' : 'patient';
                            $ref_com_log->referral_level = $limit;
                            $ref_com_log->points_earned = $points_earned;
                            $ref_com_log->referral_points_earned = $referral_points_earned;
                            $ref_com_log->old_upline_points = $old_upline_points;
                            $ref_com_log->new_upline_points = $upline->points;
                            $ref_com_log->save();
                        }

                        //pre("gross_total: $billing->gross_total points_earned: $points_earned variation: $variation referral_commission: ".$referral_points_earned);
                        $variation = 0; 
                    }
                    $limit--;
                }
                $billing->points_computation_status = 1;
                if( $billing->save() )
                    return json_encode(array('msg' => 'Points and Referral Commission has been updated.', 'status' => 200));
                
                return json_encode(array('msg' => 'Points and Referral Commission has not been fully updated.', 'status' => 500));
            }
        }    

        if( $billings_has_uncomputed_points === false )
            return json_encode(array('msg' => "Sorry, but the points from all purchases made by $user->fname $user->lname has aleady been redeemed.", 'status' => 500));
    }
}
