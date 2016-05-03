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
    return 'welcome to devless (login soon comes here)';
});

Route::group(['prefix' => 'api/v1'], function () {
	#check system status 
    Route::get('status', function ()    {
        // Matches The "/api/v1/us" URL
        return "healthy";
    });

    #view system logs 
    Route::get('log', function ()    {
        return "logs from here";
    })->middleware(['jsonValidator']);

    #schema end points  
    Route::post('schema','schemaController@store');
  
   
    
    #config end points
    Route::resource('config', 'configController');
    
     #service end points  
    Route::get('service/{service}/{resource}','serviceController@resource');
    Route::post('service/{service}/{resource}','serviceController@resource');
    Route::patch('service/{service}/{resource}','serviceController@resource');
    Route::delete('service/{service}/{resource}','serviceController@resource');
    
    
    
    
});
     #service views  
    Route::resource('services','serviceController');
    
    #app views 
    Route::resource("apps","AppController");
    


