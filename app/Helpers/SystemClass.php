<?php


use App\Helpers\DevlessHelper as DVH;
/**
 * Created by Devless.
 * User: eddymens
 * Date Created: 23rd of September 2016 09:01:20 AM
 * @Service: event
 * @Version: 1.0
 */


//Action method for serviceName
class devless
{
    public $serviceName = 'dvauth';
    private $auth;
    
    public function __construct()
    {
        $this->auth = new DVH();
        
    }
    /**
     * method for handling user signup
     * @ACL public
     */
    public function signUp($email=null, $password=null, $username=null,
            $phone_number=null, $first_name=null, $last_name=null, $remember_token=null)
    {
       $payload = get_defined_vars();
       
       $payload = self::getSetParams($payload);
       
       $auth = $this->auth;
       
       $output = $auth->signup($payload);
       return $output;
        
       
    }


    /**
     * method for handling user login
     * @ACL public
     */
    public function login($username=null, $email=null, $phone_number=null, $password=null)
    {
       $payload = get_defined_vars();
       
       $payload = self::getSetParams($payload);
       
       $auth = $this->auth;
       
       $output = $auth->login($payload);
       return $output;
       
    }
    
    /**
     * get user profile
     * @ACL public
    */
    public function profile() 
    {
        $auth = $this->auth;
        
        $profile = $auth->get_profile();
        
        return $profile;
    }
    
    /**
     * logout
     * @ACL public
     */
    public function logout()
    {
        $auth = $this->auth;
        $logState = $auth->logOut();
        
        return $logState;
    }
    
    /**
     * method for handling user login
     * @ACL public
    */
    public function updateProfile($email=null, $password=null, $username=null,
            $phone_number=null, $first_name=null, $last_name=null, $remember_token=null)
    {
       $payload = get_defined_vars();
       
       foreach($payload as $key=>$value) {
           if($value == null){
               unset($payload[$key]);
           }
           
       }
       $auth = $this->auth;
       
       $output = $auth->update_profile($payload);
       return $output;
        
       
    }
    
    private static function getSetParams($payload)
    {
        foreach($payload as $key=>$value){
           if($value == null){
               unset($payload[$key]);
           }
           
       }
       return $payload;
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

