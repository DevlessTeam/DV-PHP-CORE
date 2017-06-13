<?php

namespace App\Helpers;

use DB;
use Session;
use App\User as user;
use App\Helpers\Jwt as jwt;
use App\Helpers\DataStore;
trait devlessAuth
{
    /**
     * signup new users onto devless.
     *
     * @param type $payload
     *
     * @return alphanum
     */
    public function signup($payload)
    {
        $username = (isset($payload['username'])) ? $payload['username'] : '';

        $email = (isset($payload['email'])) ? $payload['email'] : '';
        $phone_number = (isset($payload['phone_number'])) ? $payload['phone_number'] : '';

        $existing_users = \DB::table('users')->orWhere('username', $username)->whereNotIn('username', [''])
                ->orWhere('email', $email)->whereNotIn('email', [''])
                ->orWhere('phone_number', $phone_number)->whereNotIn('phone_number', [''])
                ->get();
        if ($existing_users != null) {
            return Response::respond(1001, 'Seems User already exists');
        }

        $user = new User();

        $token = $this->auth_fields_handler($payload, $user);

        if ($token == false) {
            return $token;
        }

        $user->status = 1;
        $user->session_token = $session_token = md5(uniqid(1, true));

        //check if either username or email and password is set
        if (!isset($user->password) || !(isset($user->username) || isset($user->email))) {
            return false;
        }

        if ($user->save()) {
            $token_payload =
                [
                    'token' => $session_token,

                ];

            $prepared_token = $this->set_session_token($token_payload, $user->id);
            $profile = \DB::table('users')->where('id', $user->id)
                ->select(['username', 'first_name', 'last_name', 'phone_number', 'id', 'email', 'role'])
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
                    'remember_token',
                    'role'
                )
                ->first();

            return $user_data;
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
        } else {
            return false;
        }
        if ($user_data !== null) {
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
                'status',
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

            if ($user::where('id', $token['id'])->update($payload)) {
                return \DB::table('users')->where('id', $token['id'])
                ->select(['username', 'first_name', 'last_name', 'phone_number', 'id', 'email', 'role'])
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
        $expected_fields =
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

        foreach ($fields as $field => $value) {
            $field = strtolower($field);

            if (isset($expected_fields[$field])) {
                $valid = Helper::field_check($value, $expected_fields[$field]);
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

        if(DB::table('users')->where('id', $user_id)->update(['session_time' => Helper::session_timestamp()])) {
            return $jwt->encode($payload, $secret);
        } else {
            return false;
        }   
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


}
