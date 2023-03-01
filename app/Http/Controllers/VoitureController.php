<?php

namespace App\Http\Controllers;

use App\Panne;
use App\Chambre;
use App\Contrat;
use App\Voiture;
use App\Technicien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoitureController extends Controller
{
    public function index(Request $request){
        if(Auth::user()->compagnie->isHotel()){
            $query = Voiture::query();
        } else {
            $query = Chambre::query();
        }
        if( sizeof($request->all()) > 0){
            if($request->has('etat')){
                $query->where('etat', $request->etat );
            }
        }
        $voitures = $contractables = $query->get();
        $contrats = Contrat::all();
        if(Auth::user()->compagnie->isHotel()){
            return view('voitures.index', compact('voitures', 'contrats'));
        } else {
            return view('contractables.hotel.index', compact('voitures', 'contrats', 'contractables'));
        }

    }
    public function rendreDisponible(Voiture $contractable){
        return $contractable->update(['etat' => 'disponible']);
    }
    public function show(Voiture $voiture){
        $techniciens = Technicien::all();

        $voiture->loadMissing('documents', 'accessoires', 'pannes', 'maintenances');

        $contrats = Contrat::where('contractable_id', $voiture->id)->orderBy('id', 'desc')->paginate(10);
        $derniere_maintenance = null;

        if(sizeof($voiture->maintenances)){
            $dernier_contrat_id = $voiture->maintenances[sizeof($voiture->maintenances) - 1]->id ;
            $derniere_maintenance = \App\Maintenance::where('id', $dernier_contrat_id)->with('pannes')->first();
        }

        return view('voitures.show', compact('voiture', 'techniciens', 'derniere_maintenance', 'contrats'));
    }
    public function reception(Request $request){

        $dernier_contrat_id =  $request->voiture['contrats'][0]['id'];
        $contrat = Contrat::find( $dernier_contrat_id);

        $contrat->update([
            'etat_accessoires_au_retour' => $request->accessoires,
            'etat_documents_au_retour' => $request->documents,
            'real_check_out' => now()
        ]);
        Voiture::find( $request->voiture['id'])->etat('disponible');
    }
    public function maintenance(Voiture $voiture){
        return $voiture->etat('maintenance');
    }
    public function store(Request $request){

        $voiture = Voiture::create([
            'immatriculation' => $request->immatriculation,
            'compagnie_id' => Auth::user()->compagnie_id,
            'chassis' => $request->numero_chassis,
            'annee' => $request->annee,
            'marque' => $request->marque,
            'type' => $request->type,
            'etat' => 'disponible',
            'prix' => $request->prix
        ]);

        return redirect('/voiture/' . $voiture->id);
    }

}
