<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;
use ECEPharmacyTree\Setting;
use Redirect;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = Setting::first();
        return view("admin.settings")->withTitle("Settings")->withSettings($settings);

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
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update()
    {
        $input = Input::all();
        $setting = Setting::first();
        $setting->points = $input["points"];
        $setting->points_to_peso = $input["points_to_peso"];
        $setting->level_limit = $input["level_limit"];
        $setting->referral_commission = $input["referral_commission"];
        $setting->commission_variation = $input["commission_variation"];
        $setting->delivery_charge = $input["delivery_charge"];
        $setting->delivery_minimum = $input["delivery_minimum"];
        $setting->safety_stock = $input["safety_stock"];
        $setting->critical_stock = $input["critical_stock"];
        if( $setting->save() )
            return Redirect::to( route('Settings::index') )->withFlash_message([
                "msg" => "Your changes have been successfully saved!", "type" => "success"]);
        return Redirect::to( route('Settings::index') )
            ->withFlash_message(["msg" => "Sorry, we can't process your request right now. Please try again later.", "warning"]);
    }
}
