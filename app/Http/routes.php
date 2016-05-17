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
    return 'welcome to devless (login soon comes here)';
});

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
    Route::resource('system', 'serviceController@api');
    
     #service end points  
    Route::get('service/{service}/{resource}','serviceController@api');
    Route::post('service/{service}/{resource}','serviceController@api');
    Route::patch('service/{service}/{resource}','serviceController@api');
    Route::delete('service/{service}/{resource}','serviceController@api');
    
    
    
    
});
     #service views  
    Route::resource('services','serviceController');
    
    
    #app views 
    Route::resource("app",'AppController');
    
    #change token
    Route::put('generatetoken','AppController@update');
    


