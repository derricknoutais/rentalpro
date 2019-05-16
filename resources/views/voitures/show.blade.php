@extends('layouts.app')


@section('content')

    <voiture-show inline-template :voiture="{{ $voiture }}">
        <div>
            <!-- En Tête -->
            <h1 class="text-center mt-5">
                
                {{  $voiture->immatriculation }}
                @if (sizeof($voiture->pannes) > 0 )
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                @endif
            </h1>
            <div class="container-fluid">
                <div class="row">
                    {{-- Boutons de Fonctionnalité --}}
                    <div class="col-2">
                        <div class="row mt-5">

                            {{-- Réceptionner Véhicule --}}
                            @if($voiture->etat == 'loué')
                                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#receptionvehicule">
                                   <i class="fas fa-hands mr-2"></i> Réceptionner Véhicule
                                </button>
                            @endif

                            {{-- Voir Pannes --}}
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#voirPannes"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Voir Pannes</button>
                            
                            {{-- Signaler Panne --}}
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#panneSignalisation"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Ajouter une Panne</button>
                            
                            
                            {{-- Envoyer en Maintenance --}}
                            <button type="button" class="btn btn-warning btn-block" @click="toggleMaintenanceModal({{ $voiture }})"><i class="fas fa-tools text-danger mr-2"></i>Envoyer en Maintenance</button>
                        
                        </div>
                            {{-- <button type="button" class="btn btn-primary">Voir Historique</button>
                            <button type="button" class="btn btn-primary">Faire Louer</button> --}}
                    </div>

                    {{-- Information Voiture --}}
                    <div class="col-8 offset-1">
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

                    

                </div>
            </div>
            <div class="container">
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
                                    <tr>
                                        <td scope="row"><a href="/contrat/{{ $contrat->id }}">{{ $contrat->numéro }}</a></td>
                                        <td><a href="/clients/{{ $contrat->client->id }}">{{ $contrat->client->nom . ' ' . $contrat->client->prenom  }}</a></td>
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
                    <controle-documents-accessoires :voiture="{{ $voiture }}" 
                        :contrat="{{ $voiture->contrats[sizeof($voiture->contrats) - 1] }}">
                    </controle-documents-accessoires>
                @endif           

            </div>

            <!-- Modal Pannes -->
            <div class="modal fade" id="panneSignalisation" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Signalisation Panne</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/voitures/{{ $voiture->id }}/ajoute-pannes" method="POST">
                            @csrf
                            <div class="modal-body">
                                <button type="button" class="btn btn-primary mb-3" @click="incrementNombrePannes"><i class="fas fa-plus-circle"></i> Ajouter Panne</button>
                                <input type="hidden" class="form-control" placeholder="Panne" name="nombrePannes" v-model="nombrePannes">
                                <div class="form-group" v-for="(nombrePanne, index) in nombrePannes">
                                    <input type="text" class="form-control" placeholder="Panne" :name="'panne' + index ">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Voir Pannes -->
            <div class="modal fade" id="voirPannes" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Signalisation Panne</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                            <div class="modal-body">
                                <ul class="list-group">
                                    @foreach ($voiture->pannes as $panne)
                                        <li class="list-group-item">{{ $panne->description }}</li>   
                                    @endforeach
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                {{-- Signaler Panne --}}
                                <button type="button" class="btn btn-danger btn-block" data-dismiss="modal" data-toggle="modal" data-target="#panneSignalisation"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Ajouter une Panne</button>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Modal Maintenance -->
            <div class="modal fade" id="envoieEnMaintenance" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sélectionner Pannes</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/maintenances/store" method="POST">
                            @csrf
                            <div class="modal-body">
                                @foreach ($voiture->pannes as $panne)
                                    <div class="form-check col-6">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="panne{{ $loop->iteration }}" value="{{ $panne->id }}">
                                        {{ $panne->description }}
                                      </label>
                                    </div>
                                @endforeach

                                <div class="form-group mt-3">
                                    <label for="">Technicien</label>
                                    <multiselect :options="{{ $techniciens }}" label="nom" v-model="technicien">
                                    </multiselect>
                                    <input type="hidden" :value="technicien.id" name="technicien" v-if="technicien">
                                    <input type="hidden" value="{{ $voiture->id }}" name="voiture" >
                                    <input type="hidden" value="{{ sizeof($voiture->pannes) }}" name="nombrePannes">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                            
                    </div>
                </div>
            </div>
            
            <!-- Modal -->
            <div class="modal fade" id="pasDePannes" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger float-right"></i>
                            <h5 class="modal-title text-center">Erreur </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <p>Aucune Panne Signalée. Veuillez Ajoutez une Panne.</p>
                            <p>L'invite de Signalisation de Pannes s'ouvrira automatiquement dans @{{ timer }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </voiture-show>
@endsection