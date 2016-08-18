<?php


function sendMail($receiver, $title, $message)
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.postmarkapp.com/email",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{From: 'edmond@devless.io', To: '$receiver', Subject: '$title', HtmlBody: '$message<br><br><br><h4>Powered By <a href=\'https://devless.io\'>Devless</a></h4>'}",
    CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "cache-control: no-cache",
    "content-type: application/json",
    "x-postmark-server-token: ec0fe614-fdb7-4b2d-9371-5b7f09631fcc"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
          return  $err;
    } else {
          return $response;
    }
}

function getParam($EVENT, $field)
{
   
    return $EVENT['params'][0]['field'][0][$field];
}
