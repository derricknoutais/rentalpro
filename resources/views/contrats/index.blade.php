@extends('layouts.app')

@section('content')
    <contrats-index inline-template :contrats_prop="{{ json_encode($contrats) }}" env="{{ config('app.env') }}"
        :voitures_prop="{{ $contractables }}" :clients_prop="{{ $clients }}">
        <div>
            <div class="w-full">
                <div class="px-4 py-8 sm:px-6 lg:px-12 bg-gray-50">
                    <div class="max-w-7xl mx-auto space-y-8">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-wide text-gray-500">Vue d'ensemble</p>
                                <h1 class="text-3xl font-semibold text-gray-900">Contrats</h1>
                                <p class="text-sm text-gray-500">
                                    {{ number_format($contrats->count(), 0, ' ', ' ') }} dossiers dans votre pipeline.
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="/contrats/create"
                                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                    </svg>
                                    Nouveau contrat
                                </a>
                                <a href="/reservations/create"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Réservation rapide
                                </a>
                            </div>
                        </div>

                        {{-- Filtres --}}
                        <form action="/contrats" method="GET" class="bg-white shadow-xl rounded-2xl p-6 space-y-6">
                            @if ($compagnie->isVehicules())
                                <input type="hidden" name="voiture" v-model="filters.voiture.id">
                            @else
                                <input type="hidden" name="chambre" v-model="filters.chambre.id">
                            @endif
                            <input type="hidden" name="client" v-model="filters.client.id">
                            <input type="hidden" name="etat" v-model="filters.etat">

                            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600">
                                        @if ($compagnie->isVehicules())
                                            Véhicule
                                        @else
                                            Chambre
                                        @endif
                                    </label>
                                    <multiselect :options="{{ $contractables }}"
                                        @if ($compagnie->isVehicules()) label="immatriculation" v-model="filters.voiture"
                                    @else label="nom" v-model="filters.chambre" @endif>
                                    </multiselect>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600">Client</label>
                                    <multiselect :options="{{ $clients }}" label="nom_complet"
                                        v-model="filters.client">
                                    </multiselect>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600">État du contrat</label>
                                    <multiselect :options="['En cours', 'Terminé', 'Annulé', 'Soldé', 'Non-Soldé']"
                                        v-model="filters.etat">
                                    </multiselect>
                                </div>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600">Du</label>
                                    <input type="date" name="du"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600">Au</label>
                                    <input type="date" name="au"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="flex items-end gap-3">
                                    <button type="submit"
                                        class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Appliquer les filtres
                                    </button>
                                    <a href="/contrats"
                                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                                        Réinitialiser
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div v-if="!contrats.length"
                            class="rounded-2xl border border-dashed border-gray-200 bg-white px-6 py-12 text-center text-gray-500">
                            Aucun contrat ne correspond à votre recherche.
                        </div>

                        <div v-else class="space-y-6">
                            <div v-for="contrat in contrats" :key="contrat.id"
                                class="rounded-2xl border border-gray-100 bg-white shadow-xl">
                                <div
                                    class="flex flex-col gap-4 border-b border-gray-100 px-6 py-4 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <a class="text-xl font-semibold text-indigo-600 hover:text-indigo-700"
                                            :href="'/contrat/' + contrat.id">
                                            @{{ contrat.numéro }}
                                        </a>
                                        <p class="text-sm text-gray-500">
                                            @{{ contrat.du | moment('DD MMM YYYY') }} →
                                            <span v-if="contrat.au">@{{ contrat.au | moment('DD MMM YYYY') }}</span>
                                            <span v-else>date de fin à confirmer</span>
                                            · @{{ contrat.nombre_jours }} jours
                                            <span v-if="contrat.demi_journee">+ 1/2</span>
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span
                                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold"
                                            :class="{
                                                'bg-red-100 text-red-600': contrat.deleted_at,
                                                'bg-green-100 text-green-700': !contrat.deleted_at && contrat.statut === 'terminé',
                                                'bg-yellow-100 text-yellow-700': !contrat.deleted_at && contrat.statut === 'en cours',
                                            }">
                                            <template v-if="contrat.deleted_at">Contrat annulé</template>
                                            <template v-else-if="contrat.statut === 'terminé'">Contrat terminé</template>
                                            <template v-else>Contrat en cours</template>
                                        </span>
                                        <div class="relative">
                                            <button type="button"
                                                class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-1.5 text-sm text-gray-600 hover:bg-gray-50"
                                                @click="toggle('print_options')">
                                                <i class="fas fa-print text-gray-400"></i>
                                                Imprimer
                                            </button>
                                            <div v-show="print_options"
                                                class="absolute right-0 z-10 mt-2 w-48 rounded-xl border border-gray-100 bg-white shadow-lg">
                                                <a target="_blank" :href="'/contrat/' + contrat.id + '/print'"
                                                    class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Template
                                                    Location</a>
                                                <a target="_blank" :href="'/contrat/' + contrat.id + '/print-hotel-A5'"
                                                    class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Template
                                                    Orisha</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-6 px-6 py-6 lg:grid-cols-2 xl:grid-cols-4">
                                    <section class="space-y-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Location</p>
                                        <div
                                            class="rounded-2xl border border-gray-100 bg-gray-50 p-4 space-y-3 text-sm text-gray-700">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium">Caution</span>
                                                <span class="font-semibold" v-if="contrat.caution">@{{ contrat.caution }} F
                                                    CFA</span>
                                            </div>
                                            <div class="flex items-center justify-between" v-if="contrat.type_caution">
                                                <span class="font-medium">Type</span>
                                                <span class="font-semibold">@{{ contrat.type_caution }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span>Montant location</span>
                                                <span class="font-semibold">@{{ contrat.prix_journalier * contrat.nombre_jours }} F CFA</span>
                                            </div>
                                            <div class="flex items-center justify-between" v-if="contrat.demi_journee">
                                                <span>Option 1/2 journée</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-semibold">@{{ contrat.demi_journee }} F CFA</span>
                                                    @can('editer paiements')
                                                        <button class="text-gray-400 hover:text-indigo-600"
                                                            data-toggle="modal" data-target="#editer-demi-journee-modal"
                                                            @click="passDataToModal('contrat', contrat)"><i
                                                                class="fas fa-edit"></i></button>
                                                    @endcan
                                                    @can('supprimer paiements')
                                                        <button class="text-gray-400 hover:text-red-600"
                                                            @click="deleteData('/contrat/' + contrat.id + '/delete/demi_journee')"><i
                                                                class="fas fa-trash"></i></button>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between"
                                                v-if="contrat.montant_chauffeur">
                                                <span>Option chauffeur</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-semibold">@{{ contrat.montant_chauffeur }} F CFA</span>
                                                    @can('editer paiements')
                                                        <button class="text-gray-400 hover:text-indigo-600"
                                                            data-toggle="modal" data-target="#editer-montant-chauffeur-modal"
                                                            @click="passDataToModal('contrat', contrat)"><i
                                                                class="fas fa-edit"></i></button>
                                                    @endcan
                                                    @can('supprimer paiements')
                                                        <button class="text-gray-400 hover:text-red-600"
                                                            @click="deleteData('/contrat/' + contrat.id + '/delete/montant_chauffeur')"><i
                                                                class="fas fa-trash"></i></button>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div
                                                class="flex items-center justify-between border-t border-dashed border-gray-200 pt-3">
                                                <span class="font-semibold text-gray-900">Montant total</span>
                                                <span class="font-bold text-gray-900">@{{ total(contrat) }} F CFA</span>
                                            </div>
                                        </div>
                                    </section>

                                    <section class="space-y-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Paiements
                                        </p>
                                        <div
                                            class="rounded-2xl border border-green-100 bg-green-50 p-4 text-sm text-gray-700">
                                            <template v-if="contrat.paiements.length">
                                                <div class="space-y-3">
                                                    <div class="flex items-center justify-between"
                                                        v-for="paiement in contrat.paiements" :key="paiement.id">
                                                        <div>
                                                            <p class="font-semibold text-gray-900">
                                                                @{{ paiement.montant }} F CFA
                                                            </p>
                                                            <p class="text-xs text-gray-500">
                                                                @{{ paiement.created_at | moment('DD MMM YYYY') }} ·
                                                                <span
                                                                    v-if="paiement.type_paiement">@{{ paiement.type_paiement }}</span>
                                                                <span v-if="paiement.note"> ·
                                                                    @{{ paiement.note }}</span>
                                                            </p>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            @can('editer paiements')
                                                                <button class="text-gray-400 hover:text-indigo-600"
                                                                    data-toggle="modal" data-target="#editPaiementModal"
                                                                    @click="passDataToModal('paiement', paiement)"><i
                                                                        class="fas fa-edit"></i></button>
                                                            @endcan
                                                            @can('supprimer paiements')
                                                                <button class="text-gray-400 hover:text-red-600"
                                                                    @click="deletePayment(paiement)"><i
                                                                        class="fas fa-trash"></i></button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-4 border-t border-green-100 pt-3 text-sm font-semibold text-gray-900">
                                                    Solde : @{{ solde(contrat) }} F CFA
                                                </div>
                                            </template>
                                            <p v-else class="text-sm text-gray-500">Aucun paiement enregistré pour le
                                                moment.</p>
                                        </div>
                                    </section>

                                    <section class="space-y-3" v-if="contrat.client">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Client</p>
                                        <div
                                            class="rounded-2xl border border-gray-100 bg-gray-50 p-4 text-sm text-gray-700 space-y-3">
                                            <a class="text-base font-semibold text-indigo-600 hover:text-indigo-700"
                                                target="_blank" :href="'/clients/' + contrat.client.id">
                                                @{{ contrat.client['nom'] + ' ' + contrat.client['prenom'] }}
                                            </a>
                                            <p class="text-sm">
                                                <span class="font-medium">Téléphone :</span>
                                                @{{ contrat.client['phone1'] }}
                                                <span v-if="contrat.client['phone2']"> / @{{ contrat.client['phone2'] }}</span>
                                                <span v-if="contrat.client['phone3']"> / @{{ contrat.client['phone3'] }}</span>
                                            </p>
                                            <p class="text-sm" v-if="contrat.client.adresse">
                                                <span class="font-medium">Adresse :</span> @{{ contrat.client['adresse'] }}
                                            </p>
                                            <div v-if="contrat.client.derniers_contrats && contrat.client.derniers_contrats.length"
                                                class="mt-4 space-y-2">
                                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    Derniers contrats</p>
                                                <div class="space-y-2">
                                                    <div class="flex items-center justify-between"
                                                        v-for="ct in contrat.client.derniers_contrats"
                                                        :key="ct.id">
                                                        <a class="text-indigo-600 hover:text-indigo-800 text-sm"
                                                            target="_blank" :href="'/contrat/' + ct.id">
                                                            @{{ ct.numéro }}
                                                        </a>
                                                        <span class="text-xs text-gray-500">Solde :
                                                            @{{ solde(ct) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <section class="space-y-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Parc & suivi
                                        </p>
                                        <div
                                            class="rounded-2xl border border-gray-100 bg-gray-50 p-4 text-sm text-gray-700 space-y-3">
                                            <div v-if="contrat.contractable">
                                                <span class="font-medium text-gray-600">Asset</span>
                                                <p>
                                                    <a class="text-indigo-600 hover:text-indigo-800"
                                                        :href="'/contractables/' + contrat.contractable.id">
                                                        @{{ contrat.contractable.nom }}
                                                    </a>
                                                </p>
                                            </div>
                                            <div v-if="contrat.activity">
                                                <span class="font-medium text-gray-600">Créé par</span>
                                                <p>@{{ contrat.activity.causer.name }} · @{{ contrat.activity.created_at | moment('DD MMM YYYY') }}</p>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <div class="border-t border-gray-100 px-6 py-4">
                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                        <div class="flex flex-wrap gap-2" v-if="!contrat.deleted_at">
                                            @can('créer paiement')
                                                <button type="button"
                                                    class="rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700"
                                                    @click.prevent="displayModal('paiement',contrat)">
                                                    Effectuer un paiement
                                                </button>
                                            @endcan
                                            @can('prolonger contrat')
                                                <button type="button"
                                                    class="rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                                    @click.prevent="displayModal('prolongation',contrat)">
                                                    Prolonger
                                                </button>
                                            @endcan
                                            @can('editer contrat')
                                                <button type="button"
                                                    class="rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                                    @click.prevent="displayModal('changer-voiture',contrat)">
                                                    Changer de véhicule
                                                </button>
                                            @endcan
                                            @can('terminer contrat')
                                                <button type="button"
                                                    class="rounded-full border border-green-200 bg-green-50 px-4 py-2 text-sm font-semibold text-green-700 hover:bg-green-100"
                                                    @click.prevent="displayModal('terminer', contrat)">
                                                    Terminer
                                                </button>
                                            @endcan
                                        </div>
                                        <div class="text-sm text-gray-500" v-else>
                                            Actions indisponibles sur un contrat annulé.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Modal v-if="modal === 'paiement'">
                <template v-slot:title>EFFECTUER UN PAIEMENT</template>
                <form action="/paiement" method="post" v-if="modalData.contrat" class="space-y-5">
                    @csrf
                    <input type="hidden" :value="modalData.contrat.id" name="contrat_id">
                    <label
                        class="inline-flex items-start gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">
                        <input type="checkbox"
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            name="payer_avec_caution" v-model="ajouterPaiement.payer_avec_caution">
                        <span>
                            Payer avec la caution
                            <span class="block text-xs text-gray-500"
                                v-if="ajouterPaiement.payer_avec_caution && ajouterPaiement.montant">
                                Reste estimé :
                                @{{ modalData.contrat.caution - ajouterPaiement.montant | currency }}
                            </span>
                        </span>
                    </label>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Montant</label>
                        <input type="number" name="montant" v-model="ajouterPaiement.montant"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Note</label>
                        <textarea name="note" rows="3"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 mt-3">
                        <button type="button" @click="closeModal()"
                            class="inline-flex justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </Modal>

            <Modal v-if="modal === 'prolongation' ">
                <template v-slot:title>PROLONGER UN CONTRAT</template>
                <form v-if="modalData.contrat" :action="'/contrats/' + modalData.contrat.id + '/prolonger'"
                    method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Nouvelle date de fin</label>
                        <input type="date" name="du"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Montant journalier</label>
                        <input type="number" step="500" name="prix_journalier"
                            :value="modalData.contrat.prix_journalier"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Sélectionner un véhicule</label>
                        <select name="voiture"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                    <div class="grid gap-3 sm:grid-cols-2 mt-3">
                        <button type="button"
                            class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            @click="closeModal()">Fermer</button>
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700">Mettre
                            à jour</button>
                    </div>
                </form>
            </Modal>

            <Modal v-if="modal === 'terminer' ">
                <template v-slot:title>TERMINER LE CONTRAT</template>
                <form :action="'/contrat/' + modalData.contrat.id + '/terminer'" method="POST" v-if="modalData.contrat"
                    class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Date de fin effective</label>
                        <input name="date_fin" type="date"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="space-y-2" v-if="modalData.contrat.caution">
                        <label class="text-sm font-medium text-gray-700">Remboursement caution</label>
                        <input
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Montant à restituer" name="remboursement_caution" type="number"
                            step="500">
                    </div>
                    <p class="text-sm text-gray-500">L'heure et la date de fin seront enregistrées définitivement.</p>
                    <div class="grid gap-3 sm:grid-cols-2 mt-3">
                        <button type="button"
                            class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            @click="closeModal()">Annuler</button>
                        <button type="submit"
                            class="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-green-700">Terminer</button>
                    </div>
                </form>
            </Modal>
            <Modal v-if="modal === 'changer-voiture' ">
                <template v-slot:title>Changer de véhicule</template>
                <form :action="'/contrats/' + modalData.contrat.id + '/changer-voiture'" method="POST"
                    v-if="modalData.contrat" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Sélectionner un véhicule</label>
                        <select
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            name="voiture">
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
                    <div class="grid gap-3 sm:grid-cols-2 mt-3">
                        <button type="button"
                            class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            @click="closeModal()">Fermer</button>
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700">Confirmer</button>
                    </div>
                </form>
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
            {{-- MODAL CHANGER VOITURE  --}}
            {{-- @if ($compagnie->type === 'véhicules')
                <div class="modal fade" id="changer-voiture-modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Changer Véhicule</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
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
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif --}}
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
        const flashModal = document.getElementById('flash-overlay-modal');
        if (flashModal && typeof window.$ === 'function') {
            window.$(flashModal).modal();
        }
        if (typeof window.$ === 'function') {
            window.$('div.alert').not('.alert-important').delay(3000).fadeOut(350);
        }
    </script>
@endsection
