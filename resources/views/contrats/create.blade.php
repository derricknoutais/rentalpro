@extends('layouts.app')


@section('content')
    <div class="container-fluid">
        <cree-contrats inline-template :contractables_prop="{{ $contractables }}" :contrats="{{ $contrats }}"
            :chambres_prop="{{ $contractables }}" :clients_prop="{{ $clients }}" :offres_prop="{{ $offres }}"
            :compagnie_prop="{{ $compagnie }}" :client_requested="{{ $client }}"
            :contractable_requested="{{ $contractable }}">
            <div class="flex justify-center">
                <form class="flex flex-col w-full" action="/contrats/store" method="POST" enctype="multipart/form-data"
                    id="clientForm" {{-- @submit.prevent="enregistreClientDansCashier()" --}}>
                    @csrf
                    {{-- Checks  --}}
                    <div class="flex mt-5">
                        <label class="form-check-label flex items-center">
                            <input type="checkbox" @click="toggleNewCustomerForm">
                            <span class="ml-1">Nouveau Client</span>
                        </label>
                        <label class=" form-check-label flex items-center ml-3">
                            <input type="checkbox" v-model="display.halfDay">
                            <span class="ml-1">Ajouter 1/2 Journee</span>
                        </label>
                        <label class=" form-check-label flex items-center ml-3">
                            <input type="checkbox" v-model="display.driver">
                            <span class="ml-1">Ajouter Chauffeur</span>
                        </label>
                    </div>

                    {{-- Champs Clients --}}
                    <input type="hidden" name="client_id" v-model.number="client.id">
                    {{-- Nouveau Client --}}
                    <div class="flex flex-col mt-3" v-if="display.nouveau_client">
                        <input type="hidden" class="form-control" id="cashier_id" name="cashier_id"
                            v-model="client.cashier_id">
                        <input type="text" class="form-control" name="nom" placeholder="Nom" v-model="client.nom">
                        <input type="text" class="form-control" name="prenom" placeholder="Prénom"
                            v-model="client.prenom">
                        <input type="text" class="form-control" name="numero_telephone" placeholder="Nº Téléphone"
                            v-model="client.numero_telephone">
                        <input type="hidden" class="form-control" name="image_id" placeholder="Nº Téléphone"
                            v-model="client.image_id">
                        {{-- <FilePond name="pond/> --}}
                        <permis-pond @file-processed="attributeImageId"></permis-pond>
                    </div>
                    {{-- Ancien Client --}}
                    <div class="flex flex-col mt-3" v-else>
                        <input type="hidden" name="client" v-model.number="client.id">
                        <label for="">Selectionner Client</label>
                        <multiselect placeholder="Selectionne un Client" :options="clients" label="nom_complet"
                            v-model="client">
                            <template slot="noResult"> Ce client n'existe pas </template>
                        </multiselect>
                    </div>

                    {{-- Champs Voiture --}}
                    <input type="hidden" name="contractable" v-model.number="contractable.id">
                    <div class="w-full mt-3">
                        <label for="">
                            @if ($compagnie->isHotel())
                                Selectionner Chambre
                            @elseif($compagnie->isVehicules())
                                Selectionner Voiture
                            @endif

                        </label>
                        <div class="flex w-full">
                            <multiselect :show-labels="true" :options="contractables"
                                @if ($compagnie->isHotel()) label="nom"
                            @elseif($compagnie->isVehicules())
                                label="immatriculation" @endif
                                v-model="contractable">
                                <template slot="noResult"> Cette voiture n'existe pas </template>
                            </multiselect>
                            <button type="button" v-if="contractable.etat === 'loué' "
                                class="w-1/4 ml-3 text-gray-900 bg-gray-300 form-control" data-toggle="modal"
                                data-target="#rendreVehiculeDisponible">Receptionner</button>
                        </div>
                    </div>
                    @if ($compagnie->isHotel())
                        <div class="w-full mt-3">
                            <label for="">Selectionner Offre</label>
                            <input type="hidden" name="offre_id" v-model="formulaire.offre.id" v-if="formulaire.offre">
                            <div class="flex w-full">
                                <multiselect :show-labels="true" :options="offres" label="nom"
                                    v-model="formulaire.offre">
                                    <template slot="noResult"> Cette voiture n'existe pas </template>
                                </multiselect>
                            </div>
                        </div>
                    @endif




                    {{-- Champs Chambre & Contrat --}}
                    <input type="hidden" class="form-control" id="chambre_id" name="chambre_id"
                        :value="chambreADetailler.id">

                    {{-- Champs Du Au Nombre Jours --}}
                    <div class="sm:flex sm:flex-col lg:flex w-full mt-3">
                        <div class="w-1/3">
                            <label for="">Du</label>
                            <input type="datetime-local" class="form-control" name="du" v-model="formulaire.du">
                        </div>
                        <div class="w-1/3">
                            <label for="">Au</label>
                            <input type="datetime-local" class="form-control" name="au" v-model="formulaire.au">
                        </div>
                        <div class="w-1/3">
                            @if ($compagnie->isVehicules())
                                <label for="">Nombre Jours</label>
                                <input type="text" class="form-control" name="nombre_jours"
                                    placeholder="Nombre de Jours" :value="nb_jours" readonly>
                            @else
                                <label for="">Nombre Jours (Offres)</label>
                                <input type="text" class="form-control" name="nombre_jours"
                                    placeholder="Nombre de Jours" v-model="formulaire.nb_jours">
                            @endif

                        </div>

                    </div>

                    {{-- Montant Journalier Total --}}
                    <div class="flex w-full mt-3 sm:max-md:flex-col">
                        <div class="w-full">
                            <label for="">Montant Journalier</label>
                            <input type="number" class="form-control" name="prix_journalier"
                                placeholder="Montant Journalier" v-model="formulaire.prix_journalier">
                        </div>
                        <div class="w-full" v-show="display.halfDay">
                            <label for="">Montant 1/2 Journee</label>
                            <input type="number" class="form-control" name="demi_journee"
                                placeholder="Montant 1/2 Journee" v-model.number="formulaire.demi_journee">
                        </div>
                        <div class="w-full" v-show="display.driver">
                            <label for="">Chauffeur</label>
                            <input type="number" class="form-control" name="montant_chauffeur"
                                placeholder="Montant Chauffeur" v-model.number="formulaire.chauffeur">
                        </div>
                    </div>
                    <div class="w-full">
                        <label for="">Total</label>
                        <input type="text" class="form-control" name="" placeholder="Total Contrat"
                            :value="total" readonly>
                    </div>
                    <div class="flex w-full mt-3">
                        <div class="w-1/3">
                            <label for="">Paiement</label>
                            <input type="number" class="form-control" name="paiement" placeholder="Paiement"
                                v-model="formulaire.paiement">
                        </div>
                        <div class="w-1/3">
                            <label for="">Type Paiement</label>
                            <select type="number" class="form-control" name="type_paiement"
                                placeholder="Type Paiement">
                                <option v-for="type in types_paiements ">@{{ type }}</option>
                            </select>
                        </div>
                        <div class="w-1/3">
                            <label for="">Solde</label>
                            <input type="text" class="form-control" name="" placeholder="Solde"
                                :value="solde" readonly>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-1/2">
                            <label for="">Caution</label>
                            <input type="number" class="form-control" name="caution" placeholder="Caution">
                        </div>
                        <div class="w-1/2">
                            <label for="">Type Caution</label>
                            <select type="number" class="form-control" name="type_caution" placeholder="Type Caution">
                                <option v-for="type in types_paiements">@{{ type }}</option>
                            </select>
                        </div>
                    </div>

                    <label for="">Note</label>
                    <textarea name="note" class="form-control" id="" cols="30" rows="4"></textarea>

                    {{-- Boutons Annuler et Ajouter Client & Réserver --}}
                    <div class="flex">
                        <button type="button" class="my-3 text-gray-900 bg-gray-300 form-control">
                            Annuler
                        </button>
                        <button type="submit" class="my-3 text-gray-100 bg-gray-800 form-control">
                            Ajouter Client & Réserver
                        </button>


                    </div>
                </form>
                <div class="modal fade" id="rendreVehiculeDisponible" tabindex="-1" role="dialog"
                    aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="text-lg modal-title">Êtes-vous sûr de vouloir rendre disponible cette voiture
                                    sans terminer le contrat correspondant? </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form {{-- :action="'/contractable/' + contractable.id  + '/rendre-disponible'" --}} {{-- method="POST" --}}>
                                @csrf
                                <div class="modal-body">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Annuler</button>
                                    <button type="button" @click="rendreVehiculeDisponible"
                                        class="text-white bg-green-500 btn">Rendre Disponible</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </cree-contrats>

    </div>
@endsection
