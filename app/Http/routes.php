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

Route::get('/', function () {
    # $exitCode = Artisan::call('command:name', ['--option' => 'foo']);
    return 'welcome';
});

Route::group(['prefix' => 'api/v1'], function () {
	#check system status 
    Route::get('status', function ()    {
        // Matches The "/api/v1/us" URL
        return "healthy";
    });

    #view system logs 
    Route::get('log', function ()    {
        // Matches The "/api/v1/us" URL
        return "logs from here";
    });

    #schema end point  
    Route::resource('schema','schemaController');
  
    #app end points  
    Route::resource('app','appController');

     #service end points  
    Route::resource('service','serviceController');
    
    #config end points
    Route::resource('config', 'configController');
    
    
});
Route::get('/sql', 'sqldb@store');

Route::get('/json', 'jsonValidatorController@index');



