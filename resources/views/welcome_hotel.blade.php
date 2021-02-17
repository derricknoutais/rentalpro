@if(sizeof($chambres) > 0)
    {{-- Bienvenue sur Rental Pro --}}
    <div class="tw-flex tw-flex-col tw-container-fluid tw-h-full">
        {{-- HEADER --}}
        <div class="tw-flex mt-5 tw-justify-center tw-items-center tw-mt-24">
            <img src="/img/rentalkey.png" class="tw-w-1/12">
            <img src="/img/car.png" class="tw-w-1/12">
            <div class="tw-p-10 tw-bg-purple-300 tw-rounded">
                <h1 class="text-center tw-text-4xl  tw-text-white">
                    @if( Auth::check() )
                        Bienvenue sur Rental Pro
                    @endif
                </h1>
            </div>

            <img src="/img/hotel.png" class="tw-w-1/12">
            <img src="/img/housefield.png" class="tw-w-1/12">
        </div>

        {{-- BODY --}}
        <div class="tw-container-fluid tw-flex">
            {{-- Liste de Chambres --}}
            <div class="tw-w-2/3 tw-bg-gray-300 tw-flex tw-flex-col tw-items-center tw-justify-center">
                <div class="tw-grid tw-grid-cols-6">
                    <div class="tw-p-10 tw-border tw-cursor-pointer tw-flex tw-flex-col" :class="couleurEtat(chambre)" v-for="(chambre, index) in chambres" @click="afficheDetailsChambre(chambre)">
                        <span>@{{ chambre.nom }}</span>
                        <i class="fas fa-star tw-text-yellow-300 tw-mt-2" v-if="chambre.type === 'VIP'"></i>
                    </div>
                </div>
            </div>

            {{-- Détails de Chambre --}}
            <div class="tw-w-1/3 tw-flex tw-flex-col tw-flex-grow" :class="couleurEtat(chambreADetailler)">
                {{-- Numéro de Chambre --}}
                <h2 class="tw-text-2xl tw-text-center tw-my-3">Chambre @{{ chambreADetailler.nom }}</h2>

                {{-- Détails de Chambre --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-px-5">
                    {{-- Boutons de Fonctionnalité A Droite --}}
                    <div>
                        <p class="tw-text-lg">Type: @{{ chambreADetailler.type }}</p>
                        <p class="tw-text-lg">Etat: @{{ chambreADetailler.etat }}</p>
                    </div>
                    {{-- Bouton Faire Louer --}}
                    <div>
                        <button
                            class="tw-px-3 tw-py-3 tw-bg-gray-800 tw-text-gray-100 tw-rounded"
                            @click="cacheFormulaireDétails()" v-if="afficheFormulaireLocationRapide"
                        >Annuler</button>
                        <button
                            class="tw-px-3 tw-py-3 tw-bg-gray-800 tw-text-gray-100 tw-rounded"
                            @click="faireLouer()" v-else
                        >Faire Louer</button>

                    </div>
                </div>

                {{-- V-IF Chambre Disponible --- Formulaire de Location --}}
                <transition
                    name="fade"
                    enter-active-class="animated bounceIn"
                    eave-active-class="animated fadeOut"
                >
                    <div class="tw-flex tw-w-full tw-justify-center" v-if="afficheFormulaireLocationRapide && chambreADetailler.etat === 'Disponible' ">
                        <form
                            class="tw-flex tw-flex-col tw-w-2/3" action="/contrats/store-contrat-rapide"
                            method="POST" enctype="multipart/form-data" id="clientForm"
                            {{-- @submit.prevent="enregistreClientDansCashier()" --}}
                        >
                            @csrf
                            <label class="form-check-label tw-mt-5">
                                <input type="checkbox" @click="displayNewCustomerForm">
                                Nouveau Client
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
                                <multiselect :options="clients" label="nom_complet" v-model="client">
                                    <template slot="noResult"> Ce client n'existe pas </template>
                                </multiselect>
                            </div>

                            {{-- Champs Chambre & Contrat --}}
                            <input type="hidden" class="form-control" id="chambre_id" name="chambre_id" :value="chambreADetailler.id">
                            <input type="date" class="form-control tw-mt-3" name="du" v-model="formulaire.du">
                            <input type="date" class="form-control tw-mt-3" name="au" v-model="formulaire.au">
                            <input type="text" class="form-control tw-mt-3" name="nombre_jours" placeholder="Nombre de Jours" :value="nb_jours" readonly>
                            <input type="number" class="form-control tw-mt-3" name="prix_journalier" placeholder="Prix Journalier" :value="chambreADetailler.prix_journalier">
                            <input type="number" class="form-control tw-mt-3" name="paiement" placeholder="Paiement">


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
                </transition>

                {{-- V-ELSE-IF Contrat de Location Actuel --}}
                <transition
                    name="fade"
                    enter-active-class="animated fadeIn"
                    eave-active-class="animated fadeOut"
                >
                    <div class="tw-flex tw-flex-col tw-justify-center tw-p-5 tw-mt-10" v-if="afficheFormulaireLocationRapide && chambreADetailler.etat === 'Loué' && chambreADetailler.contrat_en_cours ">
                        {{-- Nom du Client --}}
                        <p>Nom: @{{ chambreADetailler.contrat_en_cours.client.nom }} @{{ chambreADetailler.contrat_en_cours.client.prenom }}</p>
                        <p>
                            Contrat du:
                            <span class="tw-bg-white tw-px-3 tw-py-1 tw-mx-1">
                                @{{ chambreADetailler.contrat_en_cours.du | moment('DD/MM/YYYY') }}
                            </span>
                            au :
                            <span class="tw-bg-white tw-px-3 tw-py-1 tw-mx-1">
                                @{{ chambreADetailler.contrat_en_cours.au | moment('DD/MM/YYYY') }}
                            </span>
                        </p>
                        <p>
                            Total:
                            <span>
                                @{{ chambreADetailler.contrat_en_cours.prix_journalier }}
                            </span>
                            x
                            <span>
                                @{{ chambreADetailler.contrat_en_cours.nombre_jours }}
                            </span>
                            =
                            <span>
                                @{{ chambreADetailler.contrat_en_cours.total }}
                            </span>

                        </p>
                        <a target="_blank" :href="'/contrat/' + chambreADetailler.contrat_en_cours.id" class="tw-px-3 tw-py-2 tw-bg-white tw-inline tw-w-1/6 tw-text-center ">Voir Plus</a>
                    </div>
                </transition>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="tw-flex mt-5 tw-justify-center tw-items-end">



                <div class="tw- tw-bg-gray-300 tw-rounded">
                    <h1 class="text-center tw-text-md  tw-text-black">
                        @if( Auth::check() )
                            Copyrights, All Rights Reserved. By Derrick Noutais 2019
                        @endif
                    </h1>
                </div>





        </div>

    </div>
@else
    <div class="container">
        <div class="row mt-5">
            <div class="col text-center">
                <img src="https://requestreduce.org/images/car-clipart-image-17.png" width="30%"/>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col text-center mt-5">
                Aucune chambre n'est enregistrée. Veuillez enregistrer une chambre
            </div>
        </div>
    </div>
@endif
