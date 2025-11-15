<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SocialAuthController;
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

Route::get('/maintenances', 'MaintenanceController@getApi');
Route::post('/maintenances', 'MaintenanceController@storeApi');
Route::put('/maintenances/{maintenance}', 'MaintenanceController@updateApi');
Route::delete('/maintenances/{maintenance}', 'MaintenanceController@destroyApi');
Route::post('/maintenances/{maintenance}/complete', 'MaintenanceController@complete');

Route::post('/contractables/{contractable}/documents', 'ContractableAssetController@storeDocument');
Route::put('/contractables/{contractable}/documents/{document}', 'ContractableAssetController@updateDocument');
Route::delete('/contractables/{contractable}/documents/{document}', 'ContractableAssetController@destroyDocument');

Route::post('/contractables/{contractable}/accessoires', 'ContractableAssetController@storeAccessoire');
Route::put('/contractables/{contractable}/accessoires/{accessoire}', 'ContractableAssetController@updateAccessoire');
Route::delete('/contractables/{contractable}/accessoires/{accessoire}', 'ContractableAssetController@destroyAccessoire');

Route::get('/contractables', 'ContractableController@getApi');
Route::get('/contractables-full', 'ContractableController@getFullApi');
Route::get('/contractables/{contractable}/pannes', 'PanneController@forContractable');

Route::get('/reservations', 'ReservationApiController@index');
Route::post('/reservations', 'ReservationApiController@store');
Route::put('/reservations/{reservation}', 'ReservationApiController@update');
Route::delete('/reservations/{reservation}', 'ReservationApiController@destroy');
Route::post('/reservations/{reservation}/status', 'ReservationApiController@updateStatus');
Route::post('/reservations/{reservation}/convert', 'ReservationApiController@convert');

Route::get('/techniciens', 'TechnicienController@getApi');

Route::get('/clients', 'ClientApiController@index');
Route::post('/clients', 'ClientApiController@store');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('oauth')->group(function () {
    Route::get('{provider}/url', [SocialAuthController::class, 'redirectUrl']);
    Route::post('token', [SocialAuthController::class, 'exchangeToken']);
});
