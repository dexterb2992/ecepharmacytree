<?php

namespace ECEPharmacyTree\Http\Controllers;

use Request;
use Input;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use ECEPharmacyTree\Patient;
use ECEPharmacyTree\Region;
use ECEPharmacyTree\Province;
use ECEPharmacyTree\Municipality;
use ECEPharmacyTree\Barangay;
use Response;


class PatientController extends Controller
{

    function save_user_token(){
        $input  = Input::all();
        $response = array();

        $patient =  Patient::findOrFail($input['user_id']);
        $patient->regId = $input['token'];
        
        if($patient->save())
            $response['success'] = true;
        else
            $response['success'] = false;

        $data = array( 'message' => 'Putang ina mo!' );

        $ids = array( 'fWs0-Oacg0g:APA91bFYMy9zWce6heS54Eb6GTEwmoy_ruOaYEDiZMvEbY6ucuncSg2E--mz_QTBLR4SSOnNiPIEQzgVGh9XQafOWXBpFaMlZKZsttRZCHsa6g8_WP_EBXbzmBOYnB8a4fnRcS1JtJVo' );

        $this->sendGoogleCloudMessage($data, $ids);

        return Response::json($response);
    }

    function sendGoogleCloudMessage( $data, $ids ){
        // Insert real GCM API key from Google APIs Console
    // https://code.google.com/apis/console/        
        $apiKey = 'AIzaSyBBZnU4T90rSnbSnqFdV_IyFDEDbATUZz4';

    // Define URL to GCM endpoint
        $url = 'https://gcm-http.googleapis.com/gcm/send';

    // Set GCM post variables (device IDs and push payload)     
        $post = array(
            'registration_ids'  => $ids,
            'data'              => $data,
            );

    // Set CURL request headers (authentication and type)       
        $headers = array( 
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
            );

    // Initialize curl handle       
        $ch = curl_init();

    // Set URL to GCM endpoint      
        curl_setopt( $ch, CURLOPT_URL, $url );

    // Set request method to POST       
        curl_setopt( $ch, CURLOPT_POST, true );

    // Set our custom headers       
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

    // Get the response back as string instead of printing it       
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    // Set JSON post data
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $post ) );

    // Actually send the push   
        $result = curl_exec( $ch );

    // Error handling
        if ( curl_errno( $ch ) )
        {
            echo 'GCM error: ' . curl_error( $ch );
        }

    // Close curl handle
        curl_close( $ch );

    // Debug GCM response       
        echo $result;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $members = Patient::withTrashed()->get();
        $regions = Region::all();

        return view('admin.members')->withMembers($members)->withRegions($regions);
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
        $member = Patient::findOrFail($id);
        if( isset( $member->id ) ){
            $member->full_address = $member->full_address();
            return $member->toJson();
        }
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $id = Input::get('id');
        $member = Patient::findOrFail($id);

        if($member->delete())
            return json_encode( array("status" => "success") );

        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }

    public function unblock()
    {
        $id = Input::get('id');
        $member = Patient::withTrashed()->findOrFail($id);

        if($member->restore())
            return json_encode( array("status" => "success") );

        return json_encode( array("status" => "failed", "msg" => "Sorry, we can't process your request right now. Please try again later.") );
    }
}
