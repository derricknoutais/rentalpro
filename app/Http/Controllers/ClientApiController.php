<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientApiController extends Controller
{
    /**
     * Return the authenticated compagnie clients list.
     */
    public function index(Request $request)
    {
        $this->authorizeCompany();

        $query = Client::query()
            ->where('compagnie_id', Auth::user()->compagnie_id);

        if ($search = $request->input('q')) {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('phone1', 'like', "%{$search}%")
                    ->orWhere('phone2', 'like', "%{$search}%")
                    ->orWhere('numero_permis', 'like', "%{$search}%")
                    ->orWhere('mail', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->get()->map(function (Client $client) {
            return $this->transformClient($client);
        });

        return response()->json($clients);
    }

    /**
     * Persist a new client for the authenticated compagnie.
     */
    public function store(Request $request)
    {
        $this->authorizeCompany();

        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'numero_telephone' => 'required|string|max:255',
            'numero_telephone2' => 'nullable|string|max:255',
            'numero_telephone3' => 'nullable|string|max:255',
            'numero_permis' => 'nullable|string|max:255',
            'mail' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'addresse' => 'nullable|string',
        ]);

        $client = Client::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'] ?? null,
            'adresse' => $data['addresse'] ?? null,
            'compagnie_id' => Auth::user()->compagnie_id,
            'numero_permis' => $data['numero_permis'] ?? null,
            'phone1' => $data['numero_telephone'],
            'phone2' => $data['numero_telephone2'] ?? null,
            'phone3' => $data['numero_telephone3'] ?? null,
            'mail' => $data['mail'] ?? null,
            'ville' => $data['ville'] ?? null,
            'cashier_id' => $request->input('cashier_id'),
            'image_id' => $request->input('image_id') ?? null,
        ]);

        return response()->json($this->transformClient($client), 201);
    }

    protected function transformClient(Client $client): array
    {
        return [
            'id' => $client->id,
            'nom' => $client->nom,
            'prenom' => $client->prenom,
            'phone1' => $client->phone1,
            'phone2' => $client->phone2,
            'phone3' => $client->phone3,
            'mail' => $client->mail,
            'ville' => $client->ville,
            'numero_permis' => $client->numero_permis,
            'adresse' => $client->adresse,
            'nombre_locations' => $client->nombreLocations(),
            'chiffre_affaire' => $client->chiffreAffaire(),
            'paiements_percus' => $client->paiementsPercus(),
            'solde' => $client->solde(),
        ];
    }

    protected function authorizeCompany(): void
    {
        abort_unless(Auth::check() && Auth::user()->compagnie_id, 403, 'Compte compagnie requis.');
    }
}
