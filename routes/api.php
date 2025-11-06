<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/pannes', 'PanneController@storeApi');
Route::put('/panne/{panne}', 'PanneController@updateApi');
Route::delete('/panne/{panne}', 'PanneController@destroy');

Route::post('/associer-document', 'DocumentController@associerDocument');

Route::get('/maintenances', 'MaintenanceController@getApi');

Route::post('/maintenances', 'MaintenanceController@storeApi');
Route::put('/maintenance/{maintenance}', 'MaintenanceController@updateApi');
Route::delete('/maintenance/{maintenance}', 'MaintenanceController@destroy');

Route::get('/contractables', 'ContractableController@getApi');

Route::get('/techniciens', 'TechnicienController@getApi');
