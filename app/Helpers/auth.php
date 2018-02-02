<?php

namespace App\Helpers;

use Hash;
use Session;
use App\User;
use App\Helpers\Jwt as jwt;
use App\Helpers\DevlessHelper;

trait auth
{
    /**
     * Hash password.
     *
     * @param type $password
     *
     * @return string
     *
     * @internal param type $hash
     * @internal param array $rules
     */
    public static function password_hash($password)
    {
        return Hash::make($password);
    }

    /**
     * compare password hash.
     *
     * @param string $user_input
     * @param string $hash
     *
     * @return bool
     */
    public static function compare_hash($user_input, $hash)
    {
        return Hash::check($user_input, $hash);
    }

    /**
     * Check if current user is admin.
     *
     * @return mixed
     */
    public static function is_admin_login()
    {
        return (Session()->has('user')) ? true : false;
    }

    /**
     * Get authenticated user cred.
     *
     * @param $force_auth
     *
     * @return array
     */
    public static function get_authenticated_user_cred($force_auth)
    {
        $user_token = request()->header('devless-user-token');
        $user_cred = [];

        if (self::is_admin_login() && $force_auth == true) {
            $admin = User::where('role', 1)->first();
            $user_cred['id'] = $admin->id;
            $user_cred['token'] = null;
        } elseif ($user_token != null && $user_token != 'null'
            && ($force_auth == true || $force_auth == false)
        ) {
            $user_data = self::verify_user_token($user_token, $force_auth);
            
            if (isset($user_data->id)) {
                $user_cred =
                    [
                        'id' => $user_data->id,
                        'token' => $user_data->session_token,

                    ];
            } elseif ($force_auth) {
                self::interrupt(628, null, [], true);
            }
        } elseif ($force_auth == true) {
            self::interrupt(628, null, [], true);
        }
        return $user_cred;
    }

    /**
     * Verify user token.
     *
     * @param $user_token
     *
     * @return mixed
     */
    public static function verify_user_token($user_token, $force_auth)
    {
        $auth_settings = json_decode(DevlessHelper::get_user_auth_settings(), true);
        $session_time = $auth_settings['session_time'];
        $expire_session = $auth_settings['expire_session'];
        $secret = config('app')['key'];

        $jwt = new jwt();

        $jwt_payload = json_decode($jwt->decode($user_token, $secret, true));
        if ($jwt_payload == null && $force_auth == false) {
            return (object)['id'=>'', 'session_token'=>''];
        } elseif ($force_auth == true && $jwt_payload == null) {
            throw new \UnexpectedValueException('Passed in an invalid `devless-user-token`');
        }
        if ($user_token == 'null') {
            self::interrupt(633, null, [], true);
        }
        $user_data = User::where('session_token', $jwt_payload->token)
               ->first();
        if ($user_data !== null) {
            $d1 = new \DateTime($user_data->session_time);
            $d2 = new \DateTime();
            $interval = $d1->diff($d2);
            
            if ((int)$expire_session == 0) {
                if ($interval->h >= $session_time || $interval->days > 0) {
                    $user_data->session_token = '';
                    $user_data->save();
                    self::interrupt(633, null, [], true);
                }
            }
            $user_data->session_time = self::session_timestamp();
            $user_data->save();
        }
        return $user_data;
    }
}
