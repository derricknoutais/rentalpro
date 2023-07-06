<?php

namespace App\Http\Controllers;

use DB;
use App\Client;
use App\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // return Auth::user();

        if (isset(Auth::user()->compagnie)) {
            $clients = Auth::user()->compagnie->clients;
            if ($request->has('client')) {
                $clients = Client::where('compagnie_id', Auth::user()->compagnie_id)->where(
                    'nom',
                    'like',
                    '%' . $request->client . '%'
                )->orWhere('prenom', 'like', '%' . $request->client . '%')
                    ->orWhere('phone1', 'like', '%' . $request->client . '%')
                    ->orWhere('phone2', 'like', '%' . $request->client . '%')->get();
            }


            return view('clients.index', compact('clients'));
        } else {
            return redirect('/compagnies/create');
        }
    }
    public function show(Client $client)
    {
        $client->loadMissing('contrats', 'contrats.compagnie', 'compagnie', 'image');
        // return $client;
        return view('clients.show', compact('client'));
    }
    public function edit(Client $client)
    {
        $client->loadMissing('image');
        return view('clients.edit', compact('client'));
    }
    public function update(Request $request, Client $client)
    {
        if (str_contains($request->numero_telephone, '/')) {
            $numeros = explode('/', $request->numero_telephone);
            $request->numero_telephone = $numeros[0];
            $request->numero_telephone2 = $numeros[1];
        }
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
            'image_id' => $request->image_id
        ]);
        // sd;
        $client->loadMissing('image');
        $imageName = $client->nom . ' ' . $client->prenom . ' ' . $client->phone1;
        $imageName = str_replace('/', ' ', $imageName);
        if ($client->image) {
            Storage::disk('do_spaces')->rename('permis/' . $client->image->name, 'permis/' . $imageName);
            $client->image->update(['name' => $imageName]);
        }

        return redirect('/clients/' . $client->id);
    }
    public function store(Request $request)
    {
        $client = DB::transaction(function () use ($request) {
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

            if ($request->hasFile('permis')) {
                $image = $request->file('permis');
                $nom = time() . uniqid() . '.' . $image->getClientOriginalExtension();
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
    public function delete(Client $client)
    {
        return $client->delete();
    }
}
