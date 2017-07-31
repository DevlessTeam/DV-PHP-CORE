<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use App\App;
use App\User;
use Session;
use Illuminate\Http\Request;
use App\Helpers\DevlessHelper as DLH;
use App\Helpers\Helper as helper;
use App\Jobs\RegisterUserJob;
use App\Http\Requests\RegisterUserRequest;

class UserController extends Controller
{
    // TODO: Session store needs to authenticate with a session table for security
    public function get_login()
    {
        if (\Session::has('user')) {
            return redirect('/services');
        } else {
            return view('auth.index');
        }
    }
    public function get_all_users()
    {
        $users = User::orderBy('id', 'desc')->where('role', '!=', 1)->get();
        $menuName = 'devless_users';
        return view('users.index', compact('users', 'menuName'));
    }

    public function update_user(Request $request) 
    {
        if($request->password == '') {
            if(DB::table('users')->where('id', $request->id)                ->update(
                [
                'username'      => $request->username,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'phone_number'  => $request->phone_number,
                'email'         => $request->email,
                'status'        => ($request->active == 'on') ? 1 : 0
                ]
            )) {
                return json_encode(true);
            }
        } else {
            if(DB::table('users')->where('id', $request->id)                ->update(
                [
                'username'      => $request->username,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'phone_number'  => $request->phone_number,
                'email'         => $request->email,
                'password'      => bcrypt($request->password),
                'status'        => ($request->active == 'on') ? 1 : 0
                ]
            )) {
                return json_encode(true);
            }
        }
        return json_encode(false);
    }

    public function remove_user(Request $request)
    {
        if(DB::table('users')->whereIn('id', $request->data)->delete()) {
            return json_encode(true);
        }

        return json_encode(false);
    }
    public function post_login(Request $request)
    {
        $user = DB::table('users')->where('email', $request->input('email'))->where('role', 1) ->first();
        if ($user && Hash::check($request->input('password'), $user->password)) {
            $request->session()->put('user', $user->id);
            DLH::flash('Welcome Back', 'success');

            return redirect('services');
        } else {
            Session::flash('error', 'Incorrect login credentials');

            return back();
        }
    }
    public function get_logout()
    {
        \Session::forget('user');
        \Session::flush();

        return redirect('/');
    }
    public function get_register(Request $request)
    {
        $app = [
        'app_key' => str_random(40),
        'app_token' => md5(uniqid(1, true)),
         ];
        if ($params = helper::query_string()) {
            if (isset($params['url_install']) && isset($params['url_install']) 
                && isset($params['username']) && isset($params['password']) 
                && isset($params['app_name']) && isset($params['email']) && !(\DB::table('apps')->get())
            ) {
                $username = $params['username'][0];
                $email = $params['email'][0];
                $password = $params['password'][0];
                $app_name = $params['app_name'][0];
                $app_token = md5(uniqid(1, true));
                $app_description = (isset($params['app_description'])) ?
                       $params['app_description'][0] : '';

                return $this->registrer(
                    $request,
                    $username,
                    $email,
                    $password,
                    $app_name,
                    $app_token,
                    $app_description
                );
            }
        }

        return view('auth.create', compact('app'));
    }
    public function post_register(RegisterUserRequest $request)
    {
        try {
            $user = $this->dispatch(new RegisterUserJob($request));
            $request->session()->put('user', $user->id);
            DLH::flash('Setup successful. Welcome to Devless', 'success');

            return redirect('services');
        } catch (\Exception $e) {
            DLH::flash('Error setting up', 'error');

            return back()->withInput();
        }
    }


}
