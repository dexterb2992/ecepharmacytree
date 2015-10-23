<?php

ini_set('display_errors', 1);

require __DIR__ . '/libs/PayPal/autoload.php';
// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

use PayPal\Api\Payment;


$response["error"] = false;
$response["message"] = "Payment verified successfully";
$user_id;

            // require_once '../include/Config.php';

define('DEFAULT_CURRENCY', 'PHP');
            define('PAYPAL_CLIENT_ID', 'AcCQ4PKc6AtjThCsPPkGSH01nPPe7yJKB1oRT39hpgpUGrFkORy9gmuY5_OF4loXc45RaNrUq4h94PP1'); // Paypal client id
            define('PAYPAL_SECRET', 'EH7LOgghxkz-pebLoT1dXSuDo0GiyPI3s1kKaMkp7fKQ25ezZovq5PGQqwVfAAjUpPFcPrAZYA33DcTC'); // Paypal secret
            
            try {
                $paymentId = $_POST['paymentId'];
                $payment_client = json_decode($_POST['paymentClientJson'], true);
                $user_id = $_POST['user_id'];         
                $branch_server_id = $_POST['branch_server_id'];
                $recipient_name = $_POST['recipient_name'];
                $recipient_address = $_POST['recipient_address'];
                $recipient_contactNumber = $_POST['recipient_contactNumber'];
                $modeOfDelivery = $_POST['modeOfDelivery'];
                $payment_method = $_POST['payment_method'];
                $payment_status = "pending";
                $status = $_POST['status'];

                $apiContext = new \PayPal\Rest\ApiContext(
                    new \PayPal\Auth\OAuthTokenCredential(
                        'AcCQ4PKc6AtjThCsPPkGSH01nPPe7yJKB1oRT39hpgpUGrFkORy9gmuY5_OF4loXc45RaNrUq4h94PP1', // ClientID
                        'EH7LOgghxkz-pebLoT1dXSuDo0GiyPI3s1kKaMkp7fKQ25ezZovq5PGQqwVfAAjUpPFcPrAZYA33DcTC'      // ClientSecret
                        )
                    );
                
                // Gettin payment details by making call to paypal rest api
                $payment = Payment::get($paymentId, $apiContext);
                
                // Verifying the state approved
                if ($payment->getState() != 'approved') {
                    $response["error"] = true;
                    $response["message"] = "Payment has not been verified. Status is " . $payment->getState();
                    echoResponse(200, $response);
                    return;
                }
                // Amount on client side
                $amount_client = $payment_client["amount"];
                
                // Currency on client side
                $currency_client = $payment_client["currency_code"];
                
                // Paypal transactions
                $transaction = $payment->getTransactions()[0];
                // Amount on server side
                $amount_server = $transaction->getAmount()->getTotal();
                // Currency on server side
                $currency_server = $transaction->getAmount()->getCurrency();
                $sale_state = $transaction->getRelatedResources()[0]->getSale()->getState();

                //start of saving billing

                date_default_timezone_set('Asia/Manila');
                $server_timestamp             = date('Y-m-d H:i:s', time());

                $sql = "SELECT b.*, p.*, b.id as basketID, pr.price FRom patients as p inner join baskets as b on p.id = b.patient_id inner join products as pr on b.product_id = pr.id WHERE p.id = ".$user_id." and b.is_approved = 1" ;

                $result = mysql_query($sql) or returnError(mysql_error());
                $counter = 0;
                $totalAmount = 0;
                $order_saved = false;
                $billing_saved = false;
                $prescription_id = 0;

                if ($result != 0)
                    $db_result = mysql_num_rows($result);

                // check for empty result
                if ($db_result > 0) {
                    while ($row = mysql_fetch_assoc($result)) {

                        foreach ($row as $key => $value) {
                            $quantity = $row['quantity'];
                            $price = $row['price'];
                            $product_id = $row['product_id'];
                            $quantity = $row['quantity'];              
                            $prescription_id = $row['prescription_id'];  
                        }

                        $totalAmount += $quantity * $price;


                        if($counter == 0 ){

                            $sql_orders_save = "INSERT INTO orders VALUES ('', $user_id, '$recipient_name', '$recipient_address', '$recipient_contactNumber', '', '$branch_server_id', '$modeOfDelivery', 'Pending', '$server_timestamp', '', '')";

                            if(mysql_query($sql_orders_save )){
                                $order_id = mysql_insert_id(); 
                                $response['order_message'] = "order saved on database";
                                $order_saved = true;

                            } else {
                                $response["order_message"] = "Sorry, we can't process your request right now. ".mysql_error();
                            }

                        }

                        $counter += 1;

                        if($order_saved) {
                            $sql_order_details_save = "INSERT INTO order_details VALUES ('', $order_id, $product_id, $prescription_id, $quantity, 'type', 0, '$server_timestamp', '', '')";

                            if(mysql_query($sql_order_details_save)){
                                $response['order_details_message_'.$counter] = "order detail saved on database";
                            } else {
                                $response['order_details_message_'.$counter] = "Sorry, we can't process your request right now. ".mysql_error();
                            }       
                        }              
                    }

                    $sql_billings_save = "INSERT INTO billings VALUES ('', $order_id, $totalAmount, $totalAmount, '$payment_status', '$payment_method', '$server_timestamp', '', '')";

                    if(mysql_query($sql_billings_save)){
                        $billing_id = mysql_insert_id();
                        $response['billing_message'] = "order saved on database";
                        $response['billing_id'] = $billing_id;
                        $billing_saved = true;
                    } else {
                        $response["billing_message"] = "Sorry, we can't process your request right now. ".mysql_error();
                        exit(0);
                    }
                // end of billing

                    $sql_paypal_payment = "INSERT INTO payments VALUES ('', $billing_id, '$payment->id','or number', '$server_timestamp', '', '')";

                    if(mysql_query($sql_paypal_payment )){
                        $response['message'] = "payment saved on database";

                        if(mysql_query("DELETE FROM baskets WHERE patient_id =".$user_id." and is_approved = 1")){
                            $response['basket_message'] = "basket/s deleted on database";
                        } else { 
                            $response['basket_message'] = "basket/s not deleted on database";
                        }

                        $sql_get_settings = "SELECT * from settings limit 1";

                        $result = mysql_query($sql_get_settings) or returnError(mysql_error());

                        $point = 0;
                        $earned_points = 0;

                        $db_result = mysql_num_rows($result);

                        // check for empty result
                        if ($db_result > 0) {
                            $row = mysql_fetch_assoc($result);
                            
                            $point = $row['points']/100;
                            $earned_points = $points*$totalAmount;
                        }

                        $sql_update_buyer_points = "UPDATE patients SET points = points + ".$earned_points;
                        if(mysql_query($sql_update_buyer_points)){
                            $response['points_update_message'] = "points updated";
                        } else {
                            $response['points_update_message'] = "points not updated";
                        }
            
} else {
                    // $response['message'] = "payment unsaved on database";
    $response["message"] = "Sorry, we can't process your request right now. ".mysql_error();
}

}

                // Verifying the amount
if ($amount_server != $amount_client) {
    $response["error"] = true;
    $response["message"] = "Payment amount doesn't matched.";
    echoResponse(200, $response);
    return;
}

                // Verifying the currency
if ($currency_server != $currency_client) {
    $response["error"] = true;
    $response["message"] = "Payment currency doesn't matched.";
    echoResponse(200, $response);
    return;
}

                // Verifying the sale state
                // if ($sale_state != 'completed') {
                //     $response["error"] = true;
                //     $response["message"] = "Sale not completed";
                //     echoResponse(200, $response);
                //     return;
                // }

                // storing the saled items
                // insertItemSales($payment_id_in_db, $transaction, $sale_state);

echoResponse(200, $response);
} catch (\PayPal\Exception\PayPalConnectionException $exc) {
    if ($exc->getCode() == 404) {
        $response["error"] = true;
        $response["message"] = "Payment not found!";
        echoResponse(404, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "Unknown error occurred!" . $exc->getMessage();
        echoResponse(500, $response);
    }
} catch (Exception $exc) {
    $response["error"] = true;
    $response["message"] = "Unknown error occurred!" . $exc->getMessage();
    echoResponse(500, $response);
}

function echoResponse($status_code, $response) {
   // $app = \Slim\Slim::getInstance();
    // Http response code
   // $app->status($status_code);

    // setting response content type to json
    //$app->contentType('application/json');

    echo json_encode($response);
}


?>      