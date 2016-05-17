<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\App;
use Illuminate\Http\Request;
use \App\Helpers\DevlessHelper as DLH;
class AppController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$app = App::first();

		return view('app.edit', compact('app'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('app.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{       $user_login = 1;
                $app_name = 'zowy';
		$app = new App();
		$app->name = $request->input("name");
                $app->description = $request->input("description");
                $app->api_key = $request->input("name");
                $app->token = md5(uniqid(1, true));

		$app->save();

		return redirect()->route('app.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$app = App::findOrFail($id);

		return view('apps.show', compact('app'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$app = App::findOrFail($id);

		return view('apps.edit', compact('app'));
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
		if($app = App::first())
                {
                $app->name = $request->input("name");
                $app->description = $request->input("description");
                $app->api_key = $_SERVER['SERVER_NAME'];
                #$app->token = $request->input("token");

		($app->save())? DLH::flash("App updated successfully", 'success'):
                    DLH::flash("Changes did not take effect", 'error');
                }
                else
                {
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

		return redirect()->route('app.index')->with('message', 'Item deleted successfully.');
	}

}
