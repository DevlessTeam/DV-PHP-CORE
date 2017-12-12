<?php
/**
 * Created by Devless.
 * Author: koficodes
 * Date Created: 20th of January 2017 02:52:24 AM
 * @Service: notify
 * @Version: 1.0
 */
require_once 'lib/Config.php';
require_once 'lib/NotifyBaseClass.php';
require 'lib/Sms.php';
require 'lib/Email.php';
require 'lib/Push.php';

use App\Http\Controllers\ServiceController as service;
use Notify\Email;
use Notify\Sms;
use Notify\Push;
use Notify\NotifyBaseClass;
$payload['service_name'] = 'notify';
//Action method for serviceName
class Notify
{
    public $serviceName = 'notify';
    
    

    /**
     * @description {sends sms to user without  extra user infomation}
     * 
     * @ACL protected
     */
    //
    public function sms($numbers = [], $message = '')
    {
        $sms = new Sms();
        $sms->send($numbers, $message);

    }

    /**
     * @description {sends sms to user with  extra user infomation}
     * @ACL protected
     */
    public function smsFromRecords($message = '', $userInfoColumns = '', $serviceName = '', $table = '', $usersNumberColumn = '', $conditionArray = [], $limit = 0)
    {
        $users = NotifyBaseClass::getDataFromRecords($userInfoColumns, $serviceName, $table, $usersNumberColumn, $conditionArray);
        $sms = new Sms();
        if (empty($userInfoColumns)) {
            $sms->send($users, $message);
        } else {
            $sms->sendWithInfo($users, $usersNumberColumn, $message);
        }

    }

    /**
     * @description {sends email to users without extra user information}
     * @ACL protected
     */
    public function email($emails = [], $subject = '', $message = '')
    {
        $email = new Email();
        $email->send($emails, $subject, $message);

    }

    /**
     * @description {sends email to users with extra user information}
     * @ACL protected
     */
    public function emailFromRecords($mailsubject = '', $message = '', $userInfoColumns = '', $serviceName = '', $table = '', $usersEmailColumn = '', $conditionArray = [], $limit = 0)
    {
        $users = NotifyBaseClass::getDataFromRecords($userInfoColumns, $serviceName, $table, $usersEmailColumn, $conditionArray);
        $email = new Email();
        if (empty($userInfoColumns)) {
            $email->send($users, $mailsubject, $message);
        } else {
            $email->sendWithInfo($users, $mailsubject, $usersEmailColumn, $message);
        }

    }

    /**
     * @description {sends push notification to users without extra user information}
     * @ACL protected
     */

    public function push($channel = '', $event = '',$message = '')
    {
        $push = new Push();
        $push->send($channel,$event,$message);

    }

    /**
     * @description {sends push notification to users with extra user information}
     * @ACL protected
     */

    public function pushFromRecords($event = '',$message='', $userInfoColumns = '', $serviceName = '', $table = '', $usersChannelColumn = '', $conditionArray = [], $limit = 0)
    {   
        $channel = '';
        $users = NotifyBaseClass::getDataFromRecords($userInfoColumns, $serviceName, $table, $usersChannelColumn, $conditionArray);
    
         $push = new Push();      
        if (empty($userInfoColumns)) {
            $push->send($channel,$event,$message);
        } else {
            $push->sendWithInfo($users, $event, $usersChannelColumn, $message);
        }

    }


    /**
     * This method will execute on service importation
     * @ACL private
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

}
