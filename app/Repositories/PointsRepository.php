<?php  

namespace ECEPharmacyTree\Repositories;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Carbon\Carbon; 

use ECEPharmacyTree\PointsActivityLog;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Doctor;
use ECEPharmacyTree\ReferralCommissionActivityLog;
use ECEPharmacyTree\ProductGroup;

class PointsRepository {

    function compute_basket_points($input){
        $settings = get_recent_settings();
        $patient = Patient::findOrFail($input['patient_id']);
        $basket_items = $patient->basket()->get();
        $total_points_earned = 0;

        foreach($basket_items as $basket_item) {
            $amount = (double)($basket_item->products()->first()->price * $basket_item->quantity);
           
            if($basket_item->products()->first()->product_group_id > 0){
                $group = ProductGroup::find($basket_item->product->product_group_id);
                $points_per_one_hundred = (double)$group->points;
            } else {
                $points_per_one_hundred = (double)$settings->points;
            }
            $points_earned_for_this_basket_item = $amount * ( $points_per_one_hundred/100);
            $total_points_earned += $points_earned_for_this_basket_item;
        }

        return $total_points_earned;

    }

    function process_points($referral_id) {
        $user = Patient::where('referral_id', '=', $referral_id)->first();
        $settings = get_recent_settings();


        if( empty($user) ) 
            $user = Doctor::where('referral_id', '=', $referral_id)->first();

        if( empty($user) )
            return json_encode(array('msg' => "Sorry, but we can't find a user with Referral ID of $referral_id.", 'status' => 500));

            // dd($user);

        $uplines = get_uplines($referral_id);

        $orders = $user->orders;
            // pre($uplines);
        $billings = array();
        foreach ($orders as $order) {
            $billings[] = $order->billing;

        }


        $points = 0;
        $limit = $settings->level_limit;
        $billings_has_uncomputed_points = false;

        $notes = "";

        // krsort($uplines);

        //pre("# of billings: ".count($billings));
    foreach ($billings as $billing) {
        if( $billing->payment_status == 'paid' && $billing->points_computation_status == 0 ){
            $billings_has_uncomputed_points = true;
                //pre("uncomputed billing.");
                // points computation
                // $points_earned = compute_points($billing->gross_total);
            $sales_amount = $billing->gross_total;
            $points_per_order_detail = 0;

            foreach ($billing->order->order_details as $order_detail) {
                $points_per_one_hundred = (double)$settings->points;
                $amount = (double)($order_detail->price * $order_detail->quantity);
                $points_earned_for_this_order_detail = $amount * ( $points_per_one_hundred/100);

                if( $order_detail->product->product_group_id > 0 ){
                    $group = ProductGroup::find($order_detail->product->product_group_id);
                    $points_per_one_hundred = (double)$group->points;
                        // $amount = (double)($order_detail->price * $order_detail->quantity);
                    $points_earned_for_this_order_detail = $amount * ( $points_per_one_hundred/100);

                    $points_per_order_detail+= $points_earned_for_this_order_detail;

                    $sales_amount -= $amount;


                }

                $notes.= "Order#$order->id: $points_earned_for_this_order_detail ".str_auto_plural("point", $points_earned_for_this_order_detail)
                    ." earned from ".$order_detail->product->name." ("
                    .peso()."$order_detail->price x $order_detail->quantity). \n Note: You earn $points_per_one_hundred "
                    .str_auto_plural("point", $points_per_one_hundred)." for every ".peso()."100.00 purchase of this product. \n\n";

            }

                //pre($notes);
                $points_per_one_hundred = (double)$settings->points;
                $points_earned = $points_per_order_detail + ( $sales_amount * ( $points_per_one_hundred/100) );



                // add points   
                $user_old_points = (double)$user->points; 
                $user->points = $points_earned + $user_old_points;

                // points_discount deduction
                if( $user->points >=  $billing->points_discount ){
                    $user->points = $user->points - $billing->points_discount;
                }

                if( $user->save() ){
                    $ref_com_log = new ReferralCommissionActivityLog;
                    $ref_com_log->billing_id = $billing->id;
                    $ref_com_log->to_upline_id = $user->id;   // means self, no upline
                    $ref_com_log->to_upline_type = isset($user->prc_no) ? 'doctor' : 'patient';
                    $ref_com_log->referral_level = 0; // if 0, it means - to the user itself, it's not a referral points
                    $ref_com_log->points_earned = $points_earned;
                    $ref_com_log->referral_points_earned = 0;
                    $ref_com_log->old_upline_points = $user_old_points;
                    $ref_com_log->new_upline_points = $user->points;
                    $ref_com_log->notes = $notes;
                    $ref_com_log->save();

                    //log for used points
                    if($billing->points_discount > 0){
                        $points_activity_log = new PointsActivityLog;
                        $points_activity_log->user_type = isset($user->prc_no) ? 'doctor' : 'patient';
                        $points_activity_log->user_id = $user->id;
                        $points_activity_log->points_used = $billing->points_discount;
                        $points_activity_log->notes = "You used ".$billing->points_discount." for order #".$order->id;
                        $points_activity_log->save();
                    }

                }
                // dd($uplines);
                $counter = 0; // note that 0 is the primary upline
                // pre("limit: $limit uplines: ".count($uplines));
                $difference = 0;

                if( $limit > count($uplines) ){         
                    $difference = $limit - count($uplines);
                }
                $limit -= $difference;
                $new_uplines = [];

                // remove uplines beyond referral limit
                for ($i=0; $i < $limit; $i++) { 
                    if( $i < $limit ){
                        $new_uplines[] = $uplines[$i];
                    }
                }

                krsort($new_uplines);

                foreach ($new_uplines as $upline) {
                    // $counter++;
                    if( $limit > 0 ){

                        $variation = $settings->commission_variation/100;
                        for ($i=1; $i < $limit; $i++) { 
                            $variation *= $settings->commission_variation/100;
                        }

                        $order_date = Carbon::parse($billing->order->created_at);

                        $referral_points_earned = $points_earned * $variation;
                        $old_upline_points = $upline->points;
                        $upline->points = $old_upline_points + $referral_points_earned;
                        $notes = "You earned ".($variation * 100)."% of $points_earned ".str_auto_plural('point', $points_earned).
                        "earned by $user->fname $user->lname's last order on {$order_date->formatLocalized('%A %d %B %Y')}";
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
                            $ref_com_log->notes = $notes;
                            $ref_com_log->save();
                        }

                        // pre("$upline->fname $upline->lname => gross_total: $billing->gross_total points_earned: $points_earned variation: $variation referral_commission: ".$referral_points_earned);
                        $variation = 0; 
                    }
                    $limit--;
                }

                $billing->points_computation_status = 1;
                if( $billing->save() )
                    return json_encode(array('msg' => 'Points and Referral Commission has been updated.', 'points_earned' => $points_earned, 'status' => 200));

                return json_encode(array('msg' => 'Points and Referral Commission has not been fully updated.', 'status' => 500));
            }
        }    

        if( $billings_has_uncomputed_points === false )
            return json_encode(array('msg' => "Sorry, but the points from all purchases made by $user->fname $user->lname has aleady been redeemed.", 'status' => 500));
    }

}