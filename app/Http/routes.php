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
Route::get('/', 'UserController@get_login');

Route::post('login', 'UserController@post_login');
Route::get('logout', 'UserController@get_logout');

Route::get('setup', 'UserController@get_register');
Route::post('setup', 'UserController@post_register');

#lean views route
Route::resource('service/{service}/{resource}/{template}/', 'ViewController@access_views');


Route::get('service_views/{sublevels?}', 'ViewController@static_files')->where('sublevels', '.*');

#routes for only endpoints
Route::group(['prefix' => 'api/v1','middleware' => 'cors'], function () {

    #check system status
    Route::get('status', function ()    {
        
        return ['status_code'=>111, 'message'=>'healthy','payload'=>[]];
    });

    #system logs
    Route::get('log', function ()    {
        return "no log available";
    });

    #service end points
    Route::get('service/{service}/{resource}','ServiceController@api');
    Route::post('service/{service}/{resource}','ServiceController@api');
    Route::patch('service/{service}/{resource}','ServiceController@api');
    Route::delete('service/{service}/{resource}','ServiceController@api');

});

#routes available to only login users
Route::group(['middleware' => 'user.auth'], function () {
  #service views
  Route::resource('services','ServiceController');

 #change token
Route::patch('generatetoken','AppController@token');
  
  #app views
  Route::resource('app','AppController');

  
  #destroy table 
  #Route::delete('destroy_table', 'SystemApiController@delete_table');

  #api_doc views
  Route::get('console', 'ApiDocController@index');
  Route::get('console/{console?}', 'ApiDocController@edit');
  Route::get('console/{schema?}/schema', 'ApiDocController@schema');
  Route::get('console/{script?}/script', 'ApiDocController@script');

  #privacy
  Route::get('privacy', 'PrivacyController@index');
  Route::get('privacy/{serviceId?}/get', 'PrivacyController@show');
  Route::put('privacy/{privacy}', 'PrivacyController@update');

  #Service Migrator
  Route::resource("migrate","ServiceMigrationController");



  #Download Route
  Route::get('download/{filename}', 'ServiceController@download_service_package')
    ->where('filename', '[A-Za-z0-9\-\_\.]+');

  #Datatable Route
  Route::get('datatable', 'DatatableController@index');
  Route::get('datatable/{datatable?}', 'DatatableController@create');
  Route::get('datatable/{entries?}/entries', 'DatatableController@show');



});
