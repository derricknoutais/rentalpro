@extends('layouts.app')

@section('content')
    <client-show inline-template :client_prop="{{ $client }}">
        <div class="container-fluid">
            <h1 class="mt-5 text-3xl text-center">{{ $client->nom . ' ' . $client->prenom }} </h1>
            {{-- Boutons  --}}
            <div class="flex justify-between px-5 py-10 mt-10 bg-yellow-50">
                <div>
                    <a href="/contrats/create?client_id={{ $client->id }}" class="text-white bg-green-500 btn">
                        <i class="fas fa-plus "></i>
                        <span class="ml-3">Ajouter Contrat</span>
                    </a>
                    <a href="/clients/{{ $client->id }}/edit" class="ml-5 text-white bg-blue-500 btn">
                        <i class="fas fa-edit "></i>
                        <span class="ml-3">Modifier Informations</span>
                    </a>
                </div>
                <div>
                    <button type="button" class="ml-5 text-white bg-red-500 btn" @click="deleteClient">
                        <i class="fas fa-trash "></i>
                        <span class="ml-3">Supprimer Client</span>
                    </button>
                </div>

            </div>
            <div class="flex justify-between px-32 mt-24">
                    <div class="w-1/3">
                        <p class="mt-2">
                            <span class="underline">Nom:</span>
                            <span class="font-semibold">{{ $client->nom }}</span>
                        </p>
                        <p class="mt-2">
                            <span class="underline">Numéro Phone 1:</span>
                            <span class="font-semibold">{{ $client->phone1 }}</span>
                        </p>
                        <p class="mt-2">
                            <span class="underline">Addresse Mail:</span>
                            <span class="font-semibold">{{ $client->mail }}</span>
                        </p>

                    </div>
                    <div class="w-1/3">
                        <p class="mt-2">
                            <span class="underline">Nº Permis:</span>
                            <span class="font-semibold">{{ $client->numero_permis }}</span>
                        </p>
                        <p class="mt-2">
                            <span class="underline">Adresse:</span>
                            <span class="font-semibold">{{ $client->adresse }}</span>
                        </p>
                        <p class="mt-2">
                            <span class="underline">Nº Permis:</span>
                            <span class="font-semibold">{{ $client->numero_permis }}</span>
                        </p>


                    </div>
                    <div class="w-1/3">
                        @if ($client->image)
                            <img src="/app/public/images/{{ $client->image->name }}" alt="">
                        @endif
                        {{-- <img src="/uploads/{{ $client->permis }}" width="100%" /> --}}
                    </div>

            </div>

            {{-- Contrats Clients --}}
            <div>
                <div class="container">
                    <h3 class="mt-5 text-center">Liste des Contrats </h3>
                    <table class="table mt-5 table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Contrat Nº</th>
                                @if ($client->compagnie->type === 'véhicules')
                                    <th>Immatriculation</th>
                                @else
                                    <th>Numéro Chambre</th>
                                @endif
                                <th>Date du</th>
                                <th>Date au</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($client->contrats as $contrat)
                                @if ($contrat->compagnie->type === 'véhicules')
                                    <tr class="pointer" @click="relocateTo('/contrat/{{ $contrat->id }}')">
                                        <td scope="row">
                                            <a href="/contrat/{{ $contrat->id }}">{{ $contrat->numéro }}</a>
                                        </td>
                                        @if ($contrat->contractable)    
                                            <td>
                                                <a href="/voiture/{{ $contrat->contractable->id }}">{{ $contrat->contractable->immatriculation }}</a>
                                            </td>
                                        @else
                                            <td>Voiture Supprimée</td>
                                        @endif
                                        @if($contrat->checkout)
                                            <td>{{ $contrat->au->format('d-M-Y')  }}</td>
                                        @else
                                            <td>{{ $contrat->du->format('d-M-Y') }}</td>
                                        @endif
                                        <td>{{ $contrat->au->format('d-M-Y') }}</td>
                                    </tr>
                                @else
                                    <tr class="pointer" @click="relocateTo('/contrat/{{ $contrat->id }}')">
                                        <td scope="row">
                                            <a href="/contrat/{{ $contrat->id }}">{{ $contrat->numéro }}</a>
                                        </td>
                                        <td>
                                            <a href="/voiture/{{ $contrat->contractable->id }}">{{ $contrat->contractable->immatriculation }}</a>
                                        </td>
                                        @if($contrat->checkout)
                                            <td>{{ $contrat->au->format('d-M-Y')  }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <td>{{ $contrat->du->format('d-M-Y') }}</td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </client-show>
    
@endsection
