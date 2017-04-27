<?php

use App\Helpers\DataStore;
use App\Http\Controllers\ServiceController as service;

// We begin by reading the HTTP request body contents.
// Since we expect is to be in JSON format, let's parse as well.
$ussdRequest = json_decode(@file_get_contents('php://input'));

// Our response object. We shall use PHP's json_encode function
// to convert the various properties (we'll set later) into JSON.

$ussdResponse = new stdclass;

function requestProcessor($mobile, $type, $acres, $cost, $location, $fname, $operator) {

    $date = new DateTime();

    $service = new service();
    DataStore::service("ttlussd", "tractor_requests", $service)->addData([
        [
            "name"      => $fname,
            "phone"     => $mobile,
            "location"  => $location,
            "acres"     => $acres,
            "cost"      => $cost,
            "time"      => $date->format('Y-m-d H:i:s'),
            "operator"  => $operator,
            "type"      => $type
        ]
    ]);

}

function transportProcessor($mobile, $service, $acres, $cost, $location, $fname) {

    $date = new DateTime();

    $service = new service();
    DataStore::service("ttlussd", "transportation", $service)->addData([
        [
            "name"      => $fname,
            "phone"     => $mobile,
            "location"  => $location,
            "hours"     => $acres,
            "time"      => $date->format('Y-m-d H:i:s'),
            "status"    => "unassigned"
        ]
    ]);

}

function salutation($serve){
    if(is_null($serve) || empty($serve))
        return "Your request was interrupted, please try again. Contact us on 0266800577";
    else{
        return "Thank you for using our service. Your " . $serve .
            " request is currently being processed. You can reach us on 0266800577";
    }
}


// Check if the decoding of the HTTP request body into JSON was
// successful. You can use json_last_error to get the exact nature of
// the error in event the decoding failed. I'll skip that here.
if ($ussdRequest != NULL)
switch ($ussdRequest->Type) {

    // Initiation request. This is the first type of request every
    // USSD application will receive. So let's display our main menu.
    case 'Initiation':
        $ussdResponse->Message =
            "Welcome to TrotroTractor.\n\n" .
            "Select a service. \n" .
            "1. Plough\n2. Harrow\n3. Planting";
        $ussdResponse->Type = 'Response';
        break;

    // Response request. This is where all other interactions occur.
    // Every time the mobile subscriber responds to any of our menus,
    // this will be the type of request we shall receive.
    case 'Response':
        switch ($ussdRequest->Sequence) {

            case 2:
                $trac = array('1' => 'Plough', '2' => 'Harrow', '3' => 'Planting', '4' => 'Transportation');
                if (isset($trac[$ussdRequest->Message])) {
                    $key = array_search($trac[$ussdRequest->Message], $trac);
                    switch ($key) {
                        case 1:
                            $ussdResponse->Message = "Plough Service (GH90 Per Acre).\n".
                            "Enter No. of acre(s).\n OR \n0. To Cancel";

                            $ussdResponse->ClientState = $trac[$ussdRequest->Message];
                            $ussdResponse->Type = 'Response';
                            break;

                        case 2:
                            $ussdResponse->Message = "Harrow Service (GH90 Per Acre).\n".
                            "Enter No. of acre(s).\n OR \n0. To Cancel";

                            $ussdResponse->ClientState = $trac[$ussdRequest->Message];
                            $ussdResponse->Type = 'Response';
                            break;

                        case 3:
                            $ussdResponse->Message = "Planting Service (GH90 Per Acre).\n".
                                "Enter No. of acre(s).\n OR \n0. To Cancel";

                            $ussdResponse->ClientState = $trac[$ussdRequest->Message];
                            $ussdResponse->Type = 'Response';
                            break;

                        case 4:
                            $ussdResponse->Message = "Transportation Service (GHS 4 for 1 Hour):\n".
                                "Enter No. of hour(s).\n OR \n0. To Cancel";
                            $ussdResponse->ClientState = $trac[$ussdRequest->Message];
                            $ussdResponse->Type = "Response" ;
                            break;

                        default:
                        $ussdResponse->Message = 'Invalid selection.';
                        break;
                    }

                } else {
                    $ussdResponse->Message = 'Invalid option.';
                    $ussdResponse->Type = 'Release';
                }
                break;

            case 3:
                switch($ussdRequest->Message){
                    case '0':
                        $ussdResponse->Message =  'Session Cancelled';
                        $ussdResponse->Type = 'Release';
                        break;

                    case '':
                        $ussdResponse->Message =  'Invalid Selection';
                        $ussdResponse->Type = 'Release';
                        break;

                    default:
                        if(is_nan(doubleval($ussdRequest->Message)))
                            $ussdResponse->Message =  'Invalid Entry';
                        else if(doubleval($ussdRequest->Message) < 1)
                            $ussdResponse->Message =  'Invalid No. of Acre';
                        else{
                            if ($ussdRequest->ClientState != "Transportation"){

                                $ussdResponse->ClientState = $ussdRequest->ClientState . ",&*" . doubleval($ussdRequest->Message) .
                                    ",&*" . doubleval($ussdRequest->Message) * 90;

                                $ussdResponse->Message = "No. of Acre(s): " . doubleval($ussdRequest->Message) . "\n" .
                                    "Service Cost: GHC " . doubleval($ussdRequest->Message)*90 . "\n\n" .
                                    "Enter Farm Location.\n OR \n0. To Cancel";
                            } else {
                                $ussdResponse->ClientState = $ussdRequest->ClientState . ",&*" . doubleval($ussdRequest->Message) .
                                    ",&*" . doubleval($ussdRequest->Message) * 4;

                                $ussdResponse->Message = "No. of Hour(s): " . doubleval($ussdRequest->Message) . "\n" .
                                    "Service Cost: GHC " . doubleval($ussdRequest->Message)*4 . "\n\n" .
                                    "Enter Farm Location.\n OR \n0. To Cancel";
                            }
                        }
                        $ussdResponse->Type = 'Response';
                        break;
                }
                break;

            case 4:
                switch($ussdRequest->Message){
                    case '0':
                        $ussdResponse->Message =  'Session Cancelled';
                        $ussdResponse->Type = 'Release';
                        break;

                    case '':
                        $ussdResponse->Message =  'Invalid Selection';
                        $ussdResponse->Type = 'Release';
                        break;

                    default:
                        $ussdResponse->ClientState = $ussdRequest->ClientState . ",&*" . $ussdRequest->Message;
                        $ussdResponse->Message = "Farm Location: " . $ussdRequest->Message . "\n\n" .
                             "Enter Your Name.\n OR \n0. Cancel";

                        $ussdResponse->Type = 'Response';
                        break;
                }
                break;

            case 5:
                switch($ussdRequest->Message){
                    case '0':
                        $ussdResponse->Message =  'Session Cancelled';
                        $ussdResponse->Type = 'Release';
                        break;

                    case '':
                        $ussdResponse->Message =  'Invalid Selection';
                        $ussdResponse->Type = 'Release';
                        break;

                    default:
                        $ussdResponse->ClientState = $ussdRequest->ClientState . ",&*" . $ussdRequest->Message;
                        $ussdResponse->Message = "Farmer Name: " . $ussdRequest->Message . "\n" .
                             "Enter your number for payment (MTN or Airtel only).\n or \n0. To Cancel ";

                        $ussdResponse->Type = 'Response';
                        break;
                }
                break;

            case 6:
                 switch ($ussdRequest->Message) {
                    case '0':
                        $ussdResponse->Message =  'Session Cancelled';
                        break;

                    default:

                        $regex = "/\d{10}/";
                        if(preg_match($regex, $ussdRequest->Message)) {

                            $clientReq = explode(",&*", $ussdRequest->ClientState);
                            $arrLen = count($clientReq);

                            $name = $clientReq[4];
                            $loc = $clientReq[3];
                            $cost = $clientReq[2];
                            $acres = $clientReq[1];
                            $service = $clientReq[0];
                            $phone = $ussdRequest->Message;

                            requestProcessor($phone, $service, $acres, $cost, $loc, $name, $ussdRequest->Operator);

                            $ussdResponse->Message = salutation($service);
                        } else {
                            $ussdResponse->Message = "Your entry $ussdRequest->Message is invalid."
                                    ." Please enter a valid MTN, Tigo or Airtel 10 digits number.";
                        }
                        break;
                }
                        $ussdResponse->Type = "Release";
                break;

                // Unexpected request. If the code here should ever
                // execute, it means the request is probably forged.
                // default:
                $ussdResponse->Message = 'Unexpected request.';
                $ussdResponse->Type = 'Release';
                break;
         }
         break;

    // Session cleanup.
    // Not much to do here.
    default:
        $ussdResponse->Message = 'Duh.';
        $ussdResponse->Type = 'Release';
        break;
}

// An error has occured.
// Probably the request JSON could not be parsed.
  else {
     $ussdResponse->Message = 'Invalid USSD request.';
    $ussdResponse->Type = 'Release'; }

// Let's set the HTTP content-type of our response, encode our
// USSD response object into JSON, and flush the output.
header('Content-type: application/json; charset=utf-8');
header("access-control-allow-origin: http://apps.smsgh.com");
header("Access-Control-Allow-Methods: POST");
echo json_encode($ussdResponse);
