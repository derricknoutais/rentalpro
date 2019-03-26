<?php

use Illuminate\Http\Request;
use App\Contrat;
use App\Voiture;
use App\Client;

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
    $voitures = Voiture::all();
    return view('welcome', compact('voitures'));
});

Route::get('/test-upload/{contrat}', function(Contrat $contrat){
    return view('test', compact('contrat'));
});

Route::post('/upload', function(Request $request){
    $array = ['cote_droit', 'cote_gauche', 'arriere', 'avant'];
    $liste_nom = [];
    foreach ($array as $cote) {
        if ($request->hasFile( $cote )) {
            $image = $request->file( $cote );
            $name = time(). uniqid() . '.'.$image->getClientOriginalExtension();
            array_push($liste_nom, $name);
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);
            // $this->save();
        }
    }
    Contrat::find($request->contrat_id)->update([
        'lien_photo_droit' => $liste_nom[0],
        'lien_photo_gauche' => $liste_nom[1],
        'lien_photo_avant' => $liste_nom[2],
        'lien_photo_arriere' => $liste_nom[3],
    ]);

    return redirect('/contrat/' . $request->contrat_id);
     
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// VOITURES
Route::get('/voitures', 'VoitureController@index');
Route::get('/voiture/{voiture}', 'VoitureController@show');
Route::post( '/voiture/reception', 'VoitureController@reception');
// Route::get('/voiture/{voiture}/reception', 'VoitureController@reception');
Route::get('/voiture/{voiture}/maintenance', 'VoitureController@maintenance');
Route::post('/voitures/ajout-voiture', function(Request $request){

    $voiture = Voiture::create([
        'immatriculation' => $request->immatriculation,
        'chassis' => $request->numero_chassis,
        'annee' => $request->annee,
        'marque' => $request->marque,
        'type' => $request->type,
        'etat' => 'disponible',
        'prix' => $request->prix
    ]);

    return redirect('/voiture/' . $voiture->id);
});

// CLIENTS
Route::get('/clients', 'ClientController@index');
Route::get('/clients/{client}', 'ClientController@show');
Route::post( '/clients/ajout-client', function(Request $request){
    $client = Client::create([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'adresse' => $request->addresse,
        'numero_permis' => $request->numero_permis,
        'phone1' => $request->numero_telephone,
        'mail' => $request->mail,
        'ville' => $request->ville,
        'cashier_id' => $request->cashier_id
    ]);
    return $request->all();

    if($request->hasFile('permis')){
        $image = $request->file('permis');
        return $nom = time(). uniqid() . '.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/uploads');
        $image->move($destinationPath, $nom);
        $client->update([
            'permis' => $nom
        ]);
    }


});

// CONTRATS

Route::get('/contrats/menu', 'ContratController@menu');
Route::get('/contrats', 'ContratController@index');
Route::get('/contrats/create', 'ContratController@create');
Route::get('/contrat/{contrat}', 'ContratController@show');
Route::get('/contrat/{contrat}/voir-uploads', 'ContratController@voirUploads');
Route::post('/contrats/{contrat}/ajoute-photos', 'ContratController@ajoutePhotos')->where('contrat', '[0-9]+' );
Route::post('/contrats/{contrat}/update-cashier', 'ContratController@updateCashier')->where( 'contrat', '[0-9]+');
Route::post('/contrats/store', 'ContratController@store');
Route::post( '/contrat/{contrat}/update-cashier-id', function(Request $request, Contrat $contrat){
    $contrat->update([
        'cashier_facture_id' => $request->cashier_id
    ]);
});
