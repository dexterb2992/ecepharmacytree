<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Carbon\Carbon; 

use ECEPharmacyTree\PointsActivityLog;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Doctor;
use ECEPharmacyTree\ReferralCommissionActivityLog;
use ECEPharmacyTree\ProductGroup;
use ECEPharmacyTree\Repositories\PointsRepository;


class PointsController extends Controller
{
    protected $points;

    function __construct(PointsRepository $points)
    {
        $this->points = $points;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($referral_id)
    {
        $this->points->process_points($referral_id);
    }
}
