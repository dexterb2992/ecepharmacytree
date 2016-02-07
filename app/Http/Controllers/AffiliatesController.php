<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Doctor;
use ECEPharmacyTree\ReferralCommissionActivityLog;

class AffiliatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $logs = ReferralCommissionActivityLog::orderBy('id', 'DESC')->get();

        foreach ($logs as $log) {
            $earner = get_earner_from_referral_points_logs($log);
            // $log->notes = str_replace("Order#", "<br/>Order#", str_replace("You", "<b>".get_person_fullname($earner)."</b>", $log->notes));
            $log->earner = get_person_fullname($earner);
            $log->notes = str_replace("Order#", "<br/>Order#", $log->notes);
            $phpdate = strtotime( $log->created_at );
            $log->date =  date( 'jS \o\f F, Y g:i a', $phpdate );
        }

        if( empty($doctors) )
            $doctors = [];
        if( empty($patients) )
            $patients = [];
        return view('admin.affiliates')->withPatients($patients)->withDoctors($doctors)->withLogs($logs)
            ->withTitle("Affiliates");
    }
}
