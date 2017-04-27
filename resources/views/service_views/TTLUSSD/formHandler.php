<?php

include 'ActionClass.php';

$login = new TTLUSSD();

$id = $login->sign_in($_REQUEST);

// var_dump($id);

//  $user = \DB::table('users')->where('username', $_REQUEST["username"]);
//  var_dump($user->password);
        // if ($user && \Hash::check($_REQUEST["password"], $user->password)) {
        //     \Session::put('user_profile', $user->id);
        //     \Session::put('user_details', $user);
        //     return DvRedirect(DvNavigate($service, 'index'), 0);
        // } else {
        //     echo "Invalid login details";
        //     return DvRedirect(DvNavigate($service, 'login'), 0);
        // }
