<?php

use Illuminate\Http\Request;
use App\Contrat;
use App\Voiture;
use App\Client;
use GuzzleHttp\Client as Gzclient;
use Illuminate\Support\Facades\Auth;
use App\Document;
use App\Accessoire;
use App\Panne;
use App\Maintenance;
use Carbon\Carbon;
use App\Technicien;

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
Auth::loginUsingID(1);
Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    

    Route::get('/', 'HomeController@welcome');
    Route::get('/home', 'HomeController@index')->name('home');

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

    // VOITURES
    Route::get('/voitures', 'VoitureController@index');
    Route::get('/voiture/{voiture}', 'VoitureController@show');
    Route::post( '/voiture/reception', 'VoitureController@reception');
    // Route::get('/voiture/{voiture}/reception', 'VoitureController@reception');
    Route::get('/voiture/{voiture}/maintenance', 'VoitureController@maintenance');
    Route::post('/voitures/ajout-voiture', 'VoitureController@store');


    // CLIENTS
    Route::get('/clients', 'ClientController@index');
    Route::get('/clients/{client}', 'ClientController@show');
    Route::post( '/clients/ajout-client', 'ClientController@store');
    Route::get('/clients/{client}/edit', 'ClientController@edit');
    Route::post('/clients/{client}/update', 'ClientController@update');

    // CONTRATS
    Route::get('/contrats/menu', 'ContratController@menu');
    Route::get('/contrats', 'ContratController@index');
    Route::get('/contrats/create', 'ContratController@create');
    Route::get('/contrat/{contrat}', 'ContratController@show');
    Route::get('/contrat/{contrat}/voir-uploads', 'ContratController@voirUploads');
    Route::post('/contrats/{contrat}/ajoute-photos', 'ContratController@ajoutePhotos')->where('contrat', '[0-9]+' );
    Route::post('/contrats/{contrat}/update-cashier', 'ContratController@updateCashier')->where( 'contrat', '[0-9]+');
    Route::post('/contrats/store', 'ContratController@store');
    Route::post( '/contrat/{contrat}/update-cashier-id', 'ContratController@updateCashierId');
    Route::post('/contrats/{contrat}/prolonger', 'ContratController@prolonger');
    Route::post('/contrats/{contrat}/changer-voiture', 'ContratController@changerVoiture');

    // Paramètres
    Route::get('/mes-paramètres', function(){
        $documents = Document::all();
        $accessoires = Accessoire::all();
        $voitures = Voiture::with('documents', 'accessoires')->get();
        $techniciens = Technicien::where('compagnie_id', Auth::user()->compagnie_id)->get();
        return view( 'paramètres.index', compact('documents', 'accessoires', 'voitures', 'techniciens'));
    });


    Route::post('/documents', function(Request $request){
        $document = Document::create([
            'type' => $request->type
        ]);

        if($document){
            return redirect('/mes-paramètres');
        }
    });
    Route::post( '/documents/{document}/destroy', function(Document $document){
        $deleted = $document->delete();
        if ( $deleted )
            return redirect('/mes-paramètres');
    });
    Route::post('/documents/{document}/update', function(Document $document, Request $request){
        $updated = $document->update([
            'type' => $request->type
        ]);
        if ($updated)
            return redirect('/mes-paramètres');
    });


    Route::post('/accessoires', function (Request $request) {
        $accessoire = Accessoire::create([
            'type' => $request->type
        ]);
        if ( $accessoire)
            return redirect('/mes-paramètres');
    });
    Route::post('/accessoires/{accessoire}/destroy', function (Accessoire $accessoire) {
        $deleted = $accessoire->delete();
        if ($deleted)
            return redirect('/mes-paramètres');
    });
    Route::post('/accessoires/{accessoire}/update', function (Accessoire $accessoire, Request $request) {
        $updated = $accessoire->update([
            'type' => $request->type
        ]);
        if ($updated)
            return redirect('/mes-paramètres');
    });
    Route::post( '/{voiture}/voiture-documents-accessoires', function(Request $request, Voiture $voiture){
        $documents = Document::all();
        $docKeys = [];
        $accessoires = Accessoire::all();
        $accKeys = [];
        foreach ($documents as  $document) {
            array_push($docKeys, str_replace(' ', '', $document->type));
        }
        foreach ( $accessoires as  $accessoire) {
            array_push( $accKeys, str_replace(' ', '', $accessoire->type));
        }
        // return $request;
        for($i = 0; $i < sizeof($documents); $i++){
            if(isset( $request[ $docKeys[$i] ]) && isset( $request['date' . $docKeys[$i]])){
                DB::table('voiture_documents')->updateOrInsert(
                    ['voiture_id' => $voiture->id, 'document_id' => $request[$docKeys[$i]]],
                    [ 'voiture_id' => $voiture->id, 'document_id' => $request[$docKeys[$i]], 'date_expiration' => $request['date' . $docKeys[$i]]]
                );
            } else {
                DB::table('voiture_documents')->where(['voiture_id' => $voiture->id, 'document_id' => $documents[$i]->id ])->delete();
            }
        }

        for($i = 0; $i < sizeof($accessoires); $i++){
            if(isset( $request[ $accKeys[$i]]) && isset( $request['quantité' . $accKeys [$i]] )) {
                DB::table('voiture_accessoires')->updateOrInsert(
                    [ 'voiture_id' => $voiture->id, 'accessoire_id' => $request[ $accKeys[$i]] ],
                    [ 'voiture_id' => $voiture->id, 'accessoire_id' => $request[ $accKeys[$i]], 'quantité' => $request[ 'quantité' . $accKeys[$i]]]
                );
            } else {
                DB::table( 'voiture_accessoires')->where(['voiture_id' => $voiture->id, 'accessoire_id' => $accessoires[$i]->id ])->delete();
            }
        }
        return redirect()->back();
        
        
        
    });

    // PANNES
    Route::post('/voitures/{voiture}/ajoute-pannes', 'PanneController@store');

    // Maintenances 
    Route::get('maintenances', 'MaintenanceController@index');
    Route::post('/maintenances/store', 'MaintenanceController@store');
    Route::post('/maintenances/{maintenance}/reception-véhicule', function(Request $request, Maintenance $maintenance) {
        // return $request->all();
        foreach ($request->pannes as $panne) {
            $panne = Panne::find($panne);
            $panne->update([
                'etat' => 'résolue'
            ]);
        }
        $maintenance->update([
            'coût' => $request->montant,
            'coût_pièces' => $request->pièces_détachées
        ]);
        $maintenance->loadMissing('voiture');

        $maintenance->voiture->etat('disponible');
        
        return redirect()->back();
    });
    Route::get('/reporting/voitures', function(){
        $voitures = Voiture::with(['contrats', 'maintenances'])->get();

        $contrats =  Contrat::all()->sortBy('created_at')->groupBy(function ($contrat){
            return Carbon::parse($contrat->created_at)->format('m');
        });
        $contrat_ordered_by_month = [];
        for($i = 1; $i < 13; $i++){
            if($i < 10){
                $j = '0' . $i;
            }
            if(isset( $contrats[$j])){
                array_push($contrat_ordered_by_month, $contrats[$j]);
            }
        }
        $chiffre_DAffaire_Annuel = [];
        foreach ( $contrat_ordered_by_month as $contrats_in_month) {
            $chiffre_DAffaire_Mensuel = 0;
            foreach($contrats_in_month as $contrat){
                $chiffre_DAffaire_Mensuel += $contrat->total;
            }
            array_push($chiffre_DAffaire_Annuel, $chiffre_DAffaire_Mensuel);
        }
        $chiffre_DAffaire_Annuel;

        return view('reporting.voitures', compact('voitures', 'chiffre_DAffaire_Annuel'));
    });
    Route::get('/reporting', function(){
        $contrats = Contrat::all();
        return view('reporting.index', compact('contrats'));
    });

    // COMPAGNIES

    Route::view('/compagnies/create', 'compagnies.create');

    // Techniciens
    Route::post('techniciens', 'TechnicienController@store');
});