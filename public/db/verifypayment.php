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
            global $userId;
 
            // require_once '../include/Config.php';

            define('DEFAULT_CURRENCY', 'PHP');
            define('PAYPAL_CLIENT_ID', 'AcCQ4PKc6AtjThCsPPkGSH01nPPe7yJKB1oRT39hpgpUGrFkORy9gmuY5_OF4loXc45RaNrUq4h94PP1'); // Paypal client id
            define('PAYPAL_SECRET', 'EH7LOgghxkz-pebLoT1dXSuDo0GiyPI3s1kKaMkp7fKQ25ezZovq5PGQqwVfAAjUpPFcPrAZYA33DcTC'); // Paypal secret
 
            try {
                $paymentId = $_POST['paymentId'];
                $payment_client = json_decode($_POST['paymentClientJson'], true);
 
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

$response['sale_state'] = ""+$sale_state;

$response['payment'] = ""+$payment;
 
                // Storing the payment in payments table
                // $db = new DbHandler();
 $sql_paypal_payment = "INSERT INTO paypal_payments VALUES ('', '$payment->getId()', '$userId', '$payment->getCreateTime()','$payment->getUpdateTime()','$payment->getState()', '$amount_server' ,'$currency_client','$payment->getCreateTime')";

            // $sql_paypal_payment = "INSERT INTO 'paypal_payments' VALUES('', $payment->getId(), $userId, '$payment->getCreateTime', '$payment->getUpdateTime()', '$payment->getState()', $amount_server, $currency_client, '$payment->getCreateTime')";

                if(mysql_query($sql_paypal_payment )){
                    $response['message'] = "payment saved on database";
                } else {
                    // $response['message'] = "payment unsaved on database";
                                $response["message"] = "Sorry, we can't process your request right now. ".mysql_error();
                }
                // $payment_id_in_db = $db->storePayment($payment->getId(), $userId, $payment->getCreateTime(), $payment->getUpdateTime(), $payment->getState(), $amount_server, $amount_server);
 
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