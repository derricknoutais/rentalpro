<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrat;
use App\Client;
use App\Voiture;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class ContratController extends Controller
{
    public function menu(){
        return view('contrats.menu');
    }
    public function index(){
        
        $contrats = Contrat::with('client', 'voiture')->orderBy('id', 'desc')->paginate(20);
        $voitures = Voiture::all();
        return view('contrats.index', compact(['contrats', 'voitures']));
    }
    public function show(Contrat $contrat){
        $contrat->loadMissing('client', 'voiture');
        $check_in = $contrat->check_in->format('d-M-Y');
        return view('contrats.show', compact('contrat'));
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

        date_default_timezone_set( 'Africa/Libreville');
        $contrat = DB::transaction(function () use ($request){
            $contrat = Contrat::create([
                'voiture_id'=> $request['voiture']['id'],
                'client_id'=> $request['client']['id'],
                'numéro' => Contrat::numéro(),
                'compagnie_id' => Auth::user()->compagnie->id,
                'check_out'=> $request['check_out'] . date('H:i:s'),
                'check_in'=> $request['check_in'] . date('H:i:s'),
                'real_check_in' => NULL,
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
            return $contrat;
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
        DB::transaction(function () use ( $request, $contrat) {
            $nombre_jours = Carbon::parse($request->check_in)->endOfDay()->diffInDays($contrat->check_in->endOfDay());

            $nouveauContrat = Contrat::create([
                'voiture_id' => $contrat->voiture_id,
                'client_id' => $contrat->client_id,
                'numéro' => Contrat::numéro(),
                'check_out' => $contrat->check_in,
                'check_in' => $request->check_in,
                'prix_journalier' => $contrat->prix_journalier,
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
                'real_check_in' => $contrat->check_in,
                'prolongation_id' => $nouveauContrat->id
            ]);
        });
        
        return redirect()->back();
    }
    public function changerVoiture( Request $request, Contrat $contrat){

        DB::transaction(function () {
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
            return redirect()->back();
        });
        
        

    }

}
