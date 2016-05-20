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

Route::group(['prefix' => 'api/v1','middleware' => 'cors'], function () {
	#check system status
    Route::get('status', function ()    {
        // Matches The "/api/v1/us" URL
        return "healthy";
    });

    #view system logs
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

    #api_doc views
    Route::resource('console', 'ApiDocController');



});

Route::group(['middleware' => 'user.auth'], function () {
  #service views
  Route::resource('services','ServiceController');

  #app views
  Route::resource("app",'AppController');

  #change token
  Route::put('generatetoken','AppController@update');
  Route::delete('destroy_table', 'SystemApiController@delete_table');


});
