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


    Route::get('/service_assets', 'ServiceController@service_views');

    Route::post('login', 'UserController@post_login');
    Route::get('logout', 'UserController@get_logout');

    Route::get('setup', 'UserController@get_register');
    Route::post('setup', 'UserController@post_register');

//lean views route
    Route::resource('service/{service}/{resource}/{template}/', 'ViewController@access_views');


    Route::get(config('devless')['assets_route_name'].'/{sublevels?}', 'ViewController@static_files')->where('sublevels', '.*');

//routes for only endpoints
    Route::group(
        ['prefix' => 'api/v1', 'middleware' => 'cors'],
        function () {

            //check system status
            Route::get(
                'status',
                function () {
                    return ['status_code' => 111, 'message' => 'healthy', 'payload' => []];
                }
            );

            //system logs
            Route::get(
                'log',
                function () {
                    return 'no log available';
                }
            );


            //service end points
            Route::get('service/{service}/{resource}', 'ServiceController@service');
            Route::post('service/{service}/{resource}', 'ServiceController@service');
            Route::patch('service/{service}/{resource}', 'ServiceController@service');
            Route::delete('service/{service}/{resource}', 'ServiceController@service');
        }
    );

    //routes available to only login users
    Route::group(
        ['middleware' => 'user.auth'],
        function () {

            //get list of users
            Route::get('devless_users', 'UserController@get_all_users');
            Route::get('retrieve_users', 'UserController@retrieve_all_users');

            //service views
            Route::resource('services', 'ServiceController');

            //change token
            Route::patch('generatetoken', 'AppController@token');

            //install service
            Route::get('install_service', 'HubController@get_service');

            //app views
            Route::resource('app', 'AppController');


            //destroy table
            //Route::delete('destroy_table', 'SystemApiController@delete_table');

            //api_doc views
            Route::get('console', 'ApiDocController@index');
            Route::get('console/{console?}', 'ApiDocController@edit');
            Route::get('console/{service_id?}/{service_name?}/{table?}', 'ApiDocController@schema');

            //privacy
            Route::get('privacy', 'PrivacyController@index');
            Route::get('privacy/{serviceId?}/get', 'PrivacyController@show');
            Route::put('privacy/{privacy}', 'PrivacyController@update');

            //Service Migration
            Route::resource('migrate', 'ServiceMigrationController');

            //Hub
            Route::resource('hub', 'HubController@index');
            Route::resource('get-service', 'HubController@getService');


            //Download Route
            Route::get('download/{filename}', 'ServiceController@download_service_package')
            ->where('filename', '[A-Za-z0-9\-\_\.]+');

            //Datatable Route
            Route::get('datatable', 'DatatableController@index');
            Route::get('datatable/{datatable?}', 'DatatableController@create');
            Route::get('datatable/{entries?}/entries', 'DatatableController@entries');
            Route::get('datatable/{entries?}/metas', 'DatatableController@metas');

            //Misc Route
            Route::get('edit-table/{action}/{service}/{table}/{params}', 'ServiceController@editTable');
        }
    );
