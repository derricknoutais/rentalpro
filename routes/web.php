<?php

use App\Offre;
use App\Panne;
use App\Client;
use App\Contrat;
use App\Voiture;
use App\Document;
use App\Paiement;
use Carbon\Carbon;
use App\Accessoire;
use App\Technicien;
use App\Maintenance;
use App\Contractable;
use App\Events\ContratCree;
use App\Jobs\CreateMetricEntries;
use App\Jobs\MetricCrawler;
use App\Metric;
use App\Reporting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;


// if (env('APP_ENV') == 'local')
//     Auth::loginUsingID(1);

Route::get('/test', function (Request $request) {

    if ($request['type'] === 'annee') {
        $data = Metric::where('type', 'annee')->orderBy('annee', 'desc')->take(4)->pluck($request['data_requested'], 'annee');
    } elseif ($request['type'] === 'mois') {
        $data =  Metric::where(['type' => 'mois'])->orderBy('annee', 'desc')->orderBy('mois', 'desc')->take(4)->pluck($request['data_requested'], 'mois_label');
    } elseif ($request['type'] === 'jour') {
        $data = Metric::where(['type' => 'jour'])->orderBy('annee', 'desc')->orderBy('mois', 'desc')->orderBy('jour', 'desc')->take(7)->pluck($request['data_requested'], 'jour_semaine_court');
    }
    return [
        'data' => $data->reverse()->values(),
        'labels' => $data->reverse()->keys()
    ];
});


Route::get('/t', function () {
    return view('');
    // // CreateMetricEntries::dispatch();
    // Metric::query()->delete();
    // MetricCrawler::dispatch(Contrat::where('id', '<', 500)->get());
    // // Metric::query()->delete();
    // return Metric::all();
});
Auth::routes();

Route::get('/add-offers', function () {
    Offre::create([
        'compagnie_id' => 2,
        'nom' => 'H24 Classique',
        'montant' => 20000
    ]);
    Offre::create([
        'compagnie_id' => 2,
        'nom' => 'H24 VIP',
        'montant' => 35000
    ]);
    Offre::create([
        'compagnie_id' => 2,
        'nom' => 'Nuitee Classique',
        'montant' => 15000
    ]);
    Offre::create([
        'compagnie_id' => 2,
        'nom' => 'Nuitee VIP',
        'montant' => 20000
    ]);
    Offre::create([
        'compagnie_id' => 2,
        'nom' => 'Detente',
        'montant' => 10000
    ]);
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/role-et-permissions', function () {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'gérant']);
        $role3 = Role::create(['name' => 'basique']);

        $permission0 = Permission::create(['name' => 'terminer contrat']);
        $permission1 = Permission::create(['name' => 'créer contrat']);
        $permission2 = Permission::create(['name' => 'lire contrats']);
        $permission3 = Permission::create(['name' => 'editer contrat']);
        $permission4 = Permission::create(['name' => 'supprimer contrat']);
        $permission5 = Permission::create(['name' => 'annuler contrat']);
        $permission6 = Permission::create(['name' => 'prolonger contrat']);
        $permission7 = Permission::create(['name' => 'créer paiement']);
        $permission8 = Permission::create(['name' => 'lire paiements']);
        $permission9 = Permission::create(['name' => 'editer paiements']);
        $permission10 = Permission::create(['name' => 'supprimer paiements']);
        $permission11 = Permission::create(['name' => 'annuler paiements']);
        $permission12 = Permission::create(['name' => 'créer client']);
        $permission13 = Permission::create(['name' => 'lire clients']);
        $permission14 = Permission::create(['name' => 'editer client']);
        $permission15 = Permission::create(['name' => 'supprimer client']);

        $role1->syncPermissions(
            $permission0,
            $permission1,
            $permission2,
            $permission3,
            $permission4,
            $permission5,
            $permission6,
            $permission7,
            $permission8,
            $permission9,
            $permission10,
            $permission11,
            $permission12,
            $permission13,
            $permission14,
            $permission15
        );
        $role2->syncPermissions(
            $permission0,
            $permission1,
            $permission2,
            $permission3,
            $permission5,
            $permission6,
            $permission7,
            $permission8,
            $permission9,
            $permission11,
            $permission12,
            $permission13,
            $permission14,
            $permission15
        );
        $role3->syncPermissions(
            $permission0,
            $permission1,
            $permission2,
            $permission6,
            $permission5,
            $permission7,
            $permission8,
            $permission11,
            $permission12,
            $permission13,
            $permission14
        );

        $user = Auth::user();
        App\User::find($user->id)->assignRole('admin');
        App\User::find(2)->assignRole('gérant');
    });
    Route::get('/', function () {
        return redirect('/dashboard');
    });
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/test-upload/{contrat}', function (Contrat $contrat) {
        return view('test', compact('contrat'));
    });
    Route::post('/upload', function (Request $request) {
        $array = ['cote_droit', 'cote_gauche', 'arriere', 'avant'];
        $liste_nom = [];
        foreach ($array as $cote) {
            if ($request->hasFile($cote)) {
                $image = $request->file($cote);
                $name = time() . uniqid() . '.' . $image->getClientOriginalExtension();
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
    Route::get('/contractables', 'ContractableController@index');
    Route::get('/contractables/{contractable_id}', 'ContractableController@show');
    Route::get('/voiture/{voiture}', 'VoitureController@show');
    Route::post('/voiture/reception', 'VoitureController@reception');
    // Route::get('/voiture/{voiture}/reception', 'VoitureController@reception');
    Route::get('/voiture/{voiture}/maintenance', 'VoitureController@maintenance');
    Route::post('/voitures/ajout-voiture', 'VoitureController@store');
    Route::post('/contractable/{contractable}/rendre-disponible', 'VoitureController@rendreDisponible');


    // CLIENTS
    Route::get('/clients', 'ClientController@index');
    Route::get('/clients/{client}', 'ClientController@show');
    Route::post('/clients/ajout-client', 'ClientController@store');
    Route::get('/clients/{client}/edit', 'ClientController@edit');
    Route::post('/clients/{client}/update', 'ClientController@update');
    Route::delete('/clients/{client}', 'ClientController@delete');

    //  CONTRATS
    Route::get('/contrats/menu', 'ContratController@menu');
    Route::get('/contrats', 'ContratController@index');
    Route::get('/contrats/create', 'ContratController@create');
    Route::get('/contrat/{contrat}', 'ContratController@show');

    Route::get('/contrat/{contrat}/download', 'ContratController@download');
    Route::post('/contrat/{contrat}/terminer', 'ContratController@terminer');
    Route::get('/contrat/{contrat}/voir-uploads', 'ContratController@voirUploads');
    Route::get('/contrat/{contrat}/edit', 'ContratController@edit');
    Route::put('/contrat/{contrat}/update', 'ContratController@update');
    Route::put('/contrat/{contrat}/update-all', 'ContratController@updateAll');
    Route::post('/contrats/{contrat}/ajoute-photos', 'ContratController@ajoutePhotos')->where('contrat', '[0-9]+');
    Route::post('/contrats/{contrat}/update-cashier', 'ContratController@updateCashier')->where('contrat', '[0-9]+');
    Route::post('/contrats/store', 'ContratController@store');

    // Le contrat rapide permet de créer un client et louer une chambre ou une voiture en meme temps
    Route::post('/contrats/store-contrat-rapide', 'ContratController@storeContratRapide');
    Route::post('/contrat/{contrat}/update-cashier-id', 'ContratController@updateCashierId');
    Route::post('/contrats/{contrat}/prolonger', 'ContratController@prolonger');
    Route::post('/contrats/{contrat}/changer-voiture', 'ContratController@changerVoiture');
    Route::get('/contrats/recherche', function (Request $request) {
        $contrats = Contrat::when($request, function ($query,  $request) {
            // Contrat
            if ($request->contrat) {
                $query->where('numéro', 'like', '%' . $request->contrat . '%');
            }
            // Client
            if ($request->client) {
                $clients = Client::where('nom', 'like', '%' .  $request->client . '%')
                    ->orWhere('prenom', 'like', '%' . $request->client . '%')
                    ->orWhere('phone1', 'like', '%' . $request->client . '%')
                    ->orWhere('phone2', 'like', '%' . $request->client . '%')
                    ->orWhere('phone3', 'like', '%' . $request->client . '%')
                    ->pluck('id');
                $query->whereIn('client_id', $clients);
            }
            // Date du
            if ($request->au) {
                $query->where('au', 'like',  $request->au . '%');
            }
            // Date au
            if ($request->du) {
                $query->where('du', 'like',  $request->du . '%');
            }
            // Etat
            if ($request->etat === 'en-cours') {
                $query->whereNull('real_check_out');
            } else if ($request->etat === 'terminé') {
                $query->whereNotNull('real_check_out');
            }
            return $query;
        })->orderBy('id', 'desc')->paginate(20);
        $voitures = Voiture::all();
        return view('contrats.index', compact(['contrats', 'voitures']));
    });
    Route::delete('/contrats/{contrat}', 'ContratController@destroy');
    Route::get('/contrat/{contrat}/envoyer-gescash', 'ContratController@envoyerContratGescash');
    Route::post('/contrat/{contrat}/ajouter-demi-journee', 'ContratController@ajouterDemiJournee');
    Route::post('/contrat/{contrat}/ajouter-montant-chauffeur', 'ContratController@ajouterMontantChauffeur');



    Route::resource('/image', 'ImageController');
    Route::delete('/image/{image}/client/{client}', 'ImageController@destroy');
    // Route::post('/image', 'ImageController@store');

    // Impression Contrat
    Route::get('/contrat/{contrat}/print', 'ContratController@print');
    Route::get('/contrat/{contrat}/print-{type_compagnie}-{size}', 'PrintController@print');


    //  Reservation

    Route::get('/reservations', 'ReservationController@index');
    Route::get('/reservations/create', 'ReservationController@create');
    Route::post('/reservations/store', 'ReservationController@store');
    // Paramètres
    Route::get('/mes-paramètres', function () {
        $documents = Auth::user()->documents;
        $accessoires = Auth::user()->accessoires;
        $voitures = Voiture::with('documents', 'accessoires')->get();
        $contractables = Auth::user()->contractables;
        $techniciens = Technicien::where('compagnie_id', Auth::user()->compagnie_id)->get();
        return view('paramètres.index', compact('documents', 'accessoires', 'contractables', 'techniciens'));
    });

    Route::get('/update-date-contrats', function () {
        $dates = Contrat::pluck('created_at', 'gescash_transaction_id')->toArray();
        Http::post(env('GESCASH_BASE_URL') . '/api/v1/transaction/update-date', $dates);
    });

    // Documents
    Route::post('/documents', function (Request $request) {
        $document = Document::create([
            'type' => $request->type,
            'compagnie_id' => Auth::user()->compagnie_id
        ]);

        if ($document) {
            return redirect('/mes-paramètres');
        }
    });
    Route::post('/documents/{document}/destroy', function (Document $document) {
        $deleted = $document->delete();
        if ($deleted)
            return redirect('/mes-paramètres');
    });
    Route::post('/documents/{document}/update', function (Document $document, Request $request) {
        $updated = $document->update([
            'type' => $request->type
        ]);
        if ($updated)
            return redirect('/mes-paramètres');
    });

    // Accessoires
    Route::post('/accessoires', function (Request $request) {
        $accessoire = Accessoire::create([
            'type' => $request->type,
            'compagnie_id' => Auth::user()->compagnie_id
        ]);
        if ($accessoire)
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
    Route::post('/{voiture}/voiture-documents-accessoires', function (Request $request, Voiture $voiture) {
        $documents = Document::all();
        $docKeys = [];
        $accessoires = Accessoire::all();
        $accKeys = [];
        foreach ($documents as  $document) {
            array_push($docKeys, str_replace(' ', '', $document->type));
        }
        foreach ($accessoires as  $accessoire) {
            array_push($accKeys, str_replace(' ', '', $accessoire->type));
        }
        // return $request;
        for ($i = 0; $i < sizeof($documents); $i++) {
            if (isset($request[$docKeys[$i]]) && isset($request['date' . $docKeys[$i]])) {
                DB::table('voiture_documents')->updateOrInsert(
                    ['voiture_id' => $voiture->id, 'document_id' => $request[$docKeys[$i]]],
                    ['voiture_id' => $voiture->id, 'document_id' => $request[$docKeys[$i]], 'date_expiration' => $request['date' . $docKeys[$i]]]
                );
            } else {
                DB::table('voiture_documents')->where(['voiture_id' => $voiture->id, 'document_id' => $documents[$i]->id])->delete();
            }
        }

        for ($i = 0; $i < sizeof($accessoires); $i++) {
            if (isset($request[$accKeys[$i]]) && isset($request['quantité' . $accKeys[$i]])) {
                DB::table('voiture_accessoires')->updateOrInsert(
                    ['voiture_id' => $voiture->id, 'accessoire_id' => $request[$accKeys[$i]]],
                    ['voiture_id' => $voiture->id, 'accessoire_id' => $request[$accKeys[$i]], 'quantité' => $request['quantité' . $accKeys[$i]]]
                );
            } else {
                DB::table('voiture_accessoires')->where(['voiture_id' => $voiture->id, 'accessoire_id' => $accessoires[$i]->id])->delete();
            }
        }
        return redirect()->back();
    });

    // Pannes
    Route::post('/voitures/{voiture}/ajoute-pannes', 'PanneController@store');
    Route::post('/pannes', 'PanneController@storeApi');

    // Maintenances
    Route::get('maintenances', 'MaintenanceController@index');
    Route::get('/maintenance/{maintenance}', 'MaintenanceController@show');
    Route::get('/maintenance/{maintenance}/edit', 'MaintenanceController@edit');
    Route::get('/maintenances/create', 'MaintenanceController@create');
    Route::get('/maintenances/{maintenance}/envoyer-gescash', 'MaintenanceController@envoyerMaintenanceGescash');
    Route::post('/maintenances/store', 'MaintenanceController@store');
    Route::post('/maintenances/{maintenance}/reception-véhicule', function (Request $request, Maintenance $maintenance) {
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
    Route::delete('/maintenances/{maintenance}', 'MaintenanceController@destroy');


    Route::get('/paiements-cashier', function () {
        $ids = Contrat::pluck('id')->toArray();
        $contrats = Contrat::all();
        $response = Http::post('https://cashier.azimuts.ga/api/get-payments', ['ids' => $ids]);
        $test = $response->json();
        foreach ($test as $paiement) {
            $contrat_id = $contrats->where('cashier_facture_id', $paiement['facture_id'])->first()['id'];

            if ($contrat_id)
                $paiements[] = [
                    'contrat_id' => $contrat_id,
                    'montant' => $paiement['montant'],
                    'note' => $paiement['note'],
                    'created_at' => $paiement['created_at'],
                    'updated_at' => $paiement['updated_at'],
                ];
        }
        DB::table('paiements')->insert($paiements);
        // return $test;
    });


    // Reporting
    Route::get('/reporting/voitures', function () {
        $voitures = Voiture::with(['contrats', 'contrats.paiements', 'maintenances'])->get();
        // return $voitures;
        $contrats =  Contrat::all()->sortBy('created_at')->groupBy(function ($contrat) {
            return Carbon::parse($contrat->created_at)->format('m');
        });
        $contrat_ordered_by_month = [];
        for ($i = 1; $i < 13; $i++) {
            if ($i < 10) {
                $j = '0' . $i;
            }
            if (isset($contrats[$j])) {
                array_push($contrat_ordered_by_month, $contrats[$j]);
            }
        }

        $chiffre_DAffaire_Annuel = [];

        foreach ($contrat_ordered_by_month as $contrats_in_month) {
            $chiffre_DAffaire_Mensuel = 0;
            foreach ($contrats_in_month as $contrat) {
                $chiffre_DAffaire_Mensuel += $contrat->total;
            }
            array_push($chiffre_DAffaire_Annuel, $chiffre_DAffaire_Mensuel);
        }
        $chiffre_DAffaire_Annuel;

        return view('reporting.index');

        return view('reporting.voitures', compact('voitures', 'chiffre_DAffaire_Annuel'));
    });
    Route::get('/dashboard',  'DashboardController@index');
    Route::get('/my-feeds', function () {
        $contrats = Contrat::all();
        foreach ($contrats as $contrat) {
            $contrat->start = $contrat->du;
            $contrat->end = $contrat->au;
        }
        return $contrats;
    });

    // COMPAGNIES
    Route::view('/compagnies/create', 'compagnies.create');


    // PAIEMENTS
    Route::resource('paiement', 'PaiementController');
    Route::get('/ghost', function () {
        return Paiement::find(168);
        foreach (Paiement::all() as $paiement) {
            if (!$paiement->contrat) {
                $paiement->delete();
            }
        }
    });

    // Techniciens
    Route::post('techniciens', 'TechnicienController@store');
});
