<?php

namespace App\Helpers;
use Session;
/* 
*@author Eddymens <eddymens@devless.io
 */
use App\Helpers\Helper as Helper;

class DevlessHelper extends Helper
{
    //
    
    public static function flash($message,$message_color="#736F6F")
    {
        $custom_colors = 
        [
            'error' => '#EA7878',
            'warning' => '#F1D97A',
            'success' => '#7BE454',
            'notification' => '#736F6F',
        ];
        (isset($custom_colors[$message_color]))?$notification_color = 
                $custom_colors[$message_color]
                : $notification_color = $message_color;
                
        session::flash('color', $notification_color);
        session::flash('flash_message', $message);
    }
}


 