<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// VOITURES
Route::get('/voitures', 'VoitureController@index');
Route::get('/voiture/{voiture}', 'VoitureController@show');
Route::post( '/voiture/reception', 'VoitureController@reception');
// Route::get('/voiture/{voiture}/reception', 'VoitureController@reception');
Route::get('/voiture/{voiture}/maintenance', 'VoitureController@maintenance');

// CLIENTS
Route::get('/clients', 'ClientController@index');
Route::get('/clients/{client}', 'ClientController@show');

// CONTRATS

Route::get('/contrats/menu', 'ContratController@menu');
Route::get('/contrats', 'ContratController@index');
Route::get('/contrats/create', 'ContratController@create');
Route::get('/contrat/{contrat}', 'ContratController@show');
Route::get('/contrat/{contrat}/voir-uploads', 'ContratController@voirUploads');
Route::post('/contrats/{contrat}/ajoute-photos', 'ContratController@ajoutePhotos')->where('contrat', '[0-9]+' );
Route::post('/contrats/{contrat}/update-cashier', 'ContratController@updateCashier')->where( 'contrat', '[0-9]+');
Route::post('/contrats/store', 'ContratController@store');
