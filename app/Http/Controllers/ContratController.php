<?php

namespace App\Http\Controllers;

use App\Client;
use App\Chambre;
use App\Contrat;
use App\Voiture;
use App\Paiement;
use Carbon\Carbon;
use App\ApiSetting;
use App\Maintenance;
use NumberFormatter;
use App\Prolongation;
use PDF;
use App\Mail\ContratCréé;
use App\Events\ContratCree;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContratController extends Controller
{
    public function menu()
    {
        return view('contrats.menu');
    }

    public function index(Request $request)
    {
        $compagnie = Auth::user()->compagnie;
        $contrats_compagnie = Contrat::withTrashed()
            ->where('compagnie_id', $compagnie->id)
            ->latest()
            ->get();
        $query = Contrat::withTrashed()->whereIn('id', array_pluck($contrats_compagnie, 'id'));

        if (sizeof($request->all()) > 0) {
            if ($request->has('voiture') && $request->voiture !== null) {
                $query->where([
                    'contractable_id' => $request->voiture,
                    'contractable_type' => 'App\\Voiture',
                ]);
            }

            if ($request->has('chambre') && $request->chambre !== null) {
                $query->where([
                    'contractable_id' => $request->chambre,
                    'contractable_type' => 'App\\Chambre',
                ]);
            }

            if ($request->has('client') && $request->client !== null) {
                $query->where([
                    'client_id' => $request->client,
                ]);
            }
            if ($request->has('etat') && $request->etat !== null) {
                switch ($request->etat) {
                    case 'En cours':
                        $query->whereDate('du', '<=', today())->whereDate('au', '>=', today())->whereNull('real_check_out');
                        break;
                    case 'Terminé':
                        $query->whereNotNull('real_check_out');
                        break;
                    case 'Annulé':
                        $query->whereNotNull('deleted_at');
                        break;
                    case 'Soldé':
                        return 'En Traitement';
                        break;
                    case 'Non-Soldé':
                        return 'En Traitement';
                        break;

                    default:
                        # code...
                        break;
                }
            }
            if ($request->has('du') && $request->du !== null) {
                $query->whereDate('du', '>=', $request->du);
            }
            if ($request->has('au') && $request->au !== null) {
                $query->whereDate('au', '<=', $request->au);
            }
        }

        $contrats = $query->orderBy('updated_at', 'desc')->paginate(10);
        $contrats->loadMissing(['client', 'contractable', 'paiements']);
        $contrats->map(function ($contrat) {
            $activity = $contrat->activities->where('description', 'created')->first();
            if ($activity && $activity->causer) {
                $contrat->activity = $activity;
            }
            if ($contrat->contractable) {
                $contrat->contractable->nom = $contrat->contractable->nom();
            }
            if ($contrat->client) {
                $contrat->client->derniers_contrats = $contrat->client->troisDerniersContrats();
            }
        });
        if ($compagnie->type === 'véhicules') {
            $contractablesDisponibles = Voiture::all();
        } else {
            $contractablesDisponibles = Chambre::all();
        }

        $contrat = $contrats[0];
        $voitures = Voiture::all();
        $compagnie->isHotel() ? ($contractables = Chambre::all()) : ($contractables = Voiture::all());
        $contractables->map(function ($contractable) {
            return $contractable->nom = $contractable->nom();
        });
        // return $contractables;
        $clients = Client::all();
        $clients->map(function ($client) {
            $client->nom_complet = $client->nom . ' ' . $client->prenom;
        });
        // return $clients;
        // Activity Log
        return view('contrats.index', compact(['contrats', 'voitures', 'compagnie', 'clients', 'contractablesDisponibles', 'contractables']));
    }

    public function show(Contrat $contrat)
    {
        $contrat->loadMissing('client', 'contractable', 'compagnie', 'paiements', 'activities');
        // return $contrat->activities;
        return view('contrats.show', compact('contrat'));
    }

    public function create(Request $request)
    {
        // $clients->toArray();
        $compagnie = Auth::user()->compagnie;
        $clients = $compagnie->clients;
        $contrats = $compagnie;
        $offres = $compagnie->offres;
        $contractables = $compagnie->contractables->where('etat', 'disponible')->values();
        $client = Client::first();
        $contractable = $contractables->first();
        if ($request->has('client_id')) {
            $client = Client::find($request->client_id);
        } elseif ($request->has('contractable_id')) {
            $contractable = $contractables->find($request->contractable_id);
            if (!$contractable) {
                return "Erreur! Cette chambre n'est pas disponible";
            }
        }

        if (sizeof($contractables) > 0) {
            $contractables->toArray();
            // if( $compagnie->isHotel() ){
            //     return view('contrats.create-hotel', compact('clients', 'contractables', 'contrats', 'offres', 'compagnie'));
            // }
            return view('contrats.create', compact('clients', 'contractables', 'contrats', 'offres', 'compagnie', 'client', 'contractable'));
        } else {
            return view('contrats.create_no_cars');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->compagnie->type == 'véhicules') {
            $type = 'App\\Voiture';
        } elseif (Auth::user()->compagnie->type == 'hôtel') {
            $type = 'App\\Chambre';
        }
        date_default_timezone_set('Africa/Libreville');
        $contrat = DB::transaction(function () use ($request, $type) {
            $client = null;
            if (!$request->client_id) {
                $client = Client::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'adresse' => $request->addresse,
                    'compagnie_id' => Auth::user()->compagnie_id,
                    'numero_permis' => $request->numero_permis,
                    'phone1' => $request->numero_telephone,
                    'phone2' => $request->numero_telephone2,
                    'phone3' => $request->numero_telephone3,
                    'mail' => $request->mail,
                    'ville' => $request->ville,
                    'cashier_id' => $request->cashier_id,
                    'image_id' => $request->image_id,
                ]);

                $client_id = $client->id;
                $client->loadMissing('image');
                if ($client->image) {
                    $imageName = $client->nom . ' ' . $client->prenom . ' ' . $client->phone1;
                    Storage::disk('do_spaces')->rename('permis/' . $client->image->name, 'permis/' . $imageName);
                    $client->image->update(['name' => $imageName]);
                }
            } else {
                $client_id = $request->client_id;
            }
            // Pour gérer les contrats ouverts
            $au = $request['au'];
            if ($request['au'] == null) {
                $au = null;
            }
            $contrat = Contrat::create([
                'contractable_id' => $request['contractable'],
                'contractable_type' => $type,
                'client_id' => $client_id,
                'numéro' => Contrat::numéro(),
                'compagnie_id' => Auth::user()->compagnie->id,
                'offre_id' => $request['offre_id'],
                'du' => $request['du'],
                'au' => $request['au'],
                'real_check_out' => null,
                'prix_journalier' => $request['prix_journalier'],
                'caution' => $request['caution'],
                'demi_journee' => $request['demi_journee'],
                'montant_chauffeur' => $request['montant_chauffeur'],
                'type_caution' => $request['type_caution'],
                'nombre_jours' => $request['nombre_jours'],
                'total' => $request['prix_journalier'] * $request['nombre_jours'] + $request['demi_journee'] + $request['montant_chauffeur'],
                'cashier_facture_id' => $request['cashier_id'],
                'etat_accessoires' => $request['accessoireString'],
                'etat_documents' => $request['documentString'],
                'note' => $request['note_contrat'],
            ]);
            if (Auth::user()->compagnie->isHotel()) {
                Chambre::find($request['contractable'])->update([
                    'etat' => 'loué',
                ]);
            } else {
                $voiture = Voiture::find($request['contractable'])->update([
                    'etat' => 'loué',
                ]);
            }

            if ($contrat) {
                return $contrat;
            }
        });
        if ($request->paiement != null && $request->paiement !== 0) {
            $paiement = Paiement::create([
                'payable_id' => $contrat->id,
                'payable_type' => 'App\\Contrat',
                'montant' => $request->paiement,
                'type_paiement' => $request->type_paiement,
                'note' => $request->note_paiement,
            ]);
            if ($paiement) {
                // Mail::to('derricknoutais@gmail.com')->cc('noutaiaugustin@gmail.com')->bcc('servicesazimuts@gmail.com')->send(new PaiementCréé($contrat, $paiement));
            }
        }
        // if($contrat && env('APP_ENV') !== 'local'){
        //     $apiSettings = ApiSetting::where('compagnie_id', Auth::user()->compagnie->id)->first();
        //     $transactionData = [
        //         'transaction_date' => $contrat->created_at,
        //         'tenant_id' => $apiSettings->gescash_tenant_id,
        //         'book_id' => $apiSettings->gescash_book_id,
        //         'exercise_id' => $apiSettings->gescash_exercise_id,
        //         'attachment' => 'https://rentalpro.azimuts.ga/contrat/' . $contrat->id,
        //         'entries' => [
        //             // Client Entry Debit
        //             [
        //                 'account_id' => $apiSettings->gescash_client_account_id,
        //                 'label' => 'Location ' . $contrat->contractable->immatriculation . ' à ' . $contrat->client->nom . ' ' . $contrat->client->prenom,
        //                 'debit' => $contrat->nombre_jours * $contrat->prix_journalier,
        //                 'credit' => NULL,
        //                 'created_at' => $contrat->created_at,
        //                 'updated_at' => now()
        //             ],
        //             // Service Entry Credit
        //             [
        //                 'account_id' => $apiSettings->gescash_service_account_id,
        //                 'label' => 'Location ' . $contrat->contractable->immatriculation . ' à ' . $contrat->client->nom . ' ' . $contrat->client->prenom,
        //                 'credit' => $contrat->nombre_jours * $contrat->prix_journalier,
        //                 'debit' => NULL,
        //                 'created_at' => $contrat->created_at,
        //                 'updated_at' => now()
        //             ]
        //         ]
        //     ];
        //     if($request->paiement != NULL && $request->paiement !== 0){
        //         Paiement::create([
        //             'contrat_id' => $contrat->id,
        //             'montant' => $request->paiement,
        //             'type_paiement' => $request->type_paiement,
        //             'note' => $request->note_paiement
        //         ]);
        //         array_push($transactionData['entries'],
        //             // Caisse Entry Debit
        //             [
        //                 'account_id' => $apiSettings->gescash_cash_account_id,
        //                 'label' => 'Paiment Contrat ' . $contrat->numéro,
        //                 'debit' => $request->paiement,
        //                 'credit' => NULL,
        //                 'created_at' => $contrat->created_at,
        //                 'updated_at' => now()
        //             ],
        //             // Client Entry Credit
        //             [
        //                 'account_id' => $apiSettings->gescash_client_account_id,
        //                 'label' => 'Paiment Contrat ' . $contrat->numéro,
        //                 'credit' => $request->paiement,
        //                 'debit' => NULL,
        //                 'created_at' => $contrat->created_at,
        //                 'updated_at' => now()
        //             ]

        //         );
        //     }
        //     $contrat->loadMissing('contractable', 'client');
        //     // dd('hello');
        //     $response = Http::post(env('GESCASH_BASE_URL') . '/api/v1/transaction', $transactionData);

        //     if($response->status() == 201){
        //         $contrat->update([
        //             'gescash_transaction_id' => $response->json()['id']
        //         ]);
        //         flash('Contrat Enregistré avec Succès')->success();
        //         return redirect('/contrat/' . $contrat->id . '/print');
        //     }

        // Mail::to('derricknoutais@gmail.com')->cc('noutaiaugustin@gmail.com')->bcc('servicesazimuts@gmail.com')->send(new ContratCréé($contrat));
        // $message = $contrat->client->nom . ' ' . $contrat->client->prenom .  ', votre contrat de location sur la ' . $contrat->voiture->immatriculation . ' pour la période du '
        //     . $contrat->au->format('d-M-Y h:i') . ' au ' . $contrat->du->format('d-M-Y h:i') . ' a été enregistré avec succès. Merci de votre collaboration.

        // }
        return Auth::user()->compagnie->isVehicules() ? redirect('/contrat/' . $contrat->id . '/print') : redirect('/contrat/' . $contrat->id . '/print-hotel-A5');
    }

    public function print(Contrat $contrat)
    {
        $contrat->loadMissing('contractable', 'client');
        isset($contrat->contractable->documents) ? ($documents = $contrat->contractable->documents) : ($documents = []);
        isset($contrat->contractable->accessoires) ? ($accessoires = $contrat->contractable->accessoires) : ($accessoires = []);
        return view('contrats.print', compact('contrat', 'documents', 'accessoires'));
    }

    public function storeContratRapide(Request $request)
    {
        if (Auth::user()->compagnie->type == 'véhicules') {
            $type = 'App\\Voiture';
        } elseif (Auth::user()->compagnie->type == 'hôtel') {
            $type = 'App\\Chambre';
        }
        date_default_timezone_set('Africa/Libreville');
        // return $request->all();
        $contrat = DB::transaction(function () use ($request, $type) {
            // Créee le Client Si c'est un Nouveau Client
            $client = null;
            if (!$request->client_id) {
                $client = Client::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'adresse' => $request->addresse,
                    'compagnie_id' => Auth::user()->compagnie_id,
                    'numero_permis' => $request->numero_permis,
                    'phone1' => $request->numero_telephone,
                    'phone2' => $request->numero_telephone2,
                    'phone3' => $request->numero_telephone3,
                    'mail' => $request->mail,
                    'ville' => $request->ville,
                    'cashier_id' => $request->cashier_id,
                ]);

                if ($request->hasFile('permis')) {
                    $image = $request->file('permis');
                    $nom = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/uploads');
                    $image->move($destinationPath, $nom);
                    $client->update([
                        'permis' => $nom,
                    ]);
                }
            }

            // Pour gérer les contrats ouverts
            $au = $request['au'];
            if ($request['au'] == null) {
                $au = null;
            }
            if ($client) {
                $contrat = Contrat::create([
                    'contractable_id' => $request->chambre_id,
                    'contractable_type' => $type,
                    'client_id' => $client->id,
                    'numéro' => Contrat::numéro(),
                    'compagnie_id' => Auth::user()->compagnie->id,
                    'au' => $au,
                    'du' => $request['du'] . date('H:i:s'),
                    'real_check_out' => null,
                    'prix_journalier' => $request['prix_journalier'],
                    'caution' => null,
                    'nombre_jours' => $request['nombre_jours'],
                    'total' => $request['prix_journalier'] * $request['nombre_jours'],
                    'cashier_facture_id' => $request['cashier_id'],
                    'etat_accessoires' => $request['accessoireString'],
                    'etat_documents' => $request['documentString'],
                ]);
            } else {
                $contrat = Contrat::create([
                    'contractable_id' => $request->chambre_id,
                    'contractable_type' => $type,
                    'client_id' => $request->client_id,
                    'numéro' => Contrat::numéro(),
                    'compagnie_id' => Auth::user()->compagnie->id,
                    'au' => $au,
                    'du' => $request['du'] . date('H:i:s'),
                    'real_check_out' => null,
                    'prix_journalier' => $request['prix_journalier'],
                    'caution' => null,
                    'nombre_jours' => $request['nombre_jours'],
                    'total' => $request['prix_journalier'] * $request['nombre_jours'],
                    'cashier_facture_id' => $request['cashier_id'],
                    'etat_accessoires' => $request['accessoireString'],
                    'etat_documents' => $request['documentString'],
                ]);
            }

            $contractableUpdated = $contrat->contractable->update([
                'etat' => 'loué',
            ]);

            if ($contrat && $contractableUpdated) {
                return $contrat;
            }
        });
        if ($contrat) {
            if ($request->paiement != null) {
                Paiement::create([
                    'contrat_id' => $contrat->id,
                    'montant' => $request->paiement,
                ]);
            }
            $contrat->loadMissing('contractable', 'client', 'paiements');
            $formatter = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
            $total_in_words = ucwords($formatter->format($contrat->nombre_jours * $contrat->prix_journalier));
            if (Auth::user()->compagnie->type == 'véhicules') {
                $pdf = PDF::loadView('contrats.véhicules_contrat', compact('contrat', 'total_in_words'))->setPaper('a4', 'portrait');
            } elseif (Auth::user()->compagnie->type == 'hôtel') {
                $pdf = PDF::loadView('contrats.hotel_contrat', compact('contrat', 'total_in_words'))->setPaper('a4', 'portrait');
            }
            return $pdf->download(Auth::user()->compagnie->nom . ' ' . $contrat->numéro . '.pdf');
        }
    }

    public function edit(Contrat $contrat)
    {
        $contrat->loadMissing('client');
        $clients = Auth::user()->compagnie->clients;
        $chambresDisponibles = Chambre::where('etat', 'Disponible')->get();
        return view('contrats.edit', compact('contrat', 'clients', 'chambresDisponibles'));
    }

    public function update(Contrat $contrat, Request $request)
    {
        $stored = DB::transaction(function () use ($contrat, $request) {
            if ($contrat->contractable_id != $request->contractable) {
                $prolongation = Prolongation::create([
                    'contrat_id' => $contrat->id,
                    'du' => $contrat->au,
                    'au' => $request->date,
                ]);
            }
            $diffInDays = Carbon::parse($request->date)
                ->startOfDay()
                ->diffInDays(Carbon::parse($contrat->du)->startOfDay());
            if ($contrat->nombre_jours < $diffInDays || ($contrat->nombre_jours > $diffInDays && Auth::user()->hasAnyRole(['admin', 'gérant']))) {
                $contrat->update([
                    'au' => $request->date,
                    'contractable_id' => $request->contractable,
                    'nombre_jours' => $diffInDays,
                ]);
            } else {
                flash("Vous n'avez pas la possibilité de racourcir la duré d'un contrat. Veuillez contacter un Manager")->error();
                return redirect()->back();
            }
        });
        return redirect()
            ->back()
            ->withFlash(['test' => 'micro']);
    }

    public function updateAll(Contrat $contrat, Request $request)
    {
        $diffInDays = Carbon::parse($request->au)
            ->startOfDay()
            ->diffInDays(Carbon::parse($request->du)->startOfDay());

        $contrat->update([
            'client_id' => $request->client,
            'du' => $request->du,
            'au' => $request->au,
            'prix_journalier' => $request->prix_journalier,
            'contractable_id' => $request->contractable,
            'nombre_jours' => $diffInDays,
            'caution' => $request->caution,
        ]);
        return redirect('/contrats');
    }

    public function prolonger(Request $request, Contrat $contrat)
    {
        $contrat = DB::transaction(function () use ($request, $contrat) {
            $nombre_jours = Carbon::parse($request->du)
                ->endOfDay()
                ->diffInDays($contrat->du->endOfDay());
            // $nouveauContrat = Contrat::create([
            //     'contractable_id' => $request->voiture,
            //     'contractable_type' => 'App\\Voiture',
            //     'client_id' => $contrat->client_id,
            //     'numéro' => Contrat::numéro(),
            //     'compagnie_id' => Auth::user()->compagnie->id,
            //     'au' => $request->du ,
            //     'du' => $contrat->au . $contrat->du->format('H:i:s'),
            //     'prix_journalier' => $request->prix_journalier,
            //     'nombre_jours' => $nombre_jours,
            //     "total" => $nombre_jours * $contrat->prix_journalier,
            //     "caution" => $contrat->caution,
            //     "etat_accessoires" => $contrat->etat_accessoires,
            //     "lien_photo_avant" => $contrat->lien_photo_avant,
            //     "lien_photo_arriere" => $contrat->lien_photo_arriere,
            //     "lien_photo_droit" => $contrat->lien_photo_droit,
            //     "lien_photo_gauche" => $contrat->lien_photo_gauche,
            // ]);
            $contrat->update([
                'au' => $request->du,
                'nombre_jours' => $nombre_jours,
                'total' => $nombre_jours * $contrat->prix_journalier,
                'contractable_id' => $request->voiture,
            ]);

            if ($contrat->contractable->id !== $request->voiture) {
                $contrat->contractable->etat('disponible');
                Voiture::find($request->voiture)->etat('loué');
            }
            return $contrat;
        });
        $contrat->loadMissing('contractable', 'client');
        // return $contrat;
        // Mail::to('derricknoutais@gmail.com')->cc('kougblenouleonce@gmail.com')->bcc('servicesazimuts@gmail.com')->send(new ContratCréé($contrat));
        return redirect()->back();
    }

    public function changerVoiture(Request $request, Contrat $contrat)
    {
        DB::transaction(function () use ($request, $contrat) {
            // Retrouve la voiture a remplacer
            $voiture = Voiture::find($request->voiture);

            $contrat->contractable->etat('disponible');
            if ($voiture->etat === 'disponible') {
                $contrat->update([
                    'contractable_id' => $request->voiture,
                ]);
            }
            $voiture->etat('loué');
        });
        return redirect()->back();
    }

    public function destroy(Contrat $contrat)
    {
        $contrat->contractable->update([
            'etat' => 'disponible',
        ]);
        if ($paiements = $contrat->paiements) {
            $paiements->each(function ($paiement) {
                $paiement->delete();
            });
        }
        $contrat->delete();
        return $contrat;
    }

    public function download(Contrat $contrat)
    {
        $contrat->loadMissing('contractable', 'client', 'paiements', 'compagnie');
        $formatter = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
        $total_in_words = ucwords($formatter->format($contrat->nombre_jours * $contrat->prix_journalier));
        if ($contrat->compagnie->type == 'véhicules') {
            $pdf = PDF::loadView('contrats.véhicules_contrat', compact('contrat', 'total_in_words'))->setPaper('a4', 'portrait');
        } elseif ($contrat->compagnie->type == 'hôtel') {
            $pdf = PDF::loadView('contrats.hotel_contrat', compact('contrat', 'total_in_words'))->setPaper('a4', 'portrait');
        }
        return $pdf->download(Auth::user()->compagnie->nom . ' ' . $contrat->numéro . '.pdf');
    }

    public function voirUploads(Contrat $contrat)
    {
        return view('contrats.uploads', compact('contrat'));
    }

    public function ajoutePhotos(Request $request, Contrat $contrat)
    {
        $path_droit = Storage::disk('public_uploads')->put('/', $request->file('droit'));
        $path_gauche = Storage::disk('public_uploads')->put('/', $request->file('gauche'));
        $path_avant = Storage::disk('public_uploads')->put('/', $request->file('avant'));
        $path_arriere = Storage::disk('public_uploads')->put('/', $request->file('arriere'));
        $contrat->update([
            'lien_photo_avant' => $path_avant,
            'lien_photo_arriere' => $path_arriere,
            'lien_photo_droit' => $path_droit,
            'lien_photo_gauche' => $path_gauche,
        ]);
        return 'Depuis les photos';
    }

    public function updateCashier(Request $request, Contrat $contrat)
    {
        $contrat->update([
            'cashier_facture_id' => $request->id,
        ]);
    }

    public function updateCashierId(Request $request, Contrat $contrat)
    {
        $contrat->update([
            'cashier_facture_id' => $request->cashier_id,
        ]);
    }

    public function terminer(Contrat $contrat, Request $request)
    {
        DB::transaction(function () use ($contrat, $request) {
            $nb_jours = Carbon::parse($request->date_fin)
                ->startOfDay()
                ->diffInDays(Carbon::parse($contrat->du)->startOfDay());
            if ($nb_jours == 0) {
                $nb_jours = 1;
            }
            $contrat->update([
                'real_check_out' => $request->date_fin,
                'au' => $request->date_fin,
                'nombre_jours' => $nb_jours,
            ]);
            $contrat->contractable->update([
                'etat' => 'disponible',
            ]);
        });
        return redirect()->back();
    }

    public function envoyerContratGescash(Contrat $contrat)
    {
        $apiSettings = ApiSetting::where('compagnie_id', Auth::user()->id)->first();
        $contrat->loadMissing('contractable', 'client', 'paiements', 'compagnie');
        $transactionData = [
            'transaction_date' => $contrat->created_at,
            'tenant_id' => $apiSettings->gescash_tenant_id,
            'book_id' => $apiSettings->gescash_book_id,
            'exercise_id' => $apiSettings->gescash_exercise_id,
            'attachment' => 'https://rentalpro.azimuts.ga/contrat/' . $contrat->id,
            'entries' => [
                // Client Entry Debit
                [
                    'account_id' => $apiSettings->gescash_client_account_id,
                    'label' => 'Location ' . $contrat->contractable->immatriculation . ' à ' . $contrat->client->nom . ' ' . $contrat->client->prenom,
                    'debit' => $contrat->nombre_jours * $contrat->prix_journalier,
                    'credit' => null,
                    'created_at' => $contrat->created_at,
                    'updated_at' => now(),
                ],
                // Service Entry Credit
                [
                    'account_id' => $apiSettings->gescash_service_account_id,
                    'label' => 'Location ' . $contrat->contractable->immatriculation . ' à ' . $contrat->client->nom . ' ' . $contrat->client->prenom,
                    'credit' => $contrat->nombre_jours * $contrat->prix_journalier,
                    'debit' => null,
                    'created_at' => $contrat->created_at,
                    'updated_at' => now(),
                ],
            ],
        ];
        if (isset($contrat->paiements)) {
            foreach ($contrat->paiements as $paiement) {
                array_push(
                    $transactionData['entries'],
                    // Caisse Entry Debit
                    [
                        'account_id' => $apiSettings->gescash_cash_account_id,
                        'label' => 'Paiment Contrat ' . $contrat->numéro,
                        'debit' => $paiement->montant,
                        'credit' => null,
                        'created_at' => $contrat->created_at,
                        'updated_at' => now(),
                    ],
                    // Client Entry Credit
                    [
                        'account_id' => $apiSettings->gescash_client_account_id,
                        'label' => 'Paiment Contrat ' . $contrat->numéro,
                        'credit' => $paiement->montant,
                        'debit' => null,
                        'created_at' => $contrat->created_at,
                        'updated_at' => now(),
                    ],
                );
            }
        }
        // dd('hello');
        $response = Http::post(env('GESCASH_BASE_URL') . '/api/v1/transaction', $transactionData);

        if ($response->status() == 201) {
            $contrat->update([
                'gescash_transaction_id' => $response->json()['id'],
            ]);
            flash('Transféré vers Gescash avec succès')->success();
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function ajouterDemiJournee(Contrat $contrat, Request $request)
    {
        $contrat->update([
            'demi_journee' => $request['demi_journee'],
        ]);
        return redirect()->back();
    }
    public function updateDemiJournee(Contrat $contrat, Request $request)
    {
        $contrat->update([
            'demi_journee' => $request['demi_journee'],
        ]);
        return redirect()->back();
    }

    public function ajouterMontantChauffeur(Contrat $contrat, Request $request)
    {
        $contrat->update([
            'montant_chauffeur' => $request['montant_chauffeur'],
        ]);
        return redirect()->back();
    }
    public function updateMontantChauffeur(Contrat $contrat, Request $request)
    {
        $contrat->update([
            'montant_chauffeur' => $request['montant_chauffeur'],
        ]);
        return redirect()->back();
    }
    public function resetDataToNull(Contrat $contrat, $data)
    {
        return $contrat->update([
            $data => null,
        ]);
    }

    public function sendMail(Contrat $contrat, Request $request)
    {
        if (
            $request->validate([
                'mail' => 'required|email',
            ])
        ) {
            Mail::to($request->mail)->send(new ContratCréé($contrat));
        }
        if ($request->has('save_mail') && $request->save_mail) {
            $contrat->client->update(['mail' => $request->mail]);
        }
        return redirect()->back();
    }
}
