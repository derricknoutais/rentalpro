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
}
