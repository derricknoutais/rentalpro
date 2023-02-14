@extends('layouts.app')


@section('content')
    <contrats-index  inline-template :contrats="{{ json_encode($contrats) }}" env="{{ config('app.env') }}" :voitures_prop="{{ $voitures }}" :clients_prop="{{ $clients }}">
        <div>
            <h1 class="my-20 text-4xl text-center">Contrats</h1>

            <div class="container mx-auto">
                {{-- FILTRES --}}
                <form action="/contrats" method="GET" class="flex flex-col items-center px-10 py-10 bg-yellow-100">

                    <div class="flex items-center justify-center w-full">
                        <input type="hidden" name="voiture" v-model="filters.voiture.id">
                        <div class="w-1/4 form-group" >
                            <label for="">Voiture</label>
                            <multiselect :options="{{ $voitures }}" label="immatriculation" v-model="filters.voiture"></multiselect>
                        </div>
                        {{-- FILTRE CLIENT --}}
                        <input type="hidden" name="client" v-model="filters.client.id" >
                        <div class="w-1/4 ml-3 form-group">
                            <label for="">Client</label>
                            <multiselect :options="{{ $clients }}" allow-empty="true" label="nom_complet" v-model="filters.client">
                            </multiselect>
                        </div>
                        {{-- FILTRE ETAT CONTRAT --}}
                        <input type="hidden" name="etat" v-model="filters.etat">
                        <div class="w-1/4 ml-3 form-group">
                            <label for="">État Contrat</label>
                            <multiselect :options="['En cours', 'Terminé', 'Annulé', 'Soldé', 'Non-Soldé']" v-model="filters.etat">
                            </multiselect>
                        </div>

                    </div>
                    <div class="flex items-center justify-center w-full mt-3">
                        <div class="flex flex-col w-1/4 form-group">
                          <label for="">Du</label>
                          <input type="date" class="py-2 rounded-md" name="du">
                        </div>
                        <div class="flex flex-col w-1/4 ml-3 form-group">
                          <label for="">Au</label>
                          <input type="date" class="py-2 rounded-md" name="au">
                        </div>
                        <div class="flex items-center justify-center w-1/4">
                            <button type="submit" class="px-10 py-2 bg-yellow-300 rounded">Filtrer</button>
                        </div>
                    </div>

                </form>
                <table class="table mt-1">
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
                                        <div class="flex items-center px-2 py-2 bg-yellow-200">
                                            <a class="text-blue-400" href="/contrat/{{ $contrat->id }}" class="font-semibold ">
                                                {{ $contrat->numéro }}
                                            </a>
                                            <span></span>
                                        </div>
                                        {{-- Dates --}}
                                        <div class="flex px-2 py-3 mt-2 bg-yellow-100 rounded rounded-b-none">
                                            <span class="mr-1">
                                                Du:
                                            </span>
                                            <span class="px-5 text-white rounded bg-success">
                                                {{ $contrat->du->format('d-M-Y') }}
                                            </span>
                                            <span class="ml-3">
                                                Au :
                                            </span>
                                            @if ($contrat->au)
                                                <span class="px-5 mx-1 text-white rounded bg-danger" >
                                                    {{ $contrat->au->format('d-M-Y') }}
                                                </span>
                                            @else
                                                <span class="mx-1" >
                                                    <i class="fas fa-infinity "></i>
                                                </span>
                                            @endif
                                            <span class="mr-1">
                                                Soit:
                                            </span>
                                            <span class="px-5 text-white rounded bg-primary">
                                                {{ $contrat->nombre_jours }} Jours
                                                @if ($contrat->demi_journee)
                                                    1/2
                                                @endif
                                            </span>

                                        </div>
                                        {{-- Caution --}}
                                        <div class="flex px-2 py-3 mt-2 bg-yellow-100 rounded rounded-b-none">
                                            <span class="mr-1">
                                                Caution:
                                                @if (isset($contrat->caution))
                                                    <span class="px-2 ">
                                                        {{ $contrat->caution }}
                                                    </span>
                                                @endif
                                            </span>
                                            @if (isset($contrat->type_caution))
                                            <span class="px-5 text-white rounded bg-primary">
                                                {{ $contrat->type_caution }}
                                            </span>
                                            @endif


                                        </div>
                                        {{-- Montant Location --}}
                                        <div class="flex justify-between py-1 pl-2 mt-3 bg-blue-100 rounded rounded-b-none pr-14">
                                            <p class="font-semibold">Montant Location</p>
                                            <span class="font-semibold">{{ $contrat->prix_journalier * $contrat->nombre_jours }} F CFA</span>
                                        </div>
                                        {{-- Option 1/2 Journee --}}
                                        @if ($contrat->demi_journee)
                                            <div class="flex justify-between py-1 pl-2 bg-blue-100 rounded rounded-b-none pr-14">
                                                <p class="font-semibold">Option 1/2 Journee</p>
                                                <span class="font-semibold">{{ $contrat->demi_journee }} F CFA</span>
                                            </div>
                                        @endif

                                        {{-- Option Chauffeur --}}
                                        @if ($contrat->montant_chauffeur)
                                            <div class="flex justify-between py-1 pl-2 bg-blue-100 rounded rounded-b-none pr-14">
                                                <p class="font-semibold">Option Chauffeur</p>
                                                <span class="font-semibold">{{ $contrat->montant_chauffeur }} F CFA</span>
                                            </div>
                                        @endif

                                        {{-- Montant Total --}}
                                        <div class="flex justify-between py-1 pl-2 mt-1 bg-blue-200 rounded rounded-b-none pr-14">
                                            <p class="font-semibold">Montant Total</p>
                                            <span class="font-semibold">{{ $contrat->total() }} F CFA</span>
                                        </div>

                                        {{-- Paiements --}}
                                        @if (sizeof($contrat->paiements) > 0)
                                            <div class="flex flex-col justify-between py-1 pl-3 mt-2 pr-14 bg-green-100">

                                                <p class="mt-1 mb-3 font-semibold underline text-md">Paiements</p>

                                                @foreach ($contrat->paiements as $paiement)
                                                    <div class="flex justify-between">
                                                        <span >{{ $paiement->created_at->format('d-M-Y') }}</span>
                                                        @isset($paiement->type_paiement)

                                                        <span>{{ $paiement->type_paiement }}</span>
                                                        @endisset
                                                        <span>{{ $paiement->note }}</span>
                                                        <div>
                                                            <span class="font-semibold">{{ $paiement->montant }} F CFA</span>
                                                            @can('editer paiements')
                                                                <button class="mx-1 text-blue-400" data-toggle="modal" data-target="#updatePaiement{{ $paiement->id }}"><i class="fas fa-edit"></i></button>
                                                            @endcan
                                                            @can('supprimer paiements')
                                                                <button class="mr-1 text-red-400" data-toggle="modal" data-target="#supprimerModal"><i class="fas fa-trash"></i></button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                @endforeach


                                            </div>
                                        @endif
                                        {{-- Solde Si il y a Paiements --}}
                                        @if (sizeof($contrat->paiements) > 0 )
                                            <div class="flex justify-between py-1 bg-blue-200 rounded rounded-t-none px-14">
                                                <p class="font-semibold text-md">Solde</p>
                                                <div>
                                                    <span class="w-1/4 font-semibold"
                                                    >{{ $contrat->solde()}} F CFA</span>
                                                </div>
                                            </div>
                                        @endif
                                        {{-- Bouttons --}}
                                        @if ($contrat->deleted_at === NULL)
                                            <div class="flex px-1 py-3 bg-gray-100 rounded" >
                                                @if(! $contrat->gescash_transaction_id)
                                                    <a class="px-1 py-0 mr-2 text-white bg-blue-500 btn"
                                                        href="/contrat/{{ $contrat->id }}/envoyer-gescash"
                                                    >
                                                        Envoyer à Gescash
                                                    </a>
                                                @else
                                                    <a href="{{ env('GESCASH_BASE_URL') . '/transaction/' . $contrat->gescash_transaction_id }}">
                                                        <span class="mx-1 badge badge-pill badge-success">Envoyé à Gescash</span>
                                                    </a>
                                                @endif
                                                @can('créer paiement')
                                                    <button type="button" class="px-1 py-0 mr-2 text-white bg-blue-500 btn"
                                                        data-toggle="modal" data-target="#paiement{{ $contrat->id }}"
                                                        @click="showModal('paiement')"
                                                    >
                                                        Effectuer Paiement
                                                    </button>
                                                @endcan

                                                @if (! $contrat->demi_journee)
                                                    <button type="button" class="px-1 py-0 mr-2 text-white bg-blue-500 btn" data-toggle="modal"
                                                        data-target="#ajouter-demi-journee{{ $contrat->id }}" >
                                                        Ajouter 1/2 Journee
                                                    </button>
                                                @endif

                                                @if (! $contrat->montant_chauffeur)
                                                    <button type="button" class="px-1 py-0 mr-2 text-white bg-blue-500 btn" data-toggle="modal"
                                                        data-target="#ajouter-montant-chauffeur{{ $contrat->id }}">
                                                        Ajouter Chauffeur
                                                    </button>
                                                @endif

                                                <!-- Si le contrat n'est plus en cours -->
                                                @if( $contrat->real_check_out )
                                                    <span class="mx-1 badge badge-pill badge-success">Terminé</span>
                                                <!-- Si le contrat est en cours -->
                                                @else
                                                    @can('prolonger contrat')
                                                        <button type="button" class="px-1 py-0 mr-2 btn btn-primary btn-sm " data-toggle="modal" data-target="#prolongation{{ $contrat->id }}">
                                                            <i class="fas fa-clock"></i> Prolonger Contrat
                                                        </button>
                                                    @endcan
                                                    @can('editer contrat')
                                                        <button type="button" class="px-1 py-0 btn btn-secondary btn-sm" data-toggle="modal" data-target="#changervoiture{{ $contrat->id }}">
                                                            <i class="mr-1 fas fa-exchange-alt"></i> Changer Voiture
                                                        </button>
                                                    @endcan
                                                    <span class="ml-4 badge badge-pill badge-warning">En Location</span>
                                                @endif

                                                {{-- COMPAGNIE TYPE V --}}
                                                @if ( $compagnie->type === 'véhicules' )
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
                                                                                @isset($contrat->contractable)
                                                                                    <option value="{{ $contrat->contractable->id }}" selected>{{ $contrat->contractable->immatriculation }}</option>
                                                                                @endisset

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
                                                                                @isset($contrat->contractable)
                                                                                    <option value="{{ $contrat->contractable->id }}" selected>{{ $contrat->contractable->immatriculation }}</option>
                                                                                @endisset
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
                                        <!-- Modal Paiement -->
                                        <div class="modal fade" id="paiement{{ $contrat->id }}"
                                            tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                                            aria-hidden="true" >
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Paiement Contrat</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <livewire:payment-form :contrat="$contrat">
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal 1/2 Journee -->
                                        <div class="modal fade" id="ajouter-demi-journee{{ $contrat->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ajouter 1/2 Journee</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="/contrat/{{ $contrat->id }}/ajouter-demi-journee" method="POST">
                                                        @csrf
                                                        <div class="modal-body">

                                                            <div class="form-group">
                                                                <label for="">Montant</label>
                                                                <input type="number" step=5000 class="form-control" name="demi_journee"
                                                                    value="{{ $contrat->prix_journalier / 2 }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Ajouter 1/2 Journee</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Montant Chauffeur -->
                                        <div class="modal fade" id="ajouter-montant-chauffeur{{ $contrat->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="modelTitleId" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ajouter 1/2 Journee</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="/contrat/{{ $contrat->id }}/ajouter-montant-chauffeur" method="POST">
                                                        @csrf
                                                        <div class="modal-body">

                                                            <div class="form-group">
                                                                <label for="">Montant Total Chauffeur</label>
                                                                <input type="number" step=5000 class="form-control" name="montant_chauffeur"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Ajouter Chauffeur</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- Client --}}
                                    <td class="">
                                        {{-- Nom --}}
                                        <div class="flex flex-col p-2 bg-gray-300">
                                            <a class="text-blue-500" target="_blank" href="/clients/{{ $contrat->client->id }}">{{ $contrat->client['nom'] . ' ' . $contrat->client['prenom']}}</a>
                                        </div>

                                        {{-- Numéros de Téléphone --}}
                                        <div class="flex px-2 py-3 mt-2 bg-green-300 rounded-sm">
                                            <span class="">Nº Téléphone: {{ $contrat->client['phone1'] }}</span>
                                            @if ($contrat->client['phone2'])
                                                <span> / {{ $contrat->client['phone2'] }}</span>
                                            @endif
                                        </div>
                                        {{-- Adresse --}}
                                        <div class="flex flex-col px-2 py-1 mt-4 bg-green-600">
                                            Adresse:
                                            @if ($contrat->client->adresse)
                                                <span class="">{{ $contrat->client['adresse'] }}</span>
                                            @endif
                                        </div>
                                        {{-- Derniers Contrats --}}
                                        <div class="flex flex-col p-2">
                                            <p class="mb-2 text-center">Derniers Contrats</p>
                                            @foreach ($contrat->client->troisDerniersContrats() as $ct)
                                            <div class="flex justify-between">
                                                <a class="text-blue-500" target="_blank" href="/contrat/{{ $ct->id }}">
                                                    <span>
                                                        {{ $ct->numéro }}
                                                    </span>
                                                </a>
                                                <span>
                                                    Solde: {{ $ct->solde() }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    {{-- Contractable : Chambre Ou Hotel --}}
                                    @if ($compagnie->type === 'hôtel')
                                        <td class="">{{ $contrat->contractable->nom }}</td>
                                    @else
                                        <td>
                                            <div class="flex flex-col">
                                                <div class="flex flex-col p-2 bg-gray-300">
                                                <span>
                                                @isset($contrat->contractable)
                                                    {{ $contrat->contractable->immatriculation }}
                                                @endisset

                                                </span>
                                                </div>
                                                <div class="flex flex-col p-2 mt-3 bg-red-300">
                                                <span>
                                                    Créé par
                                                    @if (($activity = $contrat->activities->where('description', 'created')->first()))
                                                        {{ $activity->causer->name }} le {{ $activity->created_at->format('d-M-Y à H\Hi') }}
                                                    @endif
                                                </span>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                    {{-- Actions --}}
                                    <td class="flex flex-col">
                                        @if($contrat->deleted_at !== NULL)
                                            <span class="py-5 mt-5 text-center text-red-100 bg-red-400">
                                                <i class="fas fa-ban "></i>
                                                Contrat Annulé
                                            </span>
                                        @elseif($contrat->real_check_out !== NULL )
                                            <span class="py-5 mt-5 text-center text-white bg-green-400">
                                                Contrat Terminé
                                            </span>
                                        @else
                                            @can('terminer contrat')
                                                <button class="px-2 py-1 mt-5 bg-green-400 rounded text-green-50" data-toggle="modal" data-target="#terminerContrat{{ $contrat->id }}">
                                                    <i class="fas fa-ban "></i>
                                                    Terminer Contrat
                                                </button>
                                            @endcan
                                            @can('editer contrat')
                                                <a class="px-2 py-1 mt-2 text-center text-blue-100 no-underline bg-blue-400 rounded" href="/contrat/{{ $contrat->id }}/edit">
                                                    <i class="fas fa-edit "></i>
                                                    Editer
                                                </a>
                                            @endcan
                                            @can('annuler contrat')
                                                <button class="px-2 py-1 mt-2 text-red-100 bg-red-400 rounded" @click="annulerContrat({{ $contrat }})">
                                                    <i class="fas fa-ban "></i>
                                                    Annuler
                                                </button>
                                            @endcan

                                            <a target="_blank" class="px-2 py-1 mt-2 text-center text-white no-underline bg-purple-400 rounded" href="/contrat/{{ $contrat->id }}">
                                                <i class="fas fa-file-invoice "></i>
                                                Voir Contrat
                                            </a>

                                            <a class="px-2 py-1 mt-2 text-center text-white no-underline bg-gray-400 rounded" href="/contrat/{{ $contrat->id }}/print">
                                                <i class="fas fa-print "></i>
                                                Imprimer
                                            </a>

                                            <div class="modal fade" id="terminerContrat{{ $contrat->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="text-lg modal-title">Êtes-vous sûr de vouloir terminer ce contrat? </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="/contrat/{{ $contrat->id }}/terminer" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div>
                                                                    <input name="date_fin" type="date" class="form-control">
                                                                </div>
                                                                <p class="text-sm">
                                                                    L'heure et Date de Fin de Contrat seront enregistrées
                                                                    et ne pourront plus être modifiées ultérieurement
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="text-white bg-green-500 btn">Terminer</button>
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

                <div class="flex justify-center pt-10 pb-24">
                    {{ $contrats->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>

            <div class="sticky flex justify-end px-40 bottom-16">
                @if ($compagnie->type === 'véhicules')

                    <a href="/contrats/create">
                @else

                    <a href="/">
                @endif
                    <i class="text-green-700 cursor-pointer fas fa-plus-circle fa-5x hover:text-green-800"></i>
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
