<?php

namespace App\Http\Controllers;

use App\Helpers\DevlessHelper as DLH;
use App\Service;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->get();

        return view('privacy.index', compact('services'));
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);

        return $service->resource_access_right;
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
        if ($id == 0) {
            DLH::flash('Error Updating Access Rights', 'error');

            return back();
        }
        $service = Service::findOrFail($id);
        $json = [
        'query'  => $request->input('query'),
        'create' => $request->input('create'),
        'update' => $request->input('update'),
        'delete' => $request->input('delete'),
        'schema' => $request->input('schema'),
        'script' => $request->input('script'),
        'view'   => $request->input('view'),
        ];
        $service->resource_access_right = json_encode($json);
        if ($service->save()) {
            DLH::flash('Access Rights Updated Successfully', 'success');
        } else {
            DLH::flash('Error Updating Access Rights', 'error');
        }

        return back();
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
