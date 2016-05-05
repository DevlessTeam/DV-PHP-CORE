<?php
namespace App\Helpers;
use Validator;
use App\Helpers\Response as Response;

/* 
 * @author eddymens <eddymens@devless.io>
*composed of most common used classes and functions 
*/

class Helper
{
     
    public static  $ERROR_HEAP = [
        #JSON HEAP
        400 => 'Sorry something went wrong with payload(check json format)',
        #SCHEMA HEAP
        500 => 'first schema error',
        #600 is used as error code for custom messages(make sure to 
        #comment register them)
        #600 => 'Data type does not exist',
        #601 => 'reference column column name does not exist',
        #602 => 'database schema could not be created',
        #603 => 'table could not be created',
        604  =>  'service resource does not exist or is not active',
        605 =>   'no such service type try (script or db)',
        606 =>   'created database Schema successfully',
        607 =>   'could not find the right DB method',
        608 =>   'request method not supported',
        609 =>   'data has been added to table successfully',
        610 =>   'query paramter does not exist',
        611 =>   'table name is not set',
        612 =>   'query parameters not set',
        613 =>   'dropped table succefully',
        614 =>   'parameters where or data  not set',
        615 =>   'delete action not set',   
        700 =>   'internal system error',
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
     * stops request processing and returns error payload 
     *
     * @param  error code  $stack
     * @return json 
     */
    public static function  interrupt($stack, $message=null){
        if($message !==null){
            $msg = $message;
        }
        else
        {
            $msg = self::error_message($stack);
        }
        $response = Response::respond($stack, $msg, []); 
           
         die($response);
    }
    
     /**
     * check the validility of a field type
     *uses laravel validator 
     * @param string   $field_value
     * @param string parameters to check against $check_against
     * @return boolean 
     */
    public static function field_check( $field_value, $check_against)
    {
        
        $state = Validator::make(
            ['name' => $field_value],
                ['name' => $check_against]
        );
        if(!$state->fails()){
            return TRUE;
        }
        else
        {
            return $state->messages();
        }
    }
    
    public static function query_string()
    {
        if(isset( $_SERVER['QUERY_STRING'])){
         $query  = explode('&', $_SERVER['QUERY_STRING']);
         $params = array();
        
        foreach( $query as $param )
            {
             
              list($name, $value) = explode('=', $param, 2);
              $params[urldecode($name)][] = urldecode($value);
            }
            return $params;
        }
        else
        {
            $param = "";
            return $param;
        }
    }
}
