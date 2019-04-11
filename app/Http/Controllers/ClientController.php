<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Contrat;

class ClientController extends Controller
{
    public function index(){
        $clients = Client::all()->sortBy('nom');
        return view('clients.index', compact('clients'));
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
}
