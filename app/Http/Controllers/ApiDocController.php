<?php namespace App\Http\Controllers;

use App\Service;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Helpers\DevlessHelper as DLH;

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
	public function script($service_name)
	{
		$script = \DB::table('services')->where('name', $service_name)->value('script');
		return $script;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function privacy()
	{
		$services = Service::all();
		return view('api_docs.show', compact('services'));
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
	public function privacy_chanage($id)
	{
		$service = Service::findOrFail($id);
		return $service->resource_access_right;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function persist_privacy(Request $request, $id)
	{
		$service = Service::findOrFail($id);
		$json = array(
			'query' =>	$request->input('query'),
			'create' =>	$request->input('create'),
			'update' =>	$request->input('update'),
			'delete' =>	$request->input('delete'),
			'schema' =>	$request->input('schema'),
			'script' =>	$request->input('script')
		);
		$service->resource_access_right = json_encode($json);
		if ($service->save()) {
			DLH::flash('Access Rights Updated Successfully', 'success');
		} else {
			DLH::flash('Error Updating Access Rights', 'error');
		}
		return back();
	}
}
