<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ServiceMigration;
use App\Service;
use App\App;
use Illuminate\Http\Request;
use App\Helpers\Migration as Migration;
use \App\Helpers\DevlessHelper as DLH;

class ServiceMigrationController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index()
    {

                
                $services = Service::orderBy('id', 'desc')->get();
                $app = App::first();
                
        return view('service_migrations.index', compact('services'))->with('app', $app);
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
    * export or import services
    *
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
                    $zipped_service_name = "";
                    $migration_type = $request->input('io_type');
                    
        if ($migration_type == "import") {
                    $zipped_file_name = "";
                            
                    
            if ($request->file('service_file')->isValid()) {
                $service_archive_object =$request->file('service_file');
                                
                $service_package_name = $service_archive_object->getClientOriginalName();
                                
                if (!file_exists(storage_path().'/'.$service_package_name)) {
                    if ($service_archive_object->move(storage_path(), $service_package_name)) {
                        $import_state = Migration::import_service($service_package_name);
                           ($import_state)? DLH::flash("Service installed successfully", 'success'):
                           DLH::flash("Sorry service could not be installed", 'error');
                    }
                } else {
                    DLH::flash("Service seems to already exist or in storage", 'error');
                }
            } else {
                DLH::flash("Service could not be uploaded", 'error');
            }
        } elseif ($migration_type == "export") {
            if ($request->input('service_name') == "*") {
                $app_name = $request->input('app_name');
                $zipped_service_name = Migration::export_app($app_name);
            } else {
                $service_name  = $request->input('service_name');
                $zipped_service_name = Migration::export_service($service_name);
            }
        } else {
              DLH::flash("No appropriate action found", 'error');
        }

            
                

               
               
                    return redirect()->route('migrate.index')->with('package', $zipped_service_name);
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
