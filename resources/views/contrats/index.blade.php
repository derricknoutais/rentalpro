@extends('layouts.app')


@section('content')
    <contrats-index  inline-template :contrats="{{ json_encode($contrats) }}" env="{{ config('app.env') }}">
        <div>
            <h1 class="text-center tw-text-3xl my-5">Contrats</h1>

            <div class="tw-container tw-mx-auto">

                <table class="table mt-5">
                    <thead>
                        <tr>
                            <th>Contrat Nº</th>
                            <th>Client</th>
                            <th>Voiture</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (sizeof($contrats) > 0)
                            @foreach ($contrats as $contrat)
                                <tr>

                                    {{-- Contrat --}}
                                    <td scope="row" class="">
                                        {{-- Numéro de Contrat --}}
                                        <div class="tw-flex tw-bg-yellow-100 tw-px-2 tw-py-2 tw-items-center">
                                            <a class="tw-text-blue-400" href="/contrat/{{ $contrat->id }}" class=" tw-font-semibold">
                                                {{ $contrat->numéro }}
                                            </a>
                                        </div>
                                        {{-- Dates --}}
                                        <div class="tw-flex tw-mt-2 tw-bg-yellow-200 tw-py-3 tw-px-2 tw-rounded tw-rounded-b-none">
                                            <span class="tw-mr-1">
                                                Du:
                                            </span>
                                            <span class="bg-success tw-px-5 tw-rounded tw-text-white">
                                                {{ $contrat->du->format('d-M-Y') }}
                                            </span>
                                            <span class="tw-ml-3">
                                                Au :
                                            </span>
                                            @if ($contrat->au)
                                                <span class="tw-mx-1 bg-danger tw-px-5 tw-rounded tw-text-white" >
                                                    {{ $contrat->au->format('d-M-Y') }}
                                                </span>
                                            @else
                                                <span class="tw-mx-1" >
                                                    <i class="fas fa-infinity "></i>
                                                </span>
                                            @endif
                                            <span class="tw-mr-1">
                                                Soit:
                                            </span>
                                            <span class="bg-primary tw-px-5 tw-rounded tw-text-white">
                                                {{ $contrat->nombre_jours }} Jours
                                            </span>

                                        </div>

                                        {{-- Montant Total --}}
                                        <div class="tw-flex tw-justify-between tw-mt-3 tw-py-1 tw-bg-blue-200 tw-px-14 tw-rounded tw-rounded-b-none">
                                            <p class="tw-font-semibold">Montant Total</p>
                                            <span class="tw-font-semibold">{{ $contrat->prix_journalier  * $contrat->nombre_jours}} F CFA</span>
                                        </div>

                                        {{-- Paiements --}}
                                        @if (sizeof($contrat->paiements) > 0)
                                            <div class="tw-flex tw-flex-col tw-justify-between tw-py-1 tw-bg-blue-100 tw-pr-6 tw-pl-14">

                                                <p class="tw-text-md tw-underline tw-font-semibold tw-mt-1 tw-mb-3">Paiements</p>

                                                @foreach ($contrat->paiements as $paiement)
                                                    <div class="tw-flex tw-justify-between">
                                                        <span >{{ $paiement->created_at->format('d-M-Y') }}</span>
                                                        <div class="tw-w-1/4">
                                                            <span class="tw-font-semibold">{{ $paiement->montant }} F CFA</span>
                                                            @can('editer paiements')
                                                                <button class="tw-text-blue-400 tw-mx-1" data-toggle="modal" data-target="#updatePaiement{{ $paiement->id }}"><i class="fas fa-edit"></i></button>
                                                            @endcan
                                                            @can('supprimer paiements')
                                                                <button class="tw-text-red-400 tw-mr-1" data-toggle="modal" data-target="#supprimerModal"><i class="fas fa-trash"></i></button>
                                                            @endcan
                                                        </div>
                                                    </div>

                                                    {{-- Modal Update Paiement --}}
                                                    <div class="modal fade" id="updatePaiement{{ $paiement->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Editer Paiement</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="/paiement/{{ $paiement->id }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="">Montant</label>
                                                                            <input type="number" class="form-control" name="montant" value="{{ $paiement->montant }}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="">Note</label>
                                                                            <textarea class="form-control" name="note" value="{{ $paiement->note }}"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" class="btn btn-primary">Editer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal Delete Paiement -->
                                                    <div class="modal fade" id="supprimerModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                                        <form action="/paiement/{{ $paiement->id }}" class="modal-dialog" role="document" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Êtes-vous sûr de Vouloir Supprimer le Paiement? </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                                                    <button type="submit" class="btn btn-primary">Oui</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endforeach


                                            </div>
                                        @endif

                                        {{-- Solde Si il y a Paiements --}}
                                        @if (sizeof($contrat->paiements) > 0 )
                                            <div class="tw-flex tw-justify-between tw-py-1 tw-bg-blue-200 tw-px-14 tw-rounded tw-rounded-t-none">
                                                <p class="tw-font-semibold tw-text-md">Solde</p>
                                                <div>
                                                    <span class="tw-font-semibold tw-w-1/4"
                                                    >{{ $contrat->solde()}} F CFA</span>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Bouttons --}}
                                        @if ($contrat->deleted_at === NULL)
                                            <div class="tw-flex tw-bg-gray-100 tw-px-1 tw-py-3 tw-rounded" >
                                                @can('créer paiement')
                                                    <button type="button" class="btn btn-primary btn-sm px-1 py-0 mr-2"
                                                        data-toggle="modal" data-target="#paiement{{ $contrat->id }}"
                                                    >
                                                        Effectuer Paiement
                                                    </button>
                                                @endcan

                                                <!-- Si le contrat n'est plus en cours -->
                                                @if( $contrat->real_check_out )
                                                    <span class="mx-1 badge badge-pill badge-success">Terminé</span>
                                                <!-- Si le contrat est en cours -->
                                                @else
                                                    @can('prolonger contrat')
                                                        <button type="button" class="btn btn-primary btn-sm px-1 py-0 mr-2 " data-toggle="modal" data-target="#prolongation{{ $contrat->id }}">
                                                            <i class="fas fa-clock"></i> Prolonger Contrat
                                                        </button>
                                                    @endcan
                                                    @can('editer contrat')
                                                        <button type="button" class="btn btn-secondary btn-sm px-1 py-0" data-toggle="modal" data-target="#changervoiture{{ $contrat->id }}">
                                                            <i class="fas fa-exchange-alt mr-1"></i> Changer Voiture
                                                        </button>
                                                    @endcan
                                                    <span class="badge badge-pill badge-warning ml-4">En Location</span>
                                                @endif

                                                {{-- COMPAGNIE TYPE V --}}
                                                @if ( $compagnie->type === 'véhicule' )
                                                    {{-- MODAL CHANGER VOITURE  --}}
                                                    <div class="modal fade" id="changervoiture{{ $contrat->id }}" tabindex="-1" role="dialog" >
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Changer Véhicule</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="/contrats/{{ $contrat->id }}/changer-voiture" method="POST" v-if="">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="">Sélectionner Voiture</label>
                                                                            <select class="custom-select" name="voiture">
                                                                                <option value="{{ $contrat->voiture->id }}" selected>{{ $contrat->voiture->immatriculation }}</option>

                                                                                @foreach ($voitures as $voiture)
                                                                                    @if($voiture->etat === 'disponible')
                                                                                        <option value="{{ $voiture->id }}">{{ $voiture->immatriculation }}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary" >Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- MODAL PROLONGATION --}}
                                                    <div class="modal fade" id="prolongation{{ $contrat->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Prolonger Contrat</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                </div>
                                                                <form action="/contrats/{{ $contrat->id }}/prolonger" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="">Nouvelle Date Prolongation</label>
                                                                            <input type="date" class="form-control" name="du">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="">Montant</label>
                                                                            <input type="number" step=5000 class="form-control" name="prix_journalier" value="{{ $contrat->prix_journalier }}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="">Sélectionner Voiture</label>
                                                                            <select class="custom-select" name="voiture">
                                                                                <option value="{{ $contrat->voiture->id }}" selected>{{ $contrat->voiture->immatriculation }}</option>
                                                                                @foreach ($voitures as $voiture)
                                                                                    @if($voiture->etat === 'disponible')
                                                                                        <option value="{{ $voiture->id }}">{{ $voiture->immatriculation }}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary" >Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        @endif


                                        <!-- Modal Prolongation Durée de Contrat -->
                                        <div class="modal fade" id="prolongation{{ $contrat->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Prolongation Durée de Contrat</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="/contrat/{{ $contrat->id }}/update" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="">Nouvelle Date</label>
                                                                <input type="date" class="form-control" name="date">
                                                            </div>
                                                            <div class="form-group">
                                                                @if ($compagnie->type === 'véhicules')
                                                                    <label for="">Nouveau Véhicule</label>
                                                                    <select type="date" class="form-control" name="contractable">
                                                                        <option value="{{ $contrat->contractable->id }}">{{ $contrat->contractable->immatriculation }}</option>
                                                                        @foreach ($contractablesDisponibles as $voiture)
                                                                            <option value="{{ $voiture->id }}">{{ $voiture->immatriculation }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @else
                                                                    <label for="">Nouvelle Chambre</label>
                                                                    <select type="date" class="form-control" name="contractable">
                                                                        <option value="{{ $contrat->contractable->id }}">{{ $contrat->contractable->nom }}</option>
                                                                        @foreach ($contractablesDisponibles as $chambre)
                                                                            <option value="{{ $chambre->id }}">{{ $chambre->nom }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif


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
                                        <!-- Modal Paiement -->
                                        <div class="modal fade" id="paiement{{ $contrat->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Paiement Contrat</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="/paiement" method="POST">
                                                        @csrf
                                                        <div class="modal-body">

                                                            <input type="hidden" class="form-control" name="contrat_id" value="{{ $contrat->id }}">

                                                            <div class="form-group">
                                                                <label for="">Montant</label>
                                                                <input type="number" class="form-control" name="montant">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Note</label>
                                                                <textarea type="number" class="form-control" name="note"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Paier</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>

                                    {{-- Client --}}
                                    <td class="">
                                        {{-- Nom --}}
                                        <div class="tw-flex tw-flex-col tw-bg-gray-300 tw-p-2">
                                            <a class="tw-text-blue-500" target="_blank" href="/clients/{{ $contrat->client->id }}">{{ $contrat->client['nom'] . ' ' . $contrat->client['prenom']}}</a>
                                        </div>

                                        {{-- Numéros de Téléphone --}}
                                        <div class="tw-flex tw-bg-green-300 tw-mt-2 tw-py-3 tw-px-2 tw-rounded-sm">
                                            <span class="">Nº Téléphone: {{ $contrat->client['phone1'] }}</span>
                                            @if ($contrat->client['phone2'])
                                                <span> / {{ $contrat->client['phone2'] }}</span>
                                            @endif
                                        </div>
                                        <div class="tw-flex tw-flex-col tw-bg-green-600 tw-mt-4 tw-py-1 tw-px-2">
                                            Adresse:
                                            @if ($contrat->client->adresse)
                                                <span class="">{{ $contrat->client['adresse'] }}</span>
                                            @endif
                                        </div>
                                        <div class="tw-flex tw-flex-col tw-p-2">
                                            <p class="tw-text-center tw-mb-2">Derniers Contrats</p>
                                            @foreach ($contrat->client->troisDerniersContrats() as $ct)
                                                <a class="tw-text-blue-500" target="_blank" href="/contrat/{{ $ct->id }}">{{ $ct->numéro }}</a>
                                            @endforeach
                                        </div>
                                        <div class="tw-flex tw-flex-col tw-bg-green-600 tw-mt-4 ">
                                            <a class="tw-bg-white tw-text-center" role="button" href="/client/{{ $contrat->client->id }}">Voir Plus de Détails</a>
                                        </div>
                                    </td>

                                    {{-- Contractable : Chambre Ou Hotel --}}
                                    @if ($compagnie->type === 'hôtel')
                                        <td class="">{{ $contrat->contractable->nom }}</td>
                                    @else
                                        <td class="">{{ $contrat->contractable->immatriculation }}</td>
                                    @endif

                                    {{-- Actions --}}
                                    <td class="tw-flex tw-flex-col">
                                        @if($contrat->deleted_at !== NULL)
                                            <span class="tw-bg-red-400 tw-text-red-100 tw-mt-5 tw-py-5 tw-text-center">
                                                <i class="fas fa-ban    "></i>
                                                Contrat Annulé
                                            </span>
                                        @elseif($contrat->real_check_out !== NULL )
                                            <span class="tw-bg-green-400 tw-text-white tw-mt-5 tw-py-5 tw-text-center">
                                                Contrat Terminé
                                            </span>
                                        @else
                                            @can('terminer contrat')
                                                <button class="tw-bg-green-400 tw-py-1 tw-px-2 tw-text-green-50 tw-mt-5 tw-rounded" @click="terminerContrat({{ $contrat }})" data-toggle="modal" data-target="#terminerContrat{{ $contrat->id }}">
                                                    <i class="fas fa-ban    "></i>
                                                    Terminer Contrat
                                                </button>
                                            @endcan
                                            @can('editer contrat')
                                                <a class="tw-no-underline tw-bg-blue-400 tw-text-blue-100 tw-py-1 tw-px-2 tw-mt-2 tw-rounded tw-text-center" href="/contrat/{{ $contrat->id }}/edit">
                                                    <i class="fas fa-edit    "></i>
                                                    Editer
                                                </a>
                                            @endcan
                                            @can('annuler contrat')
                                                <button class="tw-bg-red-400 tw-py-1 tw-px-2 tw-text-red-100 tw-mt-2 tw-rounded" @click="annulerContrat({{ $contrat }})">
                                                    <i class="fas fa-ban    "></i>
                                                    Annuler
                                                </button>
                                            @endcan

                                            <button class="tw-bg-purple-400 tw-py-1 tw-px-2 tw-mt-2 tw-rounded tw-text-white" @click="annulerContrat({{ $contrat }})">
                                                <i class="fas fa-print "></i>
                                                Imprimer
                                            </button>

                                            <a class="tw-bg-gray-400 tw-py-1 tw-px-2 tw-mt-2 tw-rounded tw-text-white tw-text-center" href="/contrat/{{ $contrat->id }}/download">
                                                <i class="fas fa-download    "></i>
                                                Télécharger
                                            </a>

                                            <div class="modal fade" id="terminerContrat{{ $contrat->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title tw-text-lg">Êtes-vous sûr de vouloir terminer ce contrat? </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="/contrat/{{ $contrat->id }}/terminer" method="GET">
                                                            <div class="modal-body">
                                                                <p class="tw-text-sm">
                                                                    L'heure et Date de Fin de Contrat seront enregistrées
                                                                    et ne pourront plus être modifiées ultérieurement
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn tw-bg-green-500 tw-text-white">Terminer</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="tw-flex tw-pb-24 tw-pt-10 tw-justify-center">
                    {{ $contrats->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>

            <div class="tw-sticky tw-bottom-16 tw-justify-end tw-flex tw-container-fluid tw-px-40">
                @if ($compagnie->type === 'véhicules')

                    <a href="/contrats/create">
                @else

                    <a href="/">
                @endif
                    <i class="fas fa-plus-circle fa-5x tw-text-green-700 hover:tw-text-green-800 tw-cursor-pointer"></i>
                </a>
            </div>

        </div>
    </contrats-index>


@endsection

@section('js')
    <script>
        document.getElementById('#flash-overlay-modal').modal();
        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    </script>
@endsection
