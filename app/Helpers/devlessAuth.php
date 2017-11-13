<?php

namespace App\Helpers;

use DB;
use Session;
use App\User as user;
use App\Helpers\Jwt as jwt;
use App\Helpers\DataStore;
use App\Http\Controllers\ServiceController as service;
use App\Helpers\DataStore as DS;

trait devlessAuth
{

    public $expected_fields =
    [
        'email' => 'email',
        'username' => 'text',
        'password' => 'password',
        'first_name' => 'text',
        'last_name' => 'text',
        'remember_token' => 'text',
        'status' => 'text',
        'phone_number' => 'text',

    ];
    /**
     * signup new users onto devless.
     *
     * @param type $payload
     *
     * @return alphanum
     */
    public function signup($payload)
    {
        $auth_settings = json_decode(DevlessHelper::get_user_auth_settings(), true);
        $verify_email = $auth_settings['verify_email'];
        
        $username = (isset($payload['username'])) ? $payload['username'] : '';

        $email = (isset($payload['email'])) ? $payload['email'] : '';
        $phone_number = (isset($payload['phone_number'])) ? $payload['phone_number'] : '';

        $existing_users = \DB::table('users')->orWhere('username', $username)->whereNotIn('username', [''])
                ->orWhere('email', $email)->whereNotIn('email', [''])
                ->orWhere('phone_number', $phone_number)->whereNotIn('phone_number', [''])
                ->first();
        
        if ($existing_users != null) {
            return Helper::interrupt(644);
        }

        $user = new User();

        $token = $this->auth_fields_handler($payload, $user);

        if ($token == false) {
            return $token;
        }

        $user->status = ($verify_email)?0:1;
        
        ($verify_email)?$this->generate_email_verification_code($user->id):'';

        $user->session_token = $session_token = md5(uniqid(1, true));
        
        //check if either username or email and password is set
        if (!(isset($user->password))) {
            if (!isset($user->username) || isset($user->email) || !isset($user->phone_number)) {
                return false;
            }
        }
        

        if ($user->save()) {
            $token_payload =
                [
                    'token' => $session_token,

                ];

                $prepared_token = $this->set_session_token($token_payload, $user->id);
                $profile = \DB::table('users')->where('id', $user->id)
                ->select(['username', 'first_name', 'last_name', 'phone_number', 'id', 'email', 'status'])
                ->first();
                $user_obj = [
                'profile' => $profile,
                'token' => $prepared_token,
                ];

                return $user_obj;
        } else {
            return false;
        }
    }

    /**
     * get authenticated user details.
     *
     * @return array
     */
    public function get_profile()
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $db = new DB();
            $user_data = $db::table('users')->where('id', $token['id'])
                ->select(
                    'id',
                    'username',
                    'email',
                    'phone_number',
                    'first_name',
                    'last_name',
                    'status',
                    'created_at',
                    'updated_at',
                    'remember_token'
                )
                ->first();
             $service = new service();
             $output = [];
             if(\Schema::hasTable('devless_user_profile')) {
               $output = DS::service('devless', 'user_profile', $service)->where('users_id', $token['id'])->getData()['payload']['results'];
                
             }
            if (!isset($output[0])) {
                return array_merge((array)$user_data, []);
            }
            $newOutput = (array)$output[0];
            unset($newOutput['id'], $newOutput['devless_user_id'], $newOutput['users_id']);
            return  array_merge((array)$user_data, $newOutput);
        }

        return false;
    }

    /**
     * authenticate and login devless users.
     *
     * @param $payload
     *
     * @return alphanum
     *
     * @internal param type $request
     */
    public function login($payload)
    {
        $user = new user();
        $fields = $payload;

        foreach ($fields as $field => $value) {
            $field = strtolower($field);
            ${$field} = $value;
        }

        if (isset($email, $password)) {
            $user_data = $user::where('email', $email)->first();
        } elseif (isset($username, $password)) {
            $user_data = $user::where('username', $username)->first();
        } elseif (isset($phone_number, $password)) {
            $user_data = $user::where('phone_number', $phone_number)->first();
        } else {
            return false;
        }
        if ($user_data !== null) {
            if (!$user_data->status) {
                Helper::interrupt(643);
            }
            $correct_password =
                   (Helper::compare_hash($password, $user_data->password)) ? true : false;
            $user_data->session_token = $session_token = md5(uniqid(1, true));

            if ($correct_password && $user_data->save()) {
                $token_payload =
                    [
                        'token' => $session_token,

                    ];

                    $prepared_token = $this->set_session_token($token_payload, $user_data->id);
                    $profile = \DB::table('users')->where('id', $user_data->id)
                    ->select(['username', 'first_name', 'last_name', 'phone_number', 'id', 'email', 'role'])
                    ->first();
                    $user_obj = [
                    'profile' => $profile,
                    'token' => $prepared_token,
                    ];
                    $extra_profile  = [];
                    $service = new service();
                    
                    if(\Schema::hasTable('devless_user_profile')){
                        $extra_profile = DS::service('devless', 'user_profile', $service)->where('users_id', $user_obj['profile']->id)->getData()['payload']['results'];

                    }
                   $user_obj['profile'] = (array)$user_obj['profile'] + $extra_profile;
                    return $user_obj;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * update devless user profile.
     *
     * @param $payload
     *
     * @return bool
     *
     * @internal param type $request
     */
    public function update_profile($payload)
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $user = new user();

            //unchangeable fields
            $indices = [
                'session_token',
                'role',
            ];

            $unset = function ($payload, $index) {
                unset($payload[$index]);
            };

            foreach ($indices as $index) {
                (isset($payload[$index])) ? $unset($payload, $index) : false;
            }

            if (isset($payload['password'])) {
                $payload['password'] = Helper::password_hash($payload['password']);
            }
            foreach ($payload as $field_name => $value) {
                $expected_field = $this->expected_fields[$field_name];
                $valid = Helper::field_check($value, $expected_field);
                ($valid !== true)?Helper::interrupt(616, 'There is something wrong with your '.$field_name):false;
            }
            if ($user::where('id', $token['id'])->update($payload)) {
                return \DB::table('users')->where('id', $token['id'])
                ->select(['username', 'first_name', 'last_name', 'phone_number', 'id', 'email', 'status'])
                ->first();
            }
        }

        return false;
    }

    /**
     * delete a devless user.
     *
     * @return bool
     *
     * @internal param type $request
     */
    public function delete()
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $user = new user();
            if ($user::where('id', $token['id'])->delete()) {
                return true;
            }
        }

        return false;
    }

    public function logout()
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $user = new user();
            if ($user::where('id', $token['id'])->update(['session_token' => ''])) {
                return true;
            }
        }

        return false;
    }

    /**
     * check if needed auth fields are satisfied.
     *
     * @param $fields
     * @param $user
     *
     * @return mixed
     */
    public function auth_fields_handler($fields, $user)
    {
    
        foreach ($fields as $field => $value) {
            $field = strtolower($field);

            if (isset($this->expected_fields[$field])) {
                $valid = Helper::field_check($value, $this->expected_fields[$field]);
                if ($valid !== true) {
                    Helper::interrupt(616, 'There is something wrong with your '.$field);
                }
                if ($field == 'password') {
                    $user->$field = Helper::password_hash($value);
                } else {
                    $user->$field = $value;
                }
            }
        }

        return $user;
    }

    /**
     * set session token.
     *
     * @param $payload
     * @param $user_id
     *
     * @return bool
     *
     * @internal param type $request
     */
    public function set_session_token($payload, $user_id)
    {
        $jwt = new jwt();
        $secret = config('app')['key'];

        $payload = json_encode($payload);

        if (DB::table('users')->where('id', $user_id)->update(['session_time' => Helper::session_timestamp()])) {
            return $jwt->encode($payload, $secret);
        } else {
            return false;
        }
    }

    public function generate_email_verification_code($user_id)
    {
        // return DataStore::setDump(md5(uniqid(1, true).'_'.$user_id, $user_id));
    }
    public static function set_user_auth_settings($settings)
    {
        return DataStore::setDump('devless_auth_settings', $settings);
    }

    public static function get_user_auth_settings()
    {
        return DataStore::getDump('devless_auth_settings');
    }

    public static function update_user_auth_settings($newSettings)
    {
        return DataStore::updateDump('devless_auth_settings', $newSettings);
    }

    public static function recover_password($recover_email)
    {
        return true;//file_get_contents('http://localhost:6060/status');
    }
}
