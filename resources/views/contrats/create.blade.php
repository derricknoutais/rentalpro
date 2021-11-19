@extends('layouts.app')


@section('content')
<div class="container-fluid">
    <cree-contrats inline-template :contractables_prop="{{ $contractables }}" :contrats="{{ $contrats }}" :chambres_prop="{{ $contractables }}" :clients_prop="{{ $clients }}">
        <div class="tw-flex ">
            <form
                class="tw-flex tw-flex-col tw-w-2/3" action="/contrats/store"
                method="POST" enctype="multipart/form-data" id="clientForm"
                {{-- @submit.prevent="enregistreClientDansCashier()" --}}
            >
                @csrf
                <label class="form-check-label tw-mt-5">
                    <input type="checkbox" @click="displayNewCustomerForm">Nouveau Client
                </label>

                {{-- Champs Clients --}}
                <input type="hidden" name="client_id" v-model.number="client.id">

                <div class="tw-flex tw-flex-col" v-if="display.nouveau_client">
                    <input type="hidden" class="form-control" id="cashier_id" name="cashier_id" v-model="client.cashier_id">
                    <input type="text" class="form-control tw-mt-3" name="nom" placeholder="Nom" v-model="client.nom">
                    <input type="text" class="form-control tw-mt-3" name="prenom" placeholder="Prénom" v-model="client.prenom">
                    <input type="text" class="form-control tw-mt-3" name="numero_telephone" placeholder="Nº Téléphone" v-model="client.numero_telephone">
                </div>

                <div class="tw-flex tw-flex-col tw-mt-5" v-else>
                    <input type="hidden" name="client" v-model.number="client.id">
                    <multiselect :options="clients" label="nom_complet" v-model="client">
                        <template slot="noResult"> Ce client n'existe pas </template>
                    </multiselect>
                </div>

                {{-- Champs Clients --}}
                <input type="hidden" name="contractable" v-model.number="contractable.id">
                 <div class="tw-mt-5">
                    <multiselect :options="contractables" label="immatriculation" v-model="contractable">
                        <template slot="noResult"> Cette voiture n'existe pas </template>
                    </multiselect>
                </div>


                {{-- Champs Chambre & Contrat --}}
                <input type="hidden" class="form-control" id="chambre_id" name="chambre_id" :value="chambreADetailler.id">
                <input type="date" class="form-control tw-mt-3" name="du" v-model="formulaire.du">
                <input type="date" class="form-control tw-mt-3" name="au" v-model="formulaire.au">
                <input type="text" class="form-control tw-mt-3" name="nombre_jours" placeholder="Nombre de Jours" :value="nb_jours" readonly>
                <input type="number" class="form-control tw-mt-3" name="prix_journalier" placeholder="Prix Journalier">
                <input type="number" class="form-control tw-mt-3" name="paiement" placeholder="Paiement">
                <input type="number" class="form-control tw-mt-3" name="caution" placeholder="Caution">

                {{-- Boutons Annuler et Ajouter Client & Réserver --}}
                <div class="tw-flex">
                    <button type="button" class="form-control tw-my-3 tw-bg-gray-300 tw-text-gray-900">
                        Annuler
                    </button>
                    <button
                        type="submit" class="form-control tw-my-3 tw-bg-gray-800 tw-text-gray-100"
                    >
                        Ajouter Client & Réserver
                    </button>


                </div>
            </form>
        </div>
    </cree-contrats>

</div>
@endsection
