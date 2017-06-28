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

        $services = Service::orderBy('created_at', 'desc')->get();
        $menuName = 'datatable';
        return view('datatable.index', compact('services', 'menuName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $service = \DB::table('services')->where('id', $id)->first();

        if ($service->driver != 'default') {
            $conn = \Config::get("database.connections.$service->driver");

            $conn['database'] = $service->database;
            $conn['host'] = $service->hostname;
            $conn['port'] = $service->port;
            $conn['username'] = $service->username;
            $conn['password'] = $service->password;

            \Session::set('DB_OTF', $conn);
        } else {
            \Session::forget('DB_OTF');
        }

        return \DB::table('table_metas')->where('service_id', $id)->get();
    }

    /**
     * Get Table Meta data.
     *
     * @param $table_name
     *
     * @return \Illuminate\Http\Response
     */
    public function metas($table_name)
    {
        $database = \Session::get('DB_OTF');
        $otf = new \App\Helpers\OTF([
                    'driver' => $database['driver'],
                    'host' => $database['host'],
                    'database' => $database['database'],
                    'username' => $database['username'],
                    'password' => $database['password'],
                    'port' => $database['port'],
            ]);
        if ($database['driver'] != null) {
            return $otf->getConnection()->getSchemaBuilder()
                            ->getColumnListing($table_name);
        }

        return \DB::getSchemaBuilder()->getColumnListing($table_name);
    }

    /**
     * Display the specified resource.
     *
     * @param $name
     *
     * @return \Illuminate\Http\Response
     *
     * @internal param int $id
     */
    public function entries($name)
    {
        $conn = \DB::connection()->getDatabaseName();

        $database = \Session::get('DB_OTF');

        if ($database['driver'] != null) {
            $otf = new \App\Helpers\OTF([
                'driver' => $database['driver'],
                'host' => $database['host'],
                'database' => $database['database'],
                'username' => $database['username'],
                'password' => $database['password'],
                'port' => $database['port'],
            ]);

            return $otf->getTable($name)->get();
        } elseif ($conn == 'database.sqlite3') {
            return \DB::connection('devless-rec')->table($name)
                    ->get();
        } else {
            return \DB::table($name)->get();
        }
    }
}
