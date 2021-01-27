@extends('layouts.app')

@section('content')
    <client-show inline-template>
        <div class="tw-container-fluid">
            <h1 class="text-center mt-5 tw-text-3xl">{{ $client->nom . ' ' . $client->prenom }} </h1>
            {{-- Boutons  --}}
            <div class="tw-flex tw-mt-10 tw-justify-between tw-bg-yellow-50 tw-py-10 tw-px-5">
                <div>
                    <button type="button" class="btn tw-bg-green-500 tw-text-white">
                        <i class="fas fa-plus "></i>
                        <span class="tw-ml-3">Ajouter Contrat</span>
                    </button>
                    <button type="button" class="btn tw-bg-blue-500 tw-text-white tw-ml-5">
                        <i class="fas fa-edit "></i>
                        <span class="tw-ml-3">Modifier Informations</span>
                    </button>
                </div>
                <div>
                    <button type="button" class="btn tw-bg-red-500 tw-text-white tw-ml-5">
                        <i class="fas fa-trash "></i>
                        <span class="tw-ml-3">Supprimer Client</span>
                    </button>
                </div>

            </div>
            <div class="tw-flex tw-mt-24 tw-justify-between tw-px-32">
                    <div class="tw-w-1/3">
                        <p class="tw-mt-2">
                            <span class="tw-underline">Nom:</span>
                            <span class="tw-font-semibold">{{ $client->nom }}</span>
                        </p>
                        <p class="tw-mt-2">
                            <span class="tw-underline">Numéro Phone 1:</span>
                            <span class="tw-font-semibold">{{ $client->phone1 }}</span>
                        </p>
                        <p class="tw-mt-2">
                            <span class="tw-underline">Addresse Mail:</span>
                            <span class="tw-font-semibold">{{ $client->mail }}</span>
                        </p>

                    </div>
                    <div class="tw-w-1/3">
                        <p class="tw-mt-2">
                            <span class="tw-underline">Nº Permis:</span>
                            <span class="tw-font-semibold">{{ $client->numero_permis }}</span>
                        </p>
                        <p class="tw-mt-2">
                            <span class="tw-underline">Adresse:</span>
                            <span class="tw-font-semibold">{{ $client->adresse }}</span>
                        </p>
                        <p class="tw-mt-2">
                            <span class="tw-underline">Nº Permis:</span>
                            <span class="tw-font-semibold">{{ $client->numero_permis }}</span>
                        </p>


                    </div>
                    <div class="tw-w-1/3">
                        <img src="/uploads/{{ $client->permis }}" width="100%" />
                    </div>

            </div>

            {{-- Contrats Clients --}}
            <div>
                <div class="container">
                    <h3 class="text-center mt-5">Liste des Contrats </h3>
                    <table class="table table-hover table-bordered mt-5">
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
                                        <td>
                                            <a href="/voiture/{{ $contrat->contractable->id }}">{{ $contrat->contractable->immatriculation }}</a>
                                        </td>
                                        @if($contrat->checkout)
                                            <td>{{ $contrat->au->format('d-M-Y')  }}</td>
                                        @else
                                            <td>d</td>
                                        @endif

                                        <td>{{ $contrat->du->format('d-M-Y') }}</td>
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
