<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is wordwrap(str)here you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', 'UserController@getLogin');

Route::post('login', 'UserController@postLogin');
Route::get('logout', 'UserController@getLogout');

Route::get('setup', 'UserController@getRegister');
Route::post('setup', 'UserController@postRegister');

#views route
Route::resource('service/{service}/{resource}/{template}/', 'ViewController@access_views');


Route::get('assets/{sublevels?}', 'ViewController@static_files')->where('sublevels', '.*');

#routes for only endpoints
Route::group(['prefix' => 'api/v1','middleware' => 'cors'], function () {

    #check system status
    Route::get('status', function ()    {

        return "healthy";
    });

    #system logs
    Route::get('log', function ()    {
        return "logs from here";
    })->middleware(['jsonValidator']);

    #config end points
    Route::resource('system', 'ServiceController@api');

    #service end points
    Route::get('service/{service}/{resource}','ServiceController@api');
    Route::post('service/{service}/{resource}','ServiceController@api');
    Route::patch('service/{service}/{resource}','ServiceController@api');
    Route::delete('service/{service}/{resource}','ServiceController@api');

    #authentication 
    Route::post('authentication/signup', 'AuthenticationController@signup');
    Route::post('authentication/login', 'AuthenticationController@login');
    Route::patch('authentication/user', 'AuthenticationController@profile');
    Route::delete('authentication/user', 'AuthenticationController@delete');
});

Route::group(['middleware' => 'user.auth'], function () {
  #service views
  Route::resource('services','ServiceController');

  #app views
  Route::resource("app",'AppController');

  #change token
  Route::put('generatetoken','AppController@update');
  Route::delete('destroy_table', 'SystemApiController@delete_table');

  #api_doc views
  Route::get('console', 'ApiDocController@index');
  Route::get('console/privacy', 'ApiDocController@privacy');
  Route::get('console/{serviceId?}/get', 'ApiDocController@privacy_chanage');
  Route::put('console/{privacy}', 'ApiDocController@persist_privacy');
  Route::get('console/{console?}', 'ApiDocController@edit');
  Route::get('console/{schema?}/schema', 'ApiDocController@schema');
  Route::get('console/{script?}/script', 'ApiDocController@script');

  #Service MIgrator
  Route::resource("migrate","ServiceMigrationController");



  #Download Route
  Route::get('download/{filename}', 'ServiceController@download_service_package')
    ->where('filename', '[A-Za-z0-9\-\_\.]+');

  #Datatable Route
  Route::get('datatable', 'DatatableController@index');
  Route::get('datatable/{datatable?}', 'DatatableController@create');
  Route::get('datatable/{entries?}/entries', 'DatatableController@show');
  
  

});
