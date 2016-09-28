<?php
namespace App\Http\Controllers;
use App\Service;
use App\Http\Requests;
use \App\Helpers\Migration;
use Illuminate\Http\Request;
use App\Helpers\DevlessHelper as DLH;
use App\Http\Controllers\Controller;
class HubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::orderBy('id', 'desc')->paginate(10);
        return view('hub.index', compact('services'));
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function getService()
    {
        $serviceName = 'imageupload.srv';
        $service = file_get_contents('https://store.devless.io/download/imageupload.srv');
        $status = file_put_contents(storage_path().'/'.$serviceName, $service);
        
        if ($status) {
            $status = Migration::import_service($serviceName);
            ($status)? DLH::flash('Service has been installed', 'success'):
                false;
        } else {
            DLH::flash('Sorry Service or package could not be downloaded', 'error');
        }
    }
}
