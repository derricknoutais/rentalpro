<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrat;
use App\Client;
use App\Voiture;
class ContratController extends Controller
{
    public function menu(){
        return view('contrats.menu');
    }
    public function index(){
        $contrats = Contrat::with('client', 'voiture')->orderBy('id', 'desc')->paginate(20);
        return view('contrats.index', compact('contrats'));
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

        $contrat = Contrat::create([
            'voiture_id'=> $request['voiture']['id'],
            'client_id'=> $request['client']['id'],
            'numéro' => Contrat::numéro(),
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

        return $contrat;
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
}
