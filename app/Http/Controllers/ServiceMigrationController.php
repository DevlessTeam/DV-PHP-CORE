<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ServiceMigration;
use Illuminate\Http\Request;

class ServiceMigrationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$service_migrations = ServiceMigration::orderBy('id', 'desc')->paginate(10);

		return view('service_migrations.index', compact('service_migrations'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('service_migrations.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$service_migration = new ServiceMigration();

		$service_migration->service_name = $request->input("service_name");

		$service_migration->save();

		return redirect()->route('service_migrations.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$service_migration = ServiceMigration::findOrFail($id);

		return view('service_migrations.show', compact('service_migration'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$service_migration = ServiceMigration::findOrFail($id);

		return view('service_migrations.edit', compact('service_migration'));
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
		$service_migration = ServiceMigration::findOrFail($id);

		$service_migration->service_name = $request->input("service_name");

		$service_migration->save();

		return redirect()->route('service_migrations.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$service_migration = ServiceMigration::findOrFail($id);
		$service_migration->delete();

		return redirect()->route('service_migrations.index')->with('message', 'Item deleted successfully.');
	}

}
