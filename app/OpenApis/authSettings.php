<?php 

use App\Helpers\DevlessHelper as DH;

class AuthSettings
{

    public function setAuthSettings($session_time, $self_signup, $verify_email, $expire_session)
    {
        $state = DH::set_user_auth_settings(json_encode(['session_time'=>$session_time, 'self_signup'=>$self_signup, 'verify_email'=>$verify_email, 'expire_session'=>$expire_session]));
        
        return ($state)? ['ok'=>true]: ['ok'=>false];
    }

    public function getAuthSettings()
    {
        $output = DH::get_user_auth_settings();
        if($output) {
            return $output;
        } 
        $this->setAuthSettings(1, 1, 0, 1);
        return ['session_time'=>1, 'self_signup'=>1, 'verify_email'=>0, 'expire_session'=>1]; 
    }

    public function updateAuthSettings($session_time, $self_signup, $verify_email, $expire_session)
    {
        $state =  DH::update_user_auth_settings(json_encode(['session_time'=>$session_time, 'self_signup'=>$self_signup, 'verify_email'=>$verify_email, 'expire_session'=>$expire_session]));
        if($state) {
            return ['ok'=>true];
        } 
    
        $state = $this->setAuthSettings($session_time, $self_signup, $verify_email, $expire_session);
        return ($state)? ['ok'=>true]: ['ok'=>false];
        
    }

}