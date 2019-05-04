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
        $voiture->loadMissing('contrats', 'documents', 'accessoires', 'pannes');
        return view('voitures.show', compact('voiture', 'techniciens'));
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
