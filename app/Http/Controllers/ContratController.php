<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrat;
use App\Client;
use App\Events\ContratCree;
use App\Voiture;
use Carbon\Carbon;
use App\Chambre;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContratCréé;
use App\Prolongation;
use PDF;
use Nexmo\Laravel\Facade\Nexmo;
use NumberFormatter;
use App\Paiement;
class ContratController extends Controller
{
    public function menu(){
        return view('contrats.menu');
    }
    public function index(){
        $compagnie = Auth::user()->compagnie;
        $contrats_compagnie = Contrat::withTrashed()->where('compagnie_id', $compagnie->id)->latest()->get();
        $contrats = Contrat::withTrashed()->whereIn('id', array_pluck($contrats_compagnie, 'id'))->orderBy('id', 'desc')->paginate(5);
        $contrats->loadMissing(['client', 'contractable', 'paiements']);
        if($compagnie->type === 'véhicules'){
            $contractablesDisponibles = Voiture::where('etat', 'Disponible')->get();
        } else {
            $contractablesDisponibles = Chambre::where('etat', 'Disponible')->get();
        }
        $contrat = $contrats[0];
        $voitures = Voiture::all();

        return view('contrats.index', compact(['contrats', 'voitures', 'compagnie', 'contractablesDisponibles']));
    }
    public function show(Contrat $contrat){
        $contrat->loadMissing('client', 'contractable', 'compagnie');
        $du = $contrat->du->format('d-M-Y');
        $formatter = new NumberFormatter("fr", NumberFormatter::SPELLOUT);
        $total_in_words = ucwords($formatter->format($contrat->nombre_jours * $contrat->prix_journalier));

        return view('contrats.show', compact('contrat'));
    }
    public function edit(Contrat $contrat){
        $contrat->loadMissing('client');
        $clients = Auth::user()->compagnie->clients;
        $chambresDisponibles = Chambre::where('etat', 'Disponible')->get();
        return view('contrats.edit', compact('contrat', 'clients', 'chambresDisponibles'));
    }
    public function update(Contrat $contrat, Request $request){

        $stored = DB::transaction(function () use ($contrat, $request) {
            if($contrat->contractable_id != $request->contractable ){
                $prolongation = Prolongation::create([
                    'contrat_id' => $contrat->id,
                    'du'  => $contrat->au,
                    'au' => $request->date
                ]);
            }
            $diffInDays = Carbon::parse($request->date)->startOfDay()->diffInDays(Carbon::parse($contrat->du)->startOfDay());
            if($contrat->nombre_jours < $diffInDays ){
                $contrat->update([
                    'au' => $request->date,
                    'contractable_id' => $request->contractable,
                    'nombre_jours' => $diffInDays
                ]);
            }
        });
        return redirect()->back()->withFlash(['test' => 'micro' ]);
    }
    public function updateAll(Contrat $contrat, Request $request){
        $diffInDays = Carbon::parse($request->au)->startOfDay()->diffInDays(Carbon::parse($request->du)->startOfDay());
        $contrat->update([
            'client_id' => $request->client,
            'du' => $request->du,
            'au' => $request->au,
            'prix_journalier' => $request->prix_journalier,
            'contractable_id' => $request->contractable,
            'nombre_jours' => $diffInDays
        ]);
    }
    public function voirUploads(Contrat $contrat){
        return view('contrats.uploads', compact('contrat'));
    }
    public function create(){
        $clients = Client::all();
        $clients->toArray();
        $voitures = Voiture::where('etat', 'disponible')->with('documents', 'accessoires')->get();
        $voitures->toArray();
        return view('contrats.create', compact('clients', 'voitures'));
    }
    public function store(Request $request){
        // return $request->all();
        date_default_timezone_set( 'Africa/Libreville');
        $contrat = DB::transaction(function () use ($request){
            $contrat = Contrat::create([
                'contractable_id'=> $request['voiture']['id'],
                'contractable_type' => 'App\\Voiture',
                'client_id'=> $request['client']['id'],
                'numéro' => Contrat::numéro(),
                'compagnie_id' => Auth::user()->compagnie->id,
                'du'=> $request['au'] . date('H:i:s'),
                'au'=> $request['du'] . date('H:i:s'),
                'real_check_out' => NULL,
                'prix_journalier'=> $request['prix_journalier'],
                'caution' => $request['caution'],
                'nombre_jours'=> $request['nombre_jours'],
                'total'=> $request['prix_journalier'] * $request['nombre_jours'],
                'cashier_facture_id' => $request['cashier_id'],
                'etat_accessoires' => $request[ 'accessoireString'],
                'etat_documents' => $request[ 'documentString'],
            ]);

            Voiture::find( $request['voiture']['id'])->update([
                'etat' => 'loué'
            ]);
            if($contrat){
                return $contrat;
            }
        });
        if($contrat){
            if($request->paiement != NULL){
                Paiement::create([
                    'contrat_id' => $request->contrat_id,
                    'montant' => $request->paiement
                ]);
            }
            $contrat->loadMissing('contractable', 'client');
            // Mail::to('derricknoutais@gmail.com')->cc('kougblenouleonce@gmail.com')->bcc('servicesazimuts@gmail.com')->send(new ContratCréé($contrat));
            // $message = $contrat->client->nom . ' ' . $contrat->client->prenom .  ', votre contrat de location sur la ' . $contrat->voiture->immatriculation . ' pour la période du '
            //     . $contrat->au->format('d-M-Y h:i') . ' au ' . $contrat->du->format('d-M-Y h:i') . ' a été enregistré avec succès. Merci de votre collaboration.';

            // Nexmo::message()->send([
            //     'to'   => '24107158215',
            //     'from' => 'STA',
            //     'text' => $message
            // ]);




            return $contrat;
        }
    }
    public function storeContratRapide(Request $request){
        date_default_timezone_set( 'Africa/Libreville');
        // return $request->all();
        $contrat = DB::transaction(function () use ($request){

            // Créee le Client Si c'est un Nouveau Client
            $client = null;
            if(! $request->client_id ){
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
                    'cashier_id' => $request->cashier_id
                ]);

                if($request->hasFile('permis')){
                    $image = $request->file('permis');
                    $nom = time(). uniqid() . '.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/uploads');
                    $image->move($destinationPath, $nom);
                    $client->update([
                        'permis' => $nom
                    ]);
                }
            }

            // Pour gérer les contrats ouverts
            $au = $request['au'];
            if($request['au'] == NULL){
                $au = NULL;
            }
            if($client){
                $contrat = Contrat::create([
                    'contractable_id'=> $request->chambre_id,
                    'contractable_type' => 'App\\Chambre',
                    'client_id'=> $client->id ,
                    'numéro' => Contrat::numéro(),
                    'compagnie_id' => Auth::user()->compagnie->id,
                    'au'=> $au,
                    'du'=> $request['du'] . date('H:i:s'),
                    'real_check_out' => NULL,
                    'prix_journalier'=> $request['prix_journalier'],
                    'caution' => NULL,
                    'nombre_jours'=> $request['nombre_jours'],
                    'total'=> $request['prix_journalier'] * $request['nombre_jours'],
                    'cashier_facture_id' => $request['cashier_id'],
                    'etat_accessoires' => $request[ 'accessoireString'],
                    'etat_documents' => $request[ 'documentString'],
                ]);
            } else {
                $contrat = Contrat::create([
                    'contractable_id'=> $request->chambre_id,
                    'contractable_type' => 'App\\Chambre',
                    'client_id'=> $request->client_id ,
                    'numéro' => Contrat::numéro(),
                    'compagnie_id' => Auth::user()->compagnie->id,
                    'au'=> $au,
                    'du'=> $request['du'] . date('H:i:s'),
                    'real_check_out' => NULL,
                    'prix_journalier'=> $request['prix_journalier'],
                    'caution' => NULL,
                    'nombre_jours'=> $request['nombre_jours'],
                    'total'=> $request['prix_journalier'] * $request['nombre_jours'],
                    'cashier_facture_id' => $request['cashier_id'],
                    'etat_accessoires' => $request[ 'accessoireString'],
                    'etat_documents' => $request[ 'documentString']
                ]);
            }

            $chambreUpdated = Chambre::find( $request->chambre_id)->update([
                'etat' => 'loué'
            ]);

            if($contrat && $chambreUpdated){
                return $contrat;
            }
        });
        if($contrat){
            if($request->paiement != NULL){
                Paiement::create([
                    'contrat_id' => $contrat->id,
                    'montant' => $request->paiement
                ]);
            }
            $contrat->loadMissing('contractable', 'client');
            // Mail::to('derricknoutais@gmail.com')->cc('kougblenouleonce@gmail.com')->bcc('servicesazimuts@gmail.com')->send(new ContratCréé($contrat));
            // $message = $contrat->client->nom . ' ' . $contrat->client->prenom .  ', votre contrat de location sur la ' . $contrat->voiture->immatriculation . ' pour la période du '
            //     . $contrat->au->format('d-M-Y h:i') . ' au ' . $contrat->du->format('d-M-Y h:i') . ' a été enregistré avec succès. Merci de votre collaboration.';
            $formatter = new NumberFormatter("fr", NumberFormatter::SPELLOUT);
            $total_in_words = ucwords($formatter->format($contrat->nombre_jours * $contrat->prix_journalier));
            $pdf = PDF::loadView('contrats.pdf', compact('contrat', 'total_in_words'))->setPaper('a4', 'portrait');
            return $pdf->download(Auth::user()->compagnie->nom . ' ' . $contrat->numéro . '.pdf');
        }
    }
    public function ajoutePhotos(Request $request, Contrat $contrat){
        $path_droit = \Storage::disk('public_uploads')->put("/", $request->file('droit'));
        $path_gauche = \Storage::disk('public_uploads')->put("/", $request->file('gauche'));
        $path_avant = \Storage::disk('public_uploads')->put("/", $request->file('avant'));
        $path_arriere = \Storage::disk('public_uploads')->put("/", $request->file('arriere'));
        $contrat->update([
            'lien_photo_avant' => $path_avant,
            'lien_photo_arriere' => $path_arriere,
            'lien_photo_droit' => $path_droit,
            'lien_photo_gauche' => $path_gauche,
        ]);
        return 'Depuis les photos';
    }
    public function updateCashier(Request $request, Contrat $contrat){
        $contrat->update([
            'cashier_facture_id' => $request->id
        ]);
    }
    public function prolonger(Request $request, Contrat $contrat){
        $contrat = DB::transaction(function () use ( $request, $contrat) {
            $nombre_jours = Carbon::parse($request->du)->endOfDay()->diffInDays($contrat->du->endOfDay());

            $nouveauContrat = Contrat::create([
                'voiture_id' => $request->voiture,
                'client_id' => $contrat->client_id,
                'numéro' => Contrat::numéro(),
                'compagnie_id' => Auth::user()->compagnie->id,
                'au' => $contrat->du ,
                'du' => $request->du . $contrat->du->format('H:i:s'),
                'prix_journalier' => $request->prix_journalier,
                'nombre_jours' => $nombre_jours,
                "total" => $nombre_jours * $contrat->prix_journalier,
                "caution" => $contrat->caution,
                "etat_accessoires" => $contrat->etat_accessoires,
                "lien_photo_avant" => $contrat->lien_photo_avant,
                "lien_photo_arriere" => $contrat->lien_photo_arriere,
                "lien_photo_droit" => $contrat->lien_photo_droit,
                "lien_photo_gauche" => $contrat->lien_photo_gauche,
            ]);
            $contrat->update([
                'real_check_out' => $contrat->du,
                'prolongation_id' => $nouveauContrat->id
            ]);

            if($contrat->voiture->id !== $request->voiture){
                $contrat->voiture->etat('disponible');
                Voiture::find( $request->voiture )->etat('loué');
            }
            return $nouveauContrat;
        });
        $contrat->loadMissing('voiture', 'client');
        // return $contrat;
        // Mail::to('derricknoutais@gmail.com')->cc('kougblenouleonce@gmail.com')->bcc('servicesazimuts@gmail.com')->send(new ContratCréé($contrat));
        // return redirect()->back();
    }
    public function changerVoiture( Request $request, Contrat $contrat){

        DB::transaction(function () use ($request, $contrat){
            // Retrouve la voiture a remplacer
            $voiture = Voiture::find( $request->voiture );

            //
            $contrat->voiture->etat('disponible');


            if( $voiture->etat === 'disponible' ){

                $contrat->update([
                    'voiture_id' => $request->voiture
                ]);

            }

            $voiture->etat('loué');

        });
        return redirect()->back();


    }
    public function updateCashierId(Request $request, Contrat $contrat){
        $contrat->update([
            'cashier_facture_id' => $request->cashier_id
        ]);
    }
    public function destroy(Contrat $contrat){
        $contrat->delete();
    }

}
