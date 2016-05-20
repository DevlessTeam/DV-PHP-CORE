<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ApiDoc;
use Illuminate\Http\Request;

class ApiDocController extends Controller {

	public function __construct()
	{
		$this->middleware('user.auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		return view('api_docs.index', compact('api_docs'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('api_docs.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		// $api_doc = new ApiDoc();
			dd($request);


		// $api_doc->save();

		// return redirect()->route('api_docs.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$api_doc = ApiDoc::findOrFail($id);

		return view('api_docs.show', compact('api_doc'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$api_doc = ApiDoc::findOrFail($id);

		return view('api_docs.edit', compact('api_doc'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$api_doc = ApiDoc::findOrFail($id);



		$api_doc->save();

		return redirect()->route('api_docs.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$api_doc = ApiDoc::findOrFail($id);
		$api_doc->delete();

		return redirect()->route('api_docs.index')->with('message', 'Item deleted successfully.');
	}

}
