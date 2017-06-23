<?php

use App\Helpers\Helper;

$action = (isset($_REQUEST['action']))?$_REQUEST['action']:Helper::interrupt(1111, "please set action"); 
$service = (isset($_REQUEST['service']))?$_REQUEST['service']:Helper::interrupt(1111, "please provide service name"); 

if($action ==  'get_script') {
	$serviceObj = DB::table('services')->where('name', $service)->first();
	echo $serviceObj->script;
} elseif($action == 'update_script') {
	$script = (isset($_REQUEST['script']))?$_REQUEST['script']:Helper::interrupt(1111, "please provide a script to update"); 
	echo DB::table('services')->where('name', $service)->update(['script'=>$script]);
} else {
	Helper::interrupt(1111, "Hmm the action you specified does not exist");
}
