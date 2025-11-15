<?php

namespace App\Http\Controllers;

use App\Panne;
use App\Chambre;
use App\Contrat;
use App\Image;
use App\Voiture;
use App\Technicien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        $voiture->loadMissing('documents', 'accessoires', 'pannes', 'maintenances', 'images');

        $contrats = Contrat::where('contractable_id', $voiture->id)->orderBy('id', 'desc')->paginate(10);
        $derniere_maintenance = null;

        if(sizeof($voiture->maintenances)){
            $dernier_contrat_id = $voiture->maintenances[sizeof($voiture->maintenances) - 1]->id ;
            $derniere_maintenance = \App\Maintenance::where('id', $dernier_contrat_id)->with('pannes')->first();
        }

        $documents = Auth::user()->compagnie->documents;
        $accessoires = Auth::user()->compagnie->accessoires;

        return view('voitures.show', compact('voiture', 'techniciens', 'derniere_maintenance', 'contrats', 'documents', 'accessoires'));
    }
    public function reception(Request $request){

        $dernier_contrat_id =  $request->voiture['contrats'][0]['id'];
        $contrat = Contrat::find( $dernier_contrat_id);

        $contrat->update([
            'etat_accessoires_au_retour' => $request->accessoires,
            'etat_documents_au_retour' => $request->documents,
            'real_check_out' => now(),
            'statut' => Contrat::STATUS_TERMINE,
        ]);
        Voiture::find( $request->voiture['id'])->etat('disponible');
    }
    public function maintenance(Voiture $voiture){
        return $voiture->etat('maintenance');
    }
    public function store(Request $request){

        $data = $request->validate([
            'immatriculation' => 'required|string',
            'numero_chassis' => 'nullable|string',
            'annee' => 'nullable|numeric',
            'marque' => 'nullable|string',
            'type' => 'nullable|string',
            'prix' => 'nullable|numeric',
            'images' => 'nullable|array',
            'images.*' => 'integer|exists:images,id',
        ]);

        $voiture = DB::transaction(function () use ($data) {
            $voiture = Voiture::create([
                'immatriculation' => $data['immatriculation'],
                'compagnie_id' => Auth::user()->compagnie_id,
                'chassis' => $data['numero_chassis'] ?? null,
                'annee' => $data['annee'] ?? null,
                'marque' => $data['marque'] ?? null,
                'type' => $data['type'] ?? null,
                'etat' => 'disponible',
                'prix' => $data['prix'] ?? null,
            ]);

            if (!empty($data['images'])) {
                $images = Image::whereIn('id', $data['images'])->get();

                foreach ($images as $image) {
                    if ($image->directory !== 'voitures') {
                        Storage::disk('do_spaces')->rename($image->directory . '/' . $image->name, 'voitures/' . $image->name);
                        $image->directory = 'voitures';
                        $image->save();
                    }
                }

                $voiture->images()->sync($images->pluck('id')->all());
                $firstImage = $images->first();

                if ($firstImage) {
                    $voiture->update([
                        'photo_url' => $firstImage->url,
                    ]);
                }
            }

            return $voiture;
        });

        return redirect('/voiture/' . $voiture->id);
    }

}
