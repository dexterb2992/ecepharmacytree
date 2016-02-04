<?php

namespace ECEPharmacyTree\Http\Controllers;

use Illuminate\Http\Request;

use ECEPharmacyTree\Http\Requests;
use ECEPharmacyTree\Http\Controllers\Controller;
use Input;
use ECEPharmacyTree\SeniorCitizen;

class SeniorCitizenController extends Controller
{
 public function store(Request $request)
 {
    $input = Input::all();
    $id = $input['patient_id'];
    $response = array();
    $allowed =  array('jpeg','png' ,'jpg');
    $target_path = "uploads/";
    $new_name = "user_".$id; 
    $target_path .= $new_name . '/';

    if($request->hasFile('image')){
        $file = $request->file('image');
        $filename = generate_random_string().'.'.$ext;
        $response['target_path'] = $target_path;
        $response['filename'] = $filename;
        $response['extension'] = $file->getClientOriginalExtension();
        $response['size'] = $file->getClientSize();
        $file->move($target_path, $filename);

        // if ($file->getClientSize() > 5242880) {
        //     $response['error'] = true;
        //     $response['message'] = "Sorry, your file is too large. File limit - 5 mb";
        // } 

        // if(!in_array($file->getClientOriginalExtension(), $allowed)){
        //     $response['error'] = true;
        //     $response['message'] = 'Sorry, only JPG, JPEG, PNG files are allowed.'; 
        // }
        
        // $ext = $file->getClientOriginalExtension();
        // $filename = generate_random_string().'.'.$ext;

        // if($file->move($target_path, $fileName)){
        //     $senior_citizen = new SeniorCitizen;
        //     $senior_citizen->patient_id = $id;
        //     // $senior_citizen->senior_citizen_id_number = $input['senior_citizen_id_number'];
        //     $senior_citizen->id_picture = $filename;

        //     if($senior_citizen->save()){
        //         $response['message'] = 'File uploaded successfully!';
        //         $response['error'] = false;
        //         $response['file_path'] = $target_path . $filename;
        //         $response['file_url'] = $target_path;
        //         $response['server_id'] = $id;
        //         $response['file_name'] = $filename;
        //     }

        // } else {
        //    $response['error'] = true;
        //     $response['message'] = 'Sorry, we cannot upload the file. Please try different image';
        // }

        
    } else {
        $response['error'] = true;
        $response['message'] = 'No File Found';
        return json_encode($response);
    }
}

function generate_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

}
