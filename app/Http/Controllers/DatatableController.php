<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;

class DatatableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->service_name && $request->table_name) {
            $service = \DB::table('services')->where('name', $request->service_name)->first();
            $tables = \DB::table('table_metas')->where('service_id', $service->id)->get();

            return view('datatable.index', compact('service', 'tables'));
        }
        $services = Service::all();

        return view('datatable.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return \DB::table('table_metas')->where('service_id', $id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($table_name)
    {
        return \DB::getSchemaBuilder()->getColumnListing($table_name);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $conn = \DB::connection()->getDatabaseName();
        if ($conn == 'database.sqlite3') {
            return \DB::connection('devless-rec')->table($name)->paginate(10);
        } else {
            return \DB::table($name)->paginate(10);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
