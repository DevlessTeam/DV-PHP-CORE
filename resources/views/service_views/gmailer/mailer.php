<?php

use  PHPMailer\PHPMailer\PHPMailer as mailer;

require 'vendor/autoload.php';

$mail = new mailer(true);

//Send mail using gmail
$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPAuth = true; // enable SMTP authentication
$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
$mail->Port = 465; // set the SMTP port for the GMAIL server
$mail->Username = "primerossgh@gmail.com"; // GMAIL username
$mail->Password = "Tocredwaters#2"; // GMAIL password

$email = "coco@morsin.com";
$name  = "edmond";

$email_from = "edmond@allbrands.io";
$name_from  = "AllBrands";
//Typical mail data
$mail->AddAddress($email, $name);
$mail->SetFrom($email_from, $name_from);
$mail->Subject = "Customer Notice";
$mail->Body = "<b>Welcome</b>";

try{
	$mail->Send();
	echo "Success!";
} catch(Exception $e){
	//Something went bad
	echo "Fail - " . $mail->ErrorInfo;
}

