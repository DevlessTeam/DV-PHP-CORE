<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Session;
use App\App;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Helpers\DevlessHelper as DLH;

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

    public function post_login(Request $request)
    {
        $loginCredentials = array(
        'email' => $request->input('email'),
        'password' => $request->input('password')
        );

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

    public function get_register()
    {
        $app = array(
        'app_key' => "TEMPORAL-APP-KEY",
        'app_token' => md5(uniqid(1, true))
         );
        return view('auth.create', compact('app'));
    }

    public function post_register(Request $request)
    {

        $this->validate($request, [
        'username' => 'required|max:255|unique:users',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|confirmed|min:6',
        'password_confirmation' => 'required|min:6',
        'app_name' => 'required|max:255',
        'app_description' => 'required|max:255',
        ]);

        $user = new User;
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->role = 1;
        $user->status = 0;
        

        $app = new App;
        $app->name = $request->input('app_name');
        $app->description = $request->input('app_description');
        $app->api_key = $request->input('app_key');
        $app->token = $request->input('app_token');

        if ($user->save() && $app->save()) {
            $request->session()->put('user', $user->id);
            DLH::flash('Setup successful. Welcome to Devless', 'success');
            return redirect('services');
        }

        return back()->withInput();
        DLH::flash('Error setting up', 'error');
    }
}
