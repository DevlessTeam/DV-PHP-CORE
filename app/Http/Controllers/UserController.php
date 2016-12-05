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
        $users = User::orderBy('id', 'asc')->paginate(10);
        return view('users.index', compact('users'));
    }
    public function post_login(Request $request)
    {
        $loginCredentials = [
        'email'    => $request->input('email'),
        'password' => $request->input('password'),
        ];

        $user = DB::table('users')->where('email', $request->input('email'))->first();
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
        'app_key'   => str_random(40),
        'app_token' => md5(uniqid(1, true)),
         ];

         if($params = helper::query_string()) {
             if(isset($params['url_install']) && isset($params['url_install'])&&
                     isset($params['username']) && isset($params['password']) &&
                     isset($params['app_name']) && isset($params['email']) && !(\DB::table('apps')->get()) ){
                $username = $params['username'][0];
                $email = $params['email'][0];
                $password = $params['password'][0];
                $app_name = $params['app_name'][0];
                $app_token = md5(uniqid(1, true));
                $app_description = (isset($params['app_description']))?
                        $params['app_description'][0]:'';
                return $this->registrer($request, $username, $email, $password,
                        $app_name, $app_token, $app_description );
             }
         }

        return view('auth.create', compact('app'));
    }

    public function post_register(Request $request)
    {
        $this->validate($request, [
        'username'              => 'required|max:255|unique:users',
        'email'                 => 'required|email|max:255|unique:users',
        'password'              => 'required|confirmed|min:6',
        'password_confirmation' => 'required|min:6',
        'app_name'              => 'required|max:255',
        'app_description'       => 'required|max:255',
        ]);

        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $app_name = $request->input('app_name');
        $app_token = md5(uniqid(1, true));
        $app_description = $request->input('app_description');
        return $this->registrer($request, $username, $email, $password, $app_name, $app_token, $app_description );

    }

    /**
     * registrer responsible for registring new apps
     * @param type $username
     * @param type $email
     * @param type $password
     * @param type $app_name
     * @param type $app_token
     * @param type $app_description
     * @return type
     */
    public function registrer
            (
            Request $request,
            $username,
            $email,
            $password,
            $app_name,
            $app_token,
            $app_description = ''
            ) {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->role = 1;
        $user->status = 0;


        $app = new App();
        $app->name = $app_name;
        $app->description = $app_description;
        $app->token = $app_token;

        if ($user->save() && $app->save()) {
            $request->session()->put('user', $user->id);
            DLH::flash('Setup successful. Welcome to Devless', 'success');

            return redirect('services');
        }

        return back()->withInput();
        DLH::flash('Error setting up', 'error');
    }
}
