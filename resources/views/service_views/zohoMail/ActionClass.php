<?php
/**
 * Created by Devless.
 * Author: eddymens
 * Date Created: 28th of February 2018 08:46:18 AM
 * Service: zohoMail
 * Version: 1.3.6
 */

use App\Helpers\ActionClass;
     
use  PHPMailer\PHPMailer\PHPMailer as mailer;

use App\Helpers\DataStore;

require 'vendor/autoload.php';

class zohoMail
{
    public $serviceName = 'zohoMail';
    /**
     * Send Emails out to men
     * @ACL private
     */
   public function send($subject, $body, $receiver_emails)
    {
        $rec_emails = $receiver_emails;

        $acc_details = DataStore::service('zohoMail', 'settings')->getData() ['payload']['results'];

        if (!isset($acc_details[0]))
        {
            return 'Your account details are missing. Set this by clicking on DOCS:UI from the gmailer service';
        }

        $sender_email = $acc_details[0]->sender_email;
        $sender_password = $acc_details[0]->sender_password;
        $sender_name = $acc_details[0]->sender_name;
        $mail = new mailer(true);

        // Send mail using gmail 
        $mail->IsSMTP(); // telling the class to use SMTP 
        $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->SMTPSecure = "ssl"; // sets the prefix to the servier 
        $mail->Host = "smtp.zoho.com"; // sets GMAIL as the SMTPserver 
        $mail->Port = 465; // set the SMTP port for the GMAIL server 
        $mail->Username = $sender_email; // GMAIL username
        $mail->Password = $sender_password; // GMAIL password //Typical mail data 
        foreach ($rec_emails as $rec_email) {$mail->AddAddress($rec_email);}
        $mail->SetFrom($sender_email, $sender_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(true);
        try
        {
            $mail->Send();
            return true;
        }
        catch(Exception $e)
        { //Something went bad
            return $mail->ErrorInfo;
        }
    }

   /**
     * This method will execute on service importation
     * 5@ACL private
     */
    public function __onImport()
    {
        //add code here

    }


    /**
     * This method will execute on service exportation
     * @ACL private
     */
    public function __onDelete()
    {
        //add code here

    }



    /**
     * List out all possible callbale methods as well as get docs on specific method 
     * @param $methodToGetDocsFor
     * @return $this;
     */
    public function help($methodToGetDocsFor=null)
    {
        $serviceInstance = $this;
        $actionClass = new ActionClass();
        return $actionClass->help($serviceInstance, $methodToGetDocsFor=null);   
    }

}

