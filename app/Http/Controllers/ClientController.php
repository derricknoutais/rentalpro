<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Contrat;
use DB;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(){
        if( isset(Auth::user()->compagnie)){
            $clients = Auth::user()->compagnie->clients;
            return view('clients.index', compact('clients'));
        } else {
            return redirect('/compagnies/create');
        }
        
    }
    public function show(Client $client){
        $client->loadMissing('contrats');
        return view('clients.show', compact('client'));
    }
    public function edit(Client $client){
        return view('clients.update', compact('client'));
    }
    public function update(Request $request, Client $client){
        $client->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'adresse' => $request->addresse,
            'numero_permis' => $request->numero_permis,
            'phone1' => $request->numero_telephone,
            'phone2' => $request->numero_telephone2,
            'phone3' => $request->numero_telephone3,
            'mail' => $request->mail,
            'ville' => $request->ville,
        ]);
    }
    public function store(Request $request){
        $client = DB::transaction(function () use ($request){
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
            return $client;
        });
        
        return redirect('/clients/' . $client->id);

    }
}
