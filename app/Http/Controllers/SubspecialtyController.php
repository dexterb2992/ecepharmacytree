<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Redirect;
use Input;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;

use ECEPharmacyTree\Specialty;
use ECEPharmacyTree\Subspecialty;

class SubspecialtyController extends Controller
{
	

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$subspecialty = new Subspecialty;
		$subspecialty->name = Input::get('name');
		$subspecialty->specialty_id = Input::get('specialty_id');
		if( $subspecialty->save() )
			return Redirect::to( route('doctors') )->withFlash_message(_get_flash_message("success", "", "{$subspecialty->name} has been added to {$subspecialty->specialty->name} subspecialties."));
		return false;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$subspecialty = Subspecialty::find($id);
		return $subspecialty->toJson();
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
		$subspecialty = Subspecialty::find( Input::get('id') );
		$subspecialty->name = Input::get('name');
		$subspecialty->specialty_id = Input::get('specialty_id');
		if( $subspecialty->save() )
			return Redirect::to( route('doctors') )->withFlash_message([
				"msg" => "$subspecialty->name has been successfully updated.",
				"type" => "info"
			]);
		return Redirect::back()->withFlash_message( _get_flash_message("error", "update") );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		if( Subspecialty::destroy( Input::get('id') ) )
			session()->flash("flash_message", ["msg" => "A subspecialty has been deleted.", "type" => "danger"]);
			return json_encode( array("status" => "success") );

		session()->flash("flash_message", _get_flash_message("error", "delete"));
		return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
	}
}
