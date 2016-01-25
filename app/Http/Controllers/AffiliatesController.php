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
            $log->notes = str_replace("You", get_person_fullname($earner), $log->notes);
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
