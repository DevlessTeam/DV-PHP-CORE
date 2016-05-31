<?php namespace App\Http\Controllers;

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
     * @param  int  $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
				$this->validate($request, [
					'username' => 'required|max:255',
					'email' => 'required|email|max:255',
					'password' => 'required|confirmed|min:6',
					'password_confirmation' => 'required|min:6',
				]);

        if ($app = App::first()) {
            if (isset($request['action'])) {
                $new_token = $app->token = md5(uniqid(1, true));
                if ($app->save()) {
                    return Response::respond(622, null, ['new_token'=>$new_token]);
                } else {
                    return Response::respond(623);
                }
            }

						$user = User::findOrFail(Session('user'));
						$user->username = $request->input('username');
						$user->email = $request->input('email');
						$user->password = bcrypt($request->input('password'));

            $app->name = $request->input("name");
            $app->description = $request->input("description");
            $app->api_key = $_SERVER['SERVER_NAME'];
                    #$app->token = $request->input("token");

                    ($app->save() && $user->save())? DLH::flash("App updated successfully", 'success'):
                        DLH::flash("Changes did not take effect", 'error');
        } else {
            DLH::flash("Could not get app properties", 'error');
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $app = App::findOrFail($id);
        $app->delete();

        return redirect()->route('app.index');
    }
}
