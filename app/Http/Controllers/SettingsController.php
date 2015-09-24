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
    public function update()
    {
        $input = Input::all();
        $setting = Setting::first();
        $setting->points = $input["points"];
        $setting->level_limit = $input["level_limit"];
        $setting->referral_commission = $input["referral_commission"];
        $setting->commission_variation = $input["commission_variation"];
        $setting->delivery_charge = $input["delivery_charge"];
        $setting->safety_stock = $input["safety_stock"];
        $setting->critical_stock = $input["critical_stock"];
        if( $setting->save() )
            return Redirect::to( route('Settings::index') )->withFlash_message("Your changes have been successfully saved!");
        return Redirect::to( route('Settings::index') )
            ->withFlash_message("Sorry, we can't process your request right now. Please try again later.");
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
