<?php namespace App\Http\Controllers;

use Hash;
use Session;
use App\App;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Helpers\DevlessHelper as DLH;
use App\Helpers\Response as Response;

class AppController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $app = App::first();
        $user = User::findOrFail(Session('user'));
        return view('app.edit', compact('app', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    Request $request
     * @return   Response
     * @internal param int $id
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'confirmed|min:6',
            'password_confirmation' => 'min:6',
            'old_password' => 'required',
            ]
        );

        $user = User::findOrFail(Session('user'));
        $app = App::first();
        if ($app && Hash::check($request->input('old_password'), $user->password)) {
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            if ($request->has('password')) {
                $user->password = bcrypt($request->input('password'));
            }

            $app->name = $request->input("name");
            $app->description = $request->input("description");

            ($app->save() && $user->save())? DLH::flash("App updated successfully", 'success'):
                DLH::flash("Changes did not take effect", 'error');
        } else {
            DLH::flash("Could not get app properties or password is incorrect", 'error');
        }
        return back();
    }

    /**
     * Delete entire Devless app
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $app = App::findOrFail($id);
        $app->delete();

        return redirect()->route('app.index');
    }


    /**
     * Generate app access token
     * @param Request $request
     * @return array
     */
    public function token(Request $request)
    {
        $app = App::first();
        if (isset($request['action'])) {
            $new_token = $app->token = md5(uniqid(1, true));
            if ($app->save()) {
                return Response::respond(622, null, ['new_token'=>$new_token]);
            } else {
                return Response::respond(623);
            }
        }

    }
}
