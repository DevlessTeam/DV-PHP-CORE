<?php
namespace App\Helpers;
use Validator;
use App\Helpers\Response as Response;
use Hash;
use App\User;
use App\Helpers\JWT as jwt;
use Response as output;
use Session;
/*
 * @author eddymens <eddymens@devless.io>
*composed of most common used classes and functions
*/

class Helper
{
    /**
     * application error heap
     * @var type
     */

    public static  $ERROR_HEAP =
    [
        #JSON HEAP
        400 => 'Sorry something went wrong with payload(check json format)',
        #SCHEMA HEAP
        500 => 'first schema error',
        # error code for custom messages
        600 => 'Data type does not exist',
        601 => 'reference column column name does not exist',
        602 => 'database schema could not be created',
        603 => 'table could not be created',
        604  =>'service  does not exist or is not active',
        605 => 'no such service resource try (script  db or view)',
        606 => 'created table successfully',
        607 => 'could not find the right DB method',
        608 => 'request method not supported',
        609 => 'data has been added to table successfully',
        610 => 'query paramter does not exist',
        611 => 'table name is not set',
        612 => 'query parameters not set',
        613 => 'database has been deleted successfully',
        614 => 'parameters where or data  not set',
        615 => 'delete action not set ',
        616 => 'caught unknown data type',
        617 =>  'no such table belongs to the service',
        618 =>  'validator type does not exist',
        619 =>  'table was updated successfully',
        620 =>  'could not delete table',
        621 =>  'Could not find asset file',
        622 =>  'Token updated succefully',
        623 =>  'Token could not be updated',
        624 =>  'Sorry this is not an open endpoint',
        625 =>  'Got response successfully',
        626 =>  'saved script',
        627 =>  'Sorry no such resource or resource is private',
        628 =>  'Sorry User is not authenticated, try logging in ',
        629 =>  'Sorry table could not be updated',
        630 =>  'failed to push json to file',
        631 =>  'Sorry access has been revoked',
        632 =>  'There is something wrong with your input field ',
        633 =>  'Token has expired please try logging in again',
        634 =>  'Table does not exist',
        635 =>  'sorry to use offset you need need to set size',
        700 => 'internal system error',
    ];

    /**
     * convert soft types to validator rules
     * @var string
     */
    private static $validator_type =
    [
        'text'      => 'string',
        'textarea'   => 'string',
        'integer'    => 'integer',
        'money'      => 'numeric',
        'password'   => 'alphanum',
        'percentage' => 'integer',
        'url'        => 'url',
        'timestamp'  => 'integer',
        'boolean'    => 'boolean',
        'email'      => 'email',
        'reference'  => 'integer',
    ];
    /**
     * fetch message based on error code
    * @param  stack  $stack
    * @return string or null
    **/
    public static function error_message($stack)
    {
        if(isset(self::$ERROR_HEAP[$stack]))
            return self::$ERROR_HEAP[$stack];
        else
            {
              return null;
            }
    }

    /**
     *  Return results
     *
     * @param  error code  $stack
     * @param  output message  $message
     * @param  additional data $payload
     * @return json
     */
    public static function  interrupt($stack, $message=null, $payload=[]){
        if($message !==null){
            $msg = $message;
        }
        else
        {
            $msg = self::error_message($stack);
        }
        $response = Response::respond($stack, $msg, $payload);
        
        if(session('script_call') == true)
        {
            
            session()->put('script_results',  $response );
            
        }
        else
        {
            die($response);
        }
        


    }

     /**
     * check the validility of a field type
     * uses laravel validator
     * @param string   $field_value
     * @param string parameters to check against $check_against
     * @return boolean
     */
    public static function field_check( $field_value, $check_against)
    {
        //convert check against to field_name for err_msg
        $field_name = $check_against;

        //check if multiple rules are used
        if(strpos($check_against, '|'))
        {
            $rules = explode("|", $check_against);

            foreach($rules as $rule)
            {
                //convert each rule and re-combine
                if(!isset(Helper::$validator_type[$rule]))
                {
                    Helper::interrupt(618,'validator type '.$rule.
                            ' does not exist');
                }
                $check_against = Helper::$validator_type[$rule]."|" ;
            }
        }
        else
        {
            //single validator rule convert field type to lowercase
            $check_against = strtolower($check_against);
            
            if(!isset(Helper::$validator_type[$check_against]))
                {
                    Helper::interrupt(618,'validator type '.$check_against.
                            ' does not exist');
                }
            $check_against = Helper::$validator_type[$check_against] ;

        }


        $state = Validator::make(
            [$field_name => $field_value],
                [$field_name => $check_against]
        );
        if(!$state->fails()){
            return TRUE;
        }
        else
        {
            return $state->messages();
        }
    }

     /**
     * get url parameters
     * @return array
     **/
    public static function query_string()
    {
        if(isset( $_SERVER['QUERY_STRING'])){
         $query  = explode('&', $_SERVER['QUERY_STRING']);
         $params = array();

        foreach( $query as $param )
            {
                if($params !=="")
                {
                    list($name, $value) = explode('=', $param, 2);
                    $params[urldecode($name)][] = urldecode($value);
                }
                 
            }
            return $params;
        }
        else
        {
            $param = "";
            return $param;
        }
    }


    /**
     * Hash password
     * @param type $password
     * @param type $hash
     * @param array $rules
     * @return string
     */
    public static function password_hash($password)
    {
        return bcrypt($password);
    }

    /**
     * compare password hash
     * @param string $user_input
     * @param string $hash
     * @return boolean
     */
    public static function compare_hash($user_input, $hash)
    {
        return Hash::check($user_input, $hash);
    }

    public static function is_admin_login()
    {
       
        return Session()->has('user');
    }
    
    public static function get_authenticated_user_cred($access_state)
   {
        $user_token = request()->header('devless-user-token');
        
        if(self::is_admin_login() || $access_state == false)
        {
            $admin = User::where('role',1)->first();
            $user_cred['id'] = $admin->id;
            $user_cred['token'] = "non for admin";
        }
        else if($user_token !== null || $access_state == false)
        {
            
            $user_data = self::verify_user_token($user_token);
            
            if(isset($user_data->id))
            {    
                $user_cred =
                    [
                        'id' =>$user_data->id,
                        'token' =>$user_data->session_token,

                    ];
            }
            else
            {
                self::interrupt(628);
            }
        }
        else
        {
            self::interrupt(628);
        }
        return $user_cred;
   }
   
        
   public static function verify_user_token($user_token)
   {
       $secret = config('app')['key'];
       
       $jwt = new jwt();
       
       $jwt_payload = json_decode($jwt->decode($user_token, $secret, true));
       
       if($user_token == "null")
        {
           Self::interrupt(633);
        }
       $user_data = User::where('session_token',$jwt_payload->token)
               ->first();
       if($user_data !== null)
       {
           
           $d1 = new \DateTime($user_data->session_time);
           $d2 = new \DateTime();
           $interval = $d1->diff($d2);
           
           if( $interval->h >= 1 || $interval->days > 0)
           {    
               $user_data->session_token = "";
               $user_data->save();
                Self::interrupt(633);
           }
           
           $user_data->session_time = Helper::session_timestamp();
           $user_data->save();

       }
       
       
       
       return $user_data;
   }
   
   public static function session_timestamp()
   {
       return date('Y-m-d H:i:s');
   }


   
}


