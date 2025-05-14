@extends('layouts.app')


@section('content')
    <contrats-index inline-template :contrats_prop="{{ json_encode($contrats) }}" env="{{ config('app.env') }}"
        :voitures_prop="{{ $contractables }}" :clients_prop="{{ $clients }}">
        <div class="w-full ">
            <h1 class="sm:md:my-2 lg:my-20 text-4xl text-center">Contrats</h1>


            <div class="w-full mx-auto">
                {{-- FILTRES --}}
                <form class="flex flex-col items-center px-10 py-10 bg-yellow-100" action="/contrats" method="GET">

                    <div class="sm:md:flex sm:md:flex-col lg:flex items-center justify-center w-full">
                        @if ($compagnie->isVehicules())
                            <input type="hidden" name="voiture" v-model="filters.voiture.id">
                        @else
                            <input type="hidden" name="chambre" v-model="filters.chambre.id">
                        @endif

                        <div class="w-full lg:w-1/4 form-group">
                            <label for="">
                                @if ($compagnie->isVehicules())
                                    Voiture
                                @else
                                    Chambre
                                @endif

                            </label>
                            <multiselect :options="{{ $contractables }}"
                                @if ($compagnie->isVehicules()) label="immatriculation"
                                    v-model="filters.voiture"
                                @else
                                   label="nom"
                                   v-model="filters.chambre" @endif>
                            </multiselect>
                        </div>
                        {{-- FILTRE CLIENT --}}
                        <input type="hidden" name="client" v-model="filters.client.id">
                        <div class="w-full lg:w-1/4 lg:ml-3 form-group">
                            <label for="">Client</label>
                            <multiselect :options="{{ $clients }}" label="nom_complet" v-model="filters.client">
                            </multiselect>
                        </div>
                        {{-- FILTRE ETAT CONTRAT --}}
                        <input type="hidden" name="etat" v-model="filters.etat">
                        <div class="w-full lg:w-1/4 lg:ml-3 form-group">
                            <label for="">État Contrat</label>
                            <multiselect :options="['En cours', 'Terminé', 'Annulé', 'Soldé', 'Non-Soldé']"
                                v-model="filters.etat">
                            </multiselect>
                        </div>

                    </div>
                    <div class="sm:md:flex sm:md:flex-col items-center justify-center lg:flex w-full mt-3">
                        <div class="flex flex-col w-full lg:w-1/4 lg:ml-3 form-group">
                            <label for="">Du</label>
                            <input type="date" class="py-2 rounded-md" name="du">
                        </div>
                        <div class="flex flex-col w-full lg:w-1/4 lg:ml-3 form-group">
                            <label for="">Au</label>
                            <input type="date" class="py-2 rounded-md" name="au">
                        </div>
                        <div class="flex items-center justify-center w-full lg:w-1/4 sm:md:mt-2">
                            <button type="submit" class="px-10 py-2 bg-yellow-300 rounded">Filtrer</button>
                        </div>
                    </div>

                </form>
                {{-- CONTRATS --}}



                <div class="w-full ">

                    <div v-for="contrat in contrats">

                        <div scope="row" class="lg:flex lg:justify-between">


                            {{-- Contrat --}}
                            <div class="lg:w-1/2 lg:mr-1 ">
                                {{-- Numéro de Contrat --}}
                                <div class="flex items-center sm:mt-5 px-2 py-2 bg-yellow-200 ">
                                    <a class="text-blue-400" :href="'/contrat/' + contrat.id" class="font-semibold ">
                                        @{{ contrat.numéro }}
                                    </a>
                                    <span></span>
                                </div>
                                {{-- Dates --}}
                                <div
                                    class="flex items-center px-2 sm:py-2 lg:py-3  lg:mt-2 bg-yellow-100 rounded rounded-b-none">
                                    <span class="mr-1">
                                        Du:
                                    </span>
                                    <span class="px-3 text-white rounded bg-success">
                                        @{{ contrat.du | moment('DD MMM YYYY') }}
                                    </span>
                                    <span class="ml-3">
                                        Au :
                                    </span>
                                    <span class="px-3 mx-1 text-white rounded bg-danger" v-if="contrat.au">
                                        @{{ contrat.au | moment('DD MMM YYYY') }}
                                    </span>
                                    <span class="mr-1">
                                        Soit:
                                    </span>
                                    <span class="sm:px-3 lg:px-5 text-white rounded bg-primary">
                                        @{{ contrat.nombre_jours }} Jours
                                        <span v-if="contrat.demi_journee">
                                            1/2
                                        </span>
                                    </span>

                                </div>
                                {{-- Caution --}}
                                <div class="flex px-2 py-2 lg:py-3 sm:mt-5 bg-yellow-100 rounded rounded-b-none">
                                    <span class="mr-1">
                                        Caution:

                                        <span class="px-2 " v-if="contrat.caution">
                                            @{{ contrat.caution }}
                                        </span>

                                    </span>

                                    <span class="px-5 text-white rounded bg-primary" v-if="contrat.type_caution">
                                        @{{ contrat.type_caution }}
                                    </span>



                                </div>
                                {{-- Montant Location --}}
                                <div
                                    class="flex justify-between py-1 pl-2 sm:mt-0 lg:mt-3 bg-blue-100 rounded rounded-b-none pr-14">
                                    <p class="font-semibold">Montant Location</p>
                                    <span class="font-semibold">@{{ contrat.prix_journalier * contrat.nombre_jours }} F CFA</span>
                                </div>
                                {{-- Option 1/2 Journee --}}
                                <div class="flex justify-between py-1 pl-2 bg-blue-100 rounded rounded-b-none pr-14"
                                    v-if="contrat.demi_journee">
                                    <p class="font-semibold">Option 1/2 Journee</p>
                                    <div>
                                        <span class="font-semibold">@{{ contrat.demi_journee }} F CFA</span>
                                        @can('editer paiements')
                                            <button class="mx-1 text-blue-400" data-toggle="modal"
                                                data-target="#editer-demi-journee-modal"
                                                @click="passDataToModal('contrat', contrat)"><i
                                                    class="fas fa-edit"></i></button>
                                        @endcan
                                        @can('supprimer paiements')
                                            <button class="ml-1 text-red-400"
                                                @click="deleteData('/contrat/' + contrat.id + '/delete/demi_journee')"><i
                                                    class="fas fa-trash"></i></button>
                                        @endcan
                                    </div>
                                </div>
                                {{-- Option Chauffeur --}}
                                <div class="flex justify-between py-1 pl-2 bg-blue-100 rounded rounded-b-none pr-14"
                                    v-if="contrat.montant_chauffeur">
                                    <p class="font-semibold">Option Chauffeur</p>
                                    <div>
                                        <span class="font-semibold">@{{ contrat.montant_chauffeur }} F CFA</span>
                                        @can('editer paiements')
                                            <button class="mx-1 text-blue-400" data-toggle="modal"
                                                data-target="#editer-montant-chauffeur-modal"
                                                @click="passDataToModal('contrat', contrat)"><i
                                                    class="fas fa-edit"></i></button>
                                        @endcan
                                        @can('supprimer paiements')
                                            <button class="ml-1 text-red-400"
                                                @click="deleteData('/contrat/' + contrat.id + '/delete/montant_chauffeur')"><i
                                                    class="fas fa-trash"></i></button>
                                        @endcan
                                    </div>

                                </div>
                                {{-- Montant Total --}}
                                <div
                                    class="flex justify-between py-1 pl-2 sm:mt-0 lg:mt-1 bg-blue-200 rounded rounded-b-none pr-14">
                                    <p class="font-semibold">Montant Total</p>
                                    <span class="font-semibold">@{{ total(contrat) }} F CFA</span>
                                </div>
                                {{-- Paiements --}}
                                <div class="flex flex-col justify-between py-1 pl-3 mt-2 pr-14 bg-green-100"
                                    v-if="contrat.paiements.length > 0">

                                    <p class="mt-1 mb-3 font-semibold underline text-md">Paiements</p>


                                    <div class="flex justify-between" v-for="paiement in contrat.paiements">
                                        <span>@{{ paiement.created_at | moment('DD MMM YYYY') }}</span>


                                        <span v-if="paiement.type_paiement">@{{ paiement.type_paiement }}</span>

                                        <span v-if="paiement.note">@{{ paiement.note }}</span>
                                        <div>
                                            <span class="font-semibold">@{{ paiement.montant }} F CFA</span>
                                            @can('editer paiements')
                                                <button class="mx-1 text-blue-400" data-toggle="modal"
                                                    data-target="#editPaiementModal"
                                                    @click="passDataToModal('paiement', paiement)"><i
                                                        class="fas fa-edit"></i></button>
                                            @endcan
                                            @can('supprimer paiements')
                                                <button class="ml-1 text-red-400" @click="deletePayment(paiement)"><i
                                                        class="fas fa-trash"></i></button>
                                            @endcan
                                        </div>
                                    </div>



                                </div>
                                {{-- Solde Si il y a Paiements --}}
                                <div class="flex justify-between py-1 bg-blue-200 rounded rounded-t-none px-14"
                                    v-if="contrat.paiements.length > 0">
                                    <p class="font-semibold text-md">Solde</p>
                                    <div>
                                        <span class="w-1/4 font-semibold">@{{ solde(contrat) }} F CFA</span>
                                    </div>
                                </div>
                                {{-- Bouttons --}}
                                <div class="sm:hidden md:hidden lg:flex px-1 py-3 bg-gray-100 rounded"
                                    v-if="contrat.deleted_at === null">

                                    @can('créer paiement')
                                        <button type="button" class="px-1 py-0 mr-2 text-white bg-blue-500 btn"
                                            data-toggle="modal" data-target="#ajouter-paiement-modal"
                                            @click="displayModal('paiement',contrat)">
                                            Effectuer Paiement
                                        </button>
                                    @endcan


                                    {{-- <button type="button" class="sm:hidden px-1 py-0 mr-2 text-white bg-blue-500 btn"
                                    data-toggle="modal" data-target="#demi-journee-modal"
                                    @click="passDataToModal('contrat', contrat)" v-if="! contrat.demi_journee">
                                    Ajouter 1/2 Journee
                                    </button> --}}

                                    {{-- <button type="button" v-if="! contrat.montant_chauffeur"
                                        class=" px-1 py-0 mr-2 text-white bg-blue-500 btn" data-toggle="modal"
                                        data-target="#montant-chauffeur-modal" @click="passDataToModal('contrat', contrat)">
                                        Ajouter Chauffeur
                                    </button> --}}

                                    <!-- Si le contrat n'est plus en cours -->



                                    <!-- Si le contrat est en cours -->

                                    @can('prolonger contrat')
                                        <button type="button" class="px-1 py-0 mr-2 btn btn-primary btn-sm "
                                            data-toggle="modal" data-target="#prolongation-modal"
                                            @click="displayModal('prolongation',contrat)">
                                            <i class="fas fa-clock"></i> Prolonger Contrat
                                        </button>
                                    @endcan
                                    @can('editer contrat')
                                        <button type="button" class="px-1 py-0 btn btn-secondary btn-sm"
                                            @click="displayModal('changer-voiture',contrat)">
                                            <i class="mr-1 fas fa-exchange-alt"></i> Changer Voiture
                                        </button>
                                    @endcan
                                    {{-- <span class="ml-4 badge badge-pill badge-warning">En Location</span> --}}


                                </div>
                            </div>


                            {{-- Client --}}
                            <div class="lg:w-1/6 lg:mx-1">
                                <template v-if="contrat.client">

                                    <div class="flex flex-col sm:mt-5 px-2 py-2 lg:p-2 bg-green-600">
                                        <a class="text-blue-200" target="_blank" :href="'/clients/' + contrat.client.id">
                                            @{{ contrat.client['nom'] + ' ' + contrat.client['prenom'] }}
                                        </a>
                                    </div>
                                    <div class="flex px-2 sm:py-1 lg:py-3 sm:mt-0 lg:mt-2 bg-green-300 rounded-sm">
                                        <span class="">Nº Téléphone: @{{ contrat.client['phone1'] }}</span>
                                        <span v-if="contrat.client['phone2']"> / @{{ contrat.client['phone2'] }}</span>
                                        <span v-if="contrat.client['phone3']"> / @{{ contrat.client['phone3'] }}</span>
                                    </div>
                                    <div class="flex flex-col px-2 py-1 sm:mt-0 lg:mt-4 bg-green-300">
                                        Adresse:

                                        <span class="" v-if="contrat.client.adresse">@{{ contrat.client['adresse'] }}</span>

                                    </div>
                                    {{-- Derniers Contrats --}}
                                    <div class="sm:hidden lg:flex lg:flex-col p-2">
                                        <p class="mb-2 text-center">Derniers Contrats</p>

                                        <div class="flex justify-between" v-for="ct in contrat.client.derniers_contrats">
                                            <a class="text-blue-500" target="_blank" :href="'/contrat/' + ct.id">
                                                <span>
                                                    @{{ ct.numéro }}
                                                </span>
                                            </a>
                                            <span>
                                                Solde: @{{ solde(ct) }}
                                            </span>
                                        </div>

                                    </div>
                                </template>
                            </div>


                            {{-- Contractable : Chambre Ou Hotel --}}
                            <div class="lg:w-1/6 lg:mx-1">
                                <div class="flex flex-col sm:mt-5">
                                    <div class="flex flex-col p-2 bg-gray-300">
                                        <a class="text-blue-500" :href="'/contractables/' + contrat.contractable.id"
                                            v-if="contrat.contractable">

                                            @{{ contrat.contractable.nom }}

                                        </a>
                                    </div>
                                    <div class="flex flex-col p-2 sm:mt-0 lg:mt-3 bg-red-300">
                                        <span>
                                            Créé par
                                            <span v-if="contrat.activity">
                                                @{{ contrat.activity.causer.name }}
                                                @{{ contrat.activity.created_at }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="sm:hidden md:hidden lg:flex lg:flex-col lg:w-1/6 lg:ml-1">

                                <span class="py-5 sm:mt-5 lg:mt-5 text-center text-red-100 bg-red-400"
                                    v-if="contrat.deleted_at">
                                    <i class="fas fa-ban "></i>
                                    Contrat Annulé
                                </span>

                                <span class="py-5 sm:mt-5 lg:mt-5 text-center text-white bg-green-400"
                                    v-else-if="contrat.real_check_out !== null">
                                    Contrat Terminé
                                </span>

                                <div class="flex flex-col" v-else>
                                    @can('terminer contrat')
                                        <button class="px-2 py-1 sm:mt-5 lg:mt-5 bg-green-400 rounded text-green-50"
                                            data-toggle="modal" data-target="#terminer-contrat-modal"
                                            @click="displayModal('terminer', contrat)">
                                            <i class="fas fa-ban "></i>
                                            Terminer Contrat
                                        </button>
                                    @endcan
                                    @can('editer contrat')
                                        <a class="px-2 py-1 mt-2 text-center text-blue-100 no-underline bg-blue-400 rounded"
                                            :href="'/contrat/' + contrat.id + '/edit'">
                                            <i class="fas fa-edit"></i>
                                            Editer
                                        </a>
                                    @endcan
                                    @can('annuler contrat')
                                        <button class="px-2 py-1 mt-2 text-red-100 bg-red-400 rounded"
                                            @click="annulerContrat(contrat)">
                                            <i class="fas fa-ban "></i>
                                            Annuler
                                        </button>
                                    @endcan
                                    @can('terminer contrat')
                                        <a class="px-2 py-1 mt-2 text-center text-white no-underline bg-purple-400 rounded"
                                            :href="'/contrat/' + contrat.id + '/download'">
                                            <i class="fas fa-download"></i>
                                            Télécharger Contrat
                                        </a>
                                        <button class="px-2 py-1 sm:mt-3 lg:mt-2 bg-yellow-400 rounded text-green-50"
                                            data-toggle="modal" data-target="#mail-contrat-modal"
                                            @click="passDataToModal('contrat', contrat)">
                                            <i class="fas fa-envelope"></i>
                                            Envoyer Mail
                                        </button>
                                    @endcan


                                    <a target="_blank"
                                        class="px-2 py-1 mt-2 text-center text-white no-underline bg-purple-400 rounded"
                                        :href="'/contrat/' + contrat.id">
                                        <i class="fas fa-file-invoice "></i>
                                        Voir Contrat
                                    </a>
                                    <div class="relative flex-shrink-0">
                                        <div class="w-full">
                                            <button type="button" @click="toggle('print_options')"
                                                class="px-2 py-1 mt-2 text-center text-white no-underline bg-gray-400 rounded w-full"
                                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                                <span class="sr-only">Open user menu</span>
                                                <i class="fas fa-print "></i>
                                                Imprimer
                                            </button>
                                        </div>

                                        <div class="absolute left-0 w-48 py-1 mt-2 origin-top-right bg-gray-400 rounded-md shadow-lg ring-1
                                                        ring-black ring-opacity-5 focus:outline-none"
                                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                            tabindex="-1" v-show="print_options">
                                            <!-- Active: "bg-gray-100", Not Active: "" -->
                                            <a target="_blank" :href="'/contrat/' + contrat.id + '/print'"
                                                class="block px-4 py-2 text-sm text-white" role="menuitem"
                                                tabindex="-1">
                                                Template Location
                                            </a>
                                            <a target="_blank" :href="'/contrat/' + contrat.id + '/print-hotel-A5'"
                                                class="block px-4 py-2 text-sm text-white" role="menuitem"
                                                tabindex="-1">
                                                Template Orisha
                                            </a>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            </tr>


                            </tbody>
                            {{-- Pagination --}}
                        </div>
                        {{-- Actions Mobile --}}
                        <div class="lg:hidden" v-if="contrat.real_check_out === null">
                            {{-- <div class="flex px-1 py-3 bg-gray-100 rounded" v-if="contrat.deleted_at === null">

                                @can('créer paiement')
                                    <button type="button" class="px-1 py-0 mr-2 text-white bg-blue-500 btn"
                                        @click="displayModal('paiement',contrat)">
                                        Effectuer Paiement
                                    </button>
                                @endcan

                                @can('prolonger contrat')
                                    <button type="button" class="px-1 py-0 mr-2 btn btn-primary btn-sm"
                                        @click="displayModal('prolongation', contrat)">
                                        <i class="fas fa-clock"></i> Prolonger Contrat
                                    </button>
                                @endcan


                                @can('terminer contrat')
                                    <button type="button" class="px-1 py-0 mr-2 btn bg-green-400 text-green-50"
                                        @click="displayModal('terminer', contrat)">
                                        <i class="fas fa-ban "></i>
                                        Terminer Contrat
                                    </button>
                                @endcan
                            </div> --}}
                        </div>


                        <div class="relative mt-3">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="isolate inline-flex -space-x-px rounded-md shadow-sm lg:hidden"
                                    v-if="contrat.real_check_out === null">

                                    <button type="button"
                                        class="relative inline-flex items-center bg-green-300 px-3 py-2 text-gray-800 sm:text-lg ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10"
                                        @click="displayModal('paiement',contrat)">
                                        Paiement
                                    </button>
                                    <button type="button"
                                        class="relative inline-flex items-center bg-yellow-300 px-3 py-2 text-gray-800 sm:text-lg ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10"
                                        @click="displayModal('prolongation', contrat)">
                                        Prolonger
                                    </button>
                                    <button type="button"
                                        class="relative inline-flex items-center rounded-r-md bg-red-300 px-3 py-2 text-gray-800 sm:text-lg ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10"
                                        @click="displayModal('terminer', contrat)">
                                        Terminer
                                    </button>
                                </span>
                                <span class="isolate inline-flex -space-x-px rounded-md shadow-sm" v-else>

                                    <button type="button"
                                        class="relative inline-flex items-center bg-green-300 px-3 py-2 text-gray-800 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10">
                                        Contrat Terminé
                                    </button>

                                </span>
                            </div>
                        </div>


                        <Modal v-if="modal === 'paiement'">
                            <template v-slot:title>EFFECTUER UN PAIEMENT</template>

                            <div class="mt-2">
                                <form action="/paiement" method="post" v-if="modalData.contrat">
                                    @csrf

                                    <div class="flex items-center justify-start ">
                                        <input type="checkbox" class="mr-4" name="payer_avec_caution"
                                            v-model="ajouterPaiement.payer_avec_caution">
                                        <label for="" class="m-0">Payer avec Caution

                                            <span v-if="ajouterPaiement.payer_avec_caution && ajouterPaiement.montant ">
                                                ( @{{ modalData.contrat.caution - ajouterPaiement.montant | currency }} )
                                            </span>

                                        </label>
                                    </div>

                                    <input type="hidden" :value="modalData.contrat.id" name="contrat_id">

                                    <div class="flex flex-col items-start">
                                        <label for="">Montant</label>
                                        <input type="number" class="w-full p-2 border border-gray-300 rounded-md"
                                            name="montant" v-model="ajouterPaiement.montant">
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <label for="">Note</label>
                                        <textarea type="number" class="w-full px-2 py-6 border border-gray-300 rounded-md" name="note"></textarea>
                                    </div>
                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                        <button type="submit"
                                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                            Payer
                                        </button>
                                        <button type="button" @click="closeModal()"
                                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </Modal>

                        <Modal v-if="modal === 'prolongation'">
                            <template v-slot:title>PROLONGER UN CONTRAT</template>
                            <div class="mt-2">
                                <form v-if="modalData.contrat"
                                    :action="'/contrats/' + modalData.contrat.id + '/prolonger'" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Nouvelle Date
                                                Prolongation</label>
                                            <input type="date" class="form-control" name="du">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Montant</label>
                                            <input type="number" step=500 class="form-control" name="prix_journalier"
                                                :value="modalData.contrat.prix_journalier">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Sélectionner
                                                Voiture</label>
                                            <select class="custom-select" name="voiture">

                                                <option :value="modalData.contrat.contractable.id" selected
                                                    v-if="modalData.contrat.contractable">
                                                    @{{ modalData.contrat.contractable.immatriculation }}
                                                </option>

                                                @foreach ($voitures as $voiture)
                                                    @if ($voiture->etat === 'disponible')
                                                        <option value="{{ $voiture->id }}">
                                                            {{ $voiture->immatriculation }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                        <button type="button"
                                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                                            @click="closeModal()">Fermer</button>
                                        <button type="submit"
                                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">Submit</button>
                                    </div>
                                </form>

                            </div>
                        </Modal>

                        <Modal v-if="modal === 'terminer' ">
                            <template v-slot:title>TERMINER LE CONTRAT</template>
                            <div class="mt-2">
                                <form :action="'/contrat/' + modalData.contrat.id + '/terminer'" method="POST"
                                    v-if="modalData.contrat">
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
                                        <button type="button" class="btn btn-secondary"
                                            @click="closeModal()">Annuler</button>
                                        <button type="submit" class="text-white bg-green-500 btn">Terminer</button>
                                    </div>
                                </form>

                            </div>
                        </Modal>
                        <Modal v-if="modal === 'changer-voiture' ">
                            <template v-slot:title>TERMINER LE CONTRAT</template>
                            <div class="mt-2">
                                <form :action="'/contrats/' + modalData.contrat.id + '/changer-voiture'" method="POST"
                                    v-if="modalData.contrat">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Sélectionner
                                                Voiture</label>
                                            <select class="custom-select" name="voiture">

                                                <option :value="modalData.contrat.contractable.id" selected
                                                    v-if="modalData.contrat.contractable">
                                                    @{{ modalData.contrat.contractable.immatriculation }}
                                                </option>

                                                @foreach ($voitures as $voiture)
                                                    @if ($voiture->etat === 'disponible')
                                                        <option value="{{ $voiture->id }}">
                                                            {{ $voiture->immatriculation }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            @click="closeModal()">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>

                            </div>
                        </Modal>


                        {{-- MODAL EDIT PAIEMENT --}}
                        {{-- <div class="modal fade" id="editPaiementModal" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Paiement Contrat</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" v-if="modalData.paiement">
                                        <form :action="'/paiement/' + modalData.paiement.id" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex flex-col items-start">
                                                <label for="">Montant</label>
                                                <input type="number" class="w-full p-2 border border-gray-300 rounded-md"
                                                    name="montant" v-model="modalData.paiement.montant">
                                            </div>
                                            <div class="flex flex-col items-start">
                                                <label for="">Note</label>
                                                <textarea type="number" class="w-full px-2 py-6 border border-gray-300 rounded-md" name="note"
                                                    v-model="modalData.paiement.note"></textarea>
                                            </div>
                                            <div
                                                class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                <button type="submit"
                                                    class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                                    Payer
                                                </button>
                                                <button type="button" @click="closeModal()"
                                                    class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>

                                    </div>


                                </div>
                            </div>
                        </div> --}}
                        {{-- COMPAGNIE TYPE V --}}
                        @if ($compagnie->type === 'véhicules')
                            {{-- MODAL CHANGER VOITURE  --}}
                            <div class="modal fade" id="changer-voiture-modal" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Changer Véhicule</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form :action="'/contrats/' + modalData.contrat.id + '/changer-voiture'"
                                            method="POST" v-if="modalData.contrat">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="">Sélectionner
                                                        Voiture</label>
                                                    <select class="custom-select" name="voiture">

                                                        <option :value="modalData.contrat.contractable.id" selected
                                                            v-if="modalData.contrat.contractable">
                                                            @{{ modalData.contrat.contractable.immatriculation }}
                                                        </option>

                                                        @foreach ($voitures as $voiture)
                                                            @if ($voiture->etat === 'disponible')
                                                                <option value="{{ $voiture->id }}">
                                                                    {{ $voiture->immatriculation }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Modal Paiement -->
                        {{-- <div class="modal fade" id="ajouter-paiement-modal" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Paiement Contrat</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/paiement" method="post" v-if="modalData.contrat">
                                            @csrf

                                            <div class="flex items-center justify-start ">
                                                <input type="checkbox" class="mr-4" name="payer_avec_caution"
                                                    v-model="ajouterPaiement.payer_avec_caution">
                                                <label for="" class="m-0">Payer avec Caution

                                                    <span
                                                        v-if="ajouterPaiement.payer_avec_caution && ajouterPaiement.montant ">
                                                        ( @{{ modalData.contrat.caution - ajouterPaiement.montant | currency }} )
                                                    </span>

                                                </label>
                                            </div>

                                            <input type="hidden" :value="modalData.contrat.id" name="contrat_id">


                                            <div class="flex flex-col items-start">
                                                <label for="">Montant</label>
                                                <input type="number" class="w-full p-2 border border-gray-300 rounded-md"
                                                    name="montant" v-model="ajouterPaiement.montant">
                                            </div>
                                            <div class="flex flex-col items-start">
                                                <label for="">Note</label>
                                                <textarea type="number" class="w-full px-2 py-6 border border-gray-300 rounded-md" name="note"></textarea>
                                            </div>
                                            <div
                                                class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                <button type="submit"
                                                    class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                                    Payer
                                                </button>
                                                <button type="button" @click="closeModal()"
                                                    class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                                    Cancel
                                                </button>
                                            </div>

                                        </form>


                                    </div>


                                </div>
                            </div>
                        </div> --}}

                        <!-- Modal 1/2 Journee -->
                        {{-- <div class="modal fade" id="demi-journee-modal" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter 1/2 Journee</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form :action="'/contrat/' + modalData.contrat.id + '/ajouter-demi-journee'"
                                        method="POST" v-if="modalData.contrat">
                                        @csrf
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="">Montant</label>
                                                <input type="number" step=5000 class="form-control" name="demi_journee"
                                                    :value="modalData.contrat.prix_journalier / 2">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Ajouter 1/2
                                                Journee</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                        <!-- Modal Editer 1/2 Journee -->
                        {{-- <div class="modal fade" id="editer-demi-journee-modal" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editer Montant 1/2 Journee</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form :action="'/contrat/' + modalData.contrat.id + '/update-demi-journee'"
                                        method="POST" v-if="modalData.contrat">
                                        @csrf
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="">Montant</label>
                                                <input type="number" step=5000 class="form-control" name="demi_journee"
                                                    :value="modalData.contrat.demi_journee">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Ajouter 1/2
                                                Journee</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Modal Montant Chauffeur -->
                        {{-- <div class="modal fade" id="montant-chauffeur-modal" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter 1/2 Journee</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form :action="'/contrat/' + this.modalData.contrat.id + '/ajouter-montant-chauffeur'"
                                        method="POST" v-if="modalData.contrat">
                                        @csrf
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="">Montant Total Chauffeur</label>
                                                <input type="number" step=5000 class="form-control"
                                                    name="montant_chauffeur" value="">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Ajouter
                                                Chauffeur</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Modal Editer Montant Chauffeur -->
                        {{-- <div class="modal fade" id="editer-montant-chauffeur-modal" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter 1/2 Journee</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form :action="'/contrat/' + this.modalData.contrat.id + '/update-montant-chauffeur'"
                                        method="POST" v-if="modalData.contrat">
                                        @csrf
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="">Montant Total Chauffeur</label>
                                                <input type="number" step=5000 class="form-control"
                                                    name="montant_chauffeur" :value="modalData.contrat.montant_chauffeur">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Ajouter
                                                Chauffeur</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="modal fade" id="terminer-contrat-modal" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="text-lg modal-title">Êtes-vous sûr de vouloir
                                            terminer ce contrat? </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form :action="'/contrat/' + modalData.contrat.id + '/terminer'" method="POST"
                                        v-if="modalData.contrat">
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
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="text-white bg-green-500 btn">Terminer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                        <!-- Modal Mail -->
                        {{-- <div class="modal fade" id="mail-contrat-modal" tabindex="-1" role="dialog"
                            aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="text-lg modal-title">Envoyer Contrat par Mail </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form :action="'/contrat/' + modalData.contrat.id + '/send-mail'" method="POST"
                                        v-if="modalData.contrat">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-sm mb-3">
                                                Entrez l'adresse Mail
                                            </p>
                                            <div v-if="modalData.contrat.client.mail">
                                                <input name="mail" type="text" class="form-control"
                                                    :value="modalData.contrat.client.mail">
                                            </div>
                                            <div v-else>
                                                <input name="mail" type="text" class="form-control">
                                                <label><input type="checkbox" name="save_mail"> Enregistrer l'adresse sur
                                                    le
                                                    client</label>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="text-white bg-green-500 btn">Envoyer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="flex justify-center pt-10 pb-24">
                        {{ $contrats->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
                    </div>

                    {{-- MODAL PROLONGATION --}}
                    {{-- <div class="modal" id="prolongation-modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Prolonger Contrat</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form v-if="modalData.contrat"
                                    :action="'/contrats/' + modalData.contrat.id + '/prolonger'" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Nouvelle Date
                                                Prolongation</label>
                                            <input type="date" class="form-control" name="du">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Montant</label>
                                            <input type="number" step=5000 class="form-control" name="prix_journalier"
                                                :value="modalData.contrat.prix_journalier">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Sélectionner
                                                Voiture</label>
                                            <select class="custom-select" name="voiture">

                                                <option :value="modalData.contrat.contractable.id" selected
                                                    v-if="modalData.contrat.contractable">
                                                    @{{ modalData.contrat.contractable.immatriculation }}
                                                </option>

                                                @foreach ($voitures as $voiture)
                                                    @if ($voiture->etat === 'disponible')
                                                        <option value="{{ $voiture->id }}">
                                                            {{ $voiture->immatriculation }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}

                    {{-- PLUS BUTTON -- AJOUTER CONTRAT --}}
                    <div class="sticky flex justify-end px-3 lg:px-40 bottom-4 lg:bottom-16">

                        <a href="/contrats/create">

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
