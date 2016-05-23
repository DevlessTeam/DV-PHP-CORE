<?php namespace App\Http\Controllers;

use App\Service;
use App\TableMeta;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiDocController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$services = Service::all();
		return view('api_docs.index', compact('services'));
	}

	/**
	 *Get schema to generate request payload
	 *
	 * @return Response
	 */
	public function schema($table_name)
	{
		$schema_data = \DB::table('table_metas')->where('table_name', $table_name)->first();
		$schema = json_decode($schema_data->schema);
	 	return $schema->field;
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
		$tables = \DB::table('table_metas')->where('service_id', $id)->get();
		return $tables;
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
