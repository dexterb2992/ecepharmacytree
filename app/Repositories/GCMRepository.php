<?php

namespace ECEPharmacyTree\Repositories;

class GCMRepository {

	function sendGoogleCloudMessage( $data, $id ){
        // Insert real GCM API key from Google APIs Console
	    // https://code.google.com/apis/console/        
		//$apiKey = 'AIzaSyBBZnU4T90rSnbSnqFdV_IyFDEDbATUZz4';
			$apiKey='AAAA5TinH-g:APA91bHvreYU25gdAJze5BAiS_TiGv4dc6Of9QrXB8KsOXxJPv82chhmeMUyy2qSU7GK2RNWU-5-IDCokSRIGiIPZ8ZmCRSWJiKX9-BkCiaXRgPgKrp_G18FnDNtdt2_i8yR13DA0B9um2ckmvDeX_6Cm2VYuh2_9w';
	    // Define URL to GCM endpoint
			//$url = 'https://gcm-http.googleapis.com/gcm/send';
			$url = 'https://fcm.googleapis.com/fcm/send';

	    // Set GCM post variables (device IDs and push payload)     
			$post = array(
				'to'  => $id,
				'data'  => $data,
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

}