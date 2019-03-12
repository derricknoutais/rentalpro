@extends('layouts.app')


@section('content')

    <voiture-show inline-template :voiture="{{ $voiture }}">
        <div class="container">
            <h1 class="text-center">{{ $voiture->marque . ' ' . $voiture->type . ' (' . $voiture->immatriculation . ')'}}</h1>
            
            {{-- Information Voiture --}}
            <div>
                <div class="row mt-5">
                    <div class="col">Marque:</div>
                    <div class="col">Type:</div>
                    <div class="col">Immatriculation:</div>
                </div>
                <div class="row">
                    <div class="col">{{ $voiture->marque }}</div>
                    <div class="col">{{ $voiture->type }}</div>
                    <div class="col">{{ $voiture->immatriculation }}</div>
                </div>
                <div class="row mt-3">
                    <div class="col">Nº Chassis</div>
                    <div class="col">Année</div>
                    <div class="col">Nombre Locations</div>
                </div>
                <div class="row">
                    <div class="col">{{ $voiture->chassis }}</div>
                    <div class="col">{{ $voiture->annee }}</div>
                    <div class="col"></div>
                </div>
            </div>
            
            {{-- Boutons de Fonctionnalité --}}
            <div class="row mt-5">
                <div class="col">
                    <button type="button" class="btn btn-primary">Voir Historique</button>
                    <button type="button" class="btn btn-primary">Faire Louer</button>
                    @if($voiture->etat == 'loué')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#receptionvehicule">Réceptionner Véhicule</button>
                    @endif
                    <button type="button" class="btn btn-primary" @click="envoyerEnMaintenance()">Envoyer en Maintenance</button>
                </div>
            </div>

            
            <div class="row mt-5">
            </div>
            
            {{-- Liste Contrat --}}
            <div class="container">
                <h3 class="text-center mt-5">Liste Contrat</h3>
                <table class="table table-hover table-bordered mt-5">
                    <thead>
                        <tr>
                            <th>Contrat Nº</th>
                            <th>Client</th>
                            <th>Date du</th>
                            <th>Date au</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($voiture->contrats)
                            @foreach ($voiture->contrats as $contrat)
                                <tr class="pointer" @click="relocateTo('/contrat/{{ $contrat->id }}')">
                                    <td scope="row">{{ $contrat->numéro }}</td>
                                    <td>{{ $contrat->client->nom }}</td>
                                    <td>{{ $contrat->check_out->format('d-M-Y') }}</td>
                                    <td>{{ $contrat->check_in->format('d-M-Y') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            

            {{-- Modal Reception Voiture --}}
            @if(sizeof($voiture->contrats) > 0)
                <controle-documents-accessoires :voiture="{{ $voiture }}" :contrat="{{ $voiture->contrats[sizeof($voiture->contrats) - 1] }}">
                    
                </controle-documents-accessoires>
            @endif           

        </div>
    </voiture-show>
@endsection