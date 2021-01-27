@extends('layouts.app')


@section('content')

    <voiture-show inline-template :voiture="{{ $voiture }}">
        <div>
            <!-- En Tête -->
            <h1 class="text-center mt-5">

                {{  $voiture->immatriculation }}

                @if (sizeof($voiture->pannesNonResolues()) > 0 )
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                @endif

            </h1>

            {{-- Bouttons de Fonctionnalité & Infos Voitures --}}
            <div class="container-fluid">
                <div class="row">
                    {{-- Boutons de Fonctionnalité --}}
                    <div class="col-12 col-lg-2">
                        <div class="row mt-5">

                            {{-- Réceptionner Véhicule Loué--}}
                            @if($voiture->etat == 'loué')
                                <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#receptionvehicule">
                                   <i class="fas fa-hands mr-2"></i> Réceptionner Véhicule
                                </button>
                            @endif
                            {{-- Réceptionner Véhicule En Maintenance--}}
                            @if($voiture->etat == 'maintenance')
                                <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#receptionvehiculemaintenance">
                                   <i class="fas fa-hands mr-2"></i> Réceptionner Véhicule De Maintenance
                                </button>
                            @endif

                            {{-- Voir Pannes --}}
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#voirPannes"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Voir Pannes</button>

                            {{-- Signaler Panne --}}
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#panneSignalisation"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Ajouter une Panne</button>


                            {{-- Envoyer en Maintenance --}}
                            <button type="button" class="btn btn-warning btn-block" @click="toggleMaintenanceModal({{ $voiture }})">
                                <i class="fas fa-tools text-danger mr-2"></i>
                                Envoyer en Maintenance
                            </button>

                        </div>
                            {{-- <button type="button" class="btn btn-primary">Voir Historique</button>
                            <button type="button" class="btn btn-primary">Faire Louer</button> --}}
                    </div>

                    {{-- Information Voiture --}}
                    <div class="col-md-8 col-12 offset-md-1 display-5">
                        <div class="row mt-5 text-danger">
                            <div class="col-4">Marque:</div>
                            <div class="col-4">Type:</div>
                            <div class="col-4">Immatriculation:</div>
                        </div>
                        <div class="row">
                            <div class="col">{{ $voiture->marque }}</div>
                            <div class="col">{{ $voiture->type }}</div>
                            <div class="col">{{ $voiture->immatriculation }}</div>
                        </div>
                        <div class="row mt-3 text-danger">
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

            <div class="container-fluid">
                <div class="row mt-5 display-5">
                    @if ( sizeof($contrats) > 0)
                        <div class="col-12 col-lg-6" >
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Contrat Nº</th>
                                        <th>Client</th>
                                        <th>Date du</th>
                                        <th>Date au</th>
                                    </tr>
                                </thead>
                                <tbody>

                                        @foreach ($contrats as $contrat)
                                            @if ($contrat->client)
                                                <tr>
                                                    <td scope="row"><a href="/contrat/{{ $contrat->id }}">{{ $contrat->numéro }}</a></td>
                                                    <td><a href="/clients/{{ $contrat->client->id }}">{{ $contrat->client->nom . ' ' . $contrat->client->prenom  }}</a></td>
                                                    <td>{{ $contrat->au->format('d-M-Y') }}</td>
                                                    <td>{{ $contrat->du->format('d-M-Y') }}</td>
                                                </tr>
                                            @endif

                                        @endforeach
                                            <tr>
                                                <td colspan="4" class="text-center">{{ $contrats->links() }}</td>
                                            </tr>
                                </tbody>
                            </table>

                        </div>
                    @endif


                    <div class="col-12  col-lg-6">
                        <div class="card text-center">
                            <div class="card-header">
                                <ul class="nav nav-pills card-header-pills">
                                    <li class="nav-item col-12 col-md-12 col-lg-4">
                                        <a class="nav-link active" data-toggle="tab" href="#pannesActuelles">Pannes Actuelles</a>
                                    </li>
                                    <li class="nav-item col-12 col-md-12 col-lg-4">
                                        <a class="nav-link" data-toggle="tab" href="#pannesRésolues">Pannes Résolues</a>
                                    </li>
                                    <li class="nav-item col-12 col-md-12 col-lg-4">
                                        <a class="nav-link" data-toggle="tab" href="#pannesEnMaintenance">Pannes en Maintenance</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body tab-content">
                                {{-- Pannes Actuelles --}}
                                <div id="pannesActuelles" class="tab-pane fade show active">

                                        {{-- Si il y a des pannes --}}

                                        @if (sizeof( $voiture->pannesNonResolues() ) > 0)

                                            {{-- Pour chaque panne ... --}}

                                            @foreach ($voiture->pannesNonResolues() as $panneNonResolue)

                                                {{-- Si la panne n'est pas résolue  --}}

                                                <ul class="list-group">
                                                    <li class="list-group-item">{{ $panneNonResolue->description }}</li>
                                                </ul>

                                            @endforeach

                                        @endif
                                </div>
                                <div id="pannesRésolues" class="tab-pane fade">

                                        {{-- Si il y a des pannes --}}

                                        @if (sizeof( $voiture->pannesResolues() ) > 0)

                                            {{-- Pour chaque panne ... --}}

                                            @foreach ($voiture->pannesResolues() as $panneResolue)

                                                {{-- Si la panne n'est pas résolue  --}}

                                                <ul class="list-group">
                                                    <li class="list-group-item">{{ $panneResolue->description }}</li>
                                                </ul>

                                            @endforeach

                                        @endif
                                </div>
                                <div id="pannesEnMaintenance" class="tab-pane fade">

                                        {{-- Si il y a des pannes --}}

                                        @if (sizeof( $voiture->pannesEnMaintenance() ) > 0)

                                            {{-- Pour chaque panne ... --}}

                                            @foreach ($voiture->pannesEnMaintenance() as $panneEnMaintenance)

                                                {{-- Si la panne n'est pas résolue  --}}

                                                <ul class="list-group">
                                                    <li class="list-group-item">{{ $panneEnMaintenance->description }}</li>
                                                </ul>

                                            @endforeach

                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="container-fluid">
                <div class="row mt-5">

                </div>

                {{-- Liste Contrat --}}
                <div class="container">

                </div>


                {{-- Modal Reception Voiture --}}
                @if(sizeof($voiture->contrats) > 0)
                    <controle-documents-accessoires :voiture="{{ $voiture }}"
                        :contrat="{{ $voiture->contrats[0] }}">
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
                                        @if($panne->etat === 'non-résolue' || $panne->etat === 'maintenance' )
                                            <li class="list-group-item">{{ $panne->description }}</li>
                                        @endif
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

            <!-- Modal Envoie Maintenance -->
            <div class="modal fade" id="envoieEnMaintenance">
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
                                @if ($panne->etat === 'non-résolue')
                                    <div class="form-check col-6">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="panne[]" value="{{ $panne->id }}">
                                        {{ $panne->description }}
                                      </label>
                                    </div>
                                @endif

                                @endforeach

                                <div class="form-group mt-3">
                                    <label for="">Technicien</label>
                                    <multiselect :options="{{ $techniciens }}" label="nom" v-model="technicien">
                                    </multiselect>
                                    <input type="hidden" :value="technicien.id" name="technicien" v-if="technicien">
                                    <input type="hidden" value="{{ $voiture->id }}" name="voiture" >
                                    <input type="hidden" value="{{ sizeof($voiture->pannes) }}" name="nombrePannes">
                                </div>
                                <div class="form-group">
                                  <label for="">Titre (Description)</label>
                                  <input type="text" class="form-control" name="titre" >
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
            <div class="modal fade" id="pasDePannes">
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

            <div class="modal fade" id="receptionvehiculemaintenance">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center">Réceptionner Véhicule de Maintenance</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @isset($derniere_maintenance)
                            <form action="/maintenances/{{ $derniere_maintenance->id }}/reception-véhicule" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <ul class="list-group">
                                        @foreach ($derniere_maintenance->pannes as $panne)
                                            <li class="list-group-item">
                                                <input type="checkbox" name="pannes[]" value="{{ $panne->id }}">
                                                {{ $panne->description }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="form-group mt-3">
                                        <label for="">Coût Maintenance</label>
                                        <input type="number" class="form-control" name="montant">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="">Coût Pièces Détachées</label>
                                        <input type="number" class="form-control" name="coût_pièces">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Receptionner Véhicule</button>
                                </div>
                            </form>
                        @endisset

                    </div>
                </div>
            </div>

        </div>

    </voiture-show>
@endsection
