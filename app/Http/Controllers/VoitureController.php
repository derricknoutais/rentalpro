<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voiture;
use App\Contrat;
use App\Technicien;

class VoitureController extends Controller
{
    public function index(){
        $voitures = Voiture::all()->sortBy('immatriculation');
        return view('voitures.index', compact('voitures'));
    }
    public function show(Voiture $voiture){

        $techniciens = Technicien::all();

        $voiture->loadMissing('contrats', 'documents', 'accessoires', 'pannes', 'maintenances');

        $contrats = Contrat::where('voiture_id', $voiture->id)->paginate(10);

        if(sizeof($voiture->maintenances)){
            $dernier_contrat_id = $voiture->maintenances[sizeof($voiture->maintenances) - 1]->id ;
            $derniere_maintenance = \App\Maintenance::where('id', $dernier_contrat_id)->with('pannes')->first();
        }

        return view('voitures.show', compact('voiture', 'techniciens', 'derniere_maintenance', 'contrats'));
    }
    public function reception(Request $request){
        $dernier_contrat_id =  $request->voiture['contrats'][sizeof( $request->voiture['contrats']) - 1]['id'];
        $contrat = Contrat::find( $dernier_contrat_id);
        $contrat->update([
            'etat_accessoires_au_retour' => $request->accessoires,
            'etat_documents_au_retour' => $request->documents,
            'real_check_in' => now()
        ]);
        Voiture::find( $request->voiture['id'])->etat('disponible');
    }
    public function maintenance(Voiture $voiture){
        $voiture->etat('maintenance');
    }
}
