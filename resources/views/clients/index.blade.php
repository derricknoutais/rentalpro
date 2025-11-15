@extends('layouts.app')

@php
    $clientsPayload = $clients->map(function ($client) {
        return [
            'id' => $client->id,
            'nom' => $client->nom,
            'prenom' => $client->prenom,
            'phone1' => $client->phone1,
            'phone2' => $client->phone2,
            'phone3' => $client->phone3,
            'mail' => $client->mail,
            'ville' => $client->ville,
            'numero_permis' => $client->numero_permis,
            'adresse' => $client->adresse,
            'nombre_locations' => $client->nombreLocations(),
            'chiffre_affaire' => $client->chiffreAffaire(),
            'paiements_percus' => $client->paiementsPercus(),
            'solde' => $client->solde(),
        ];
    });
@endphp

@section('content')
    <clients-index inline-template :initial-clients='@json($clientsPayload)'>
        <div class="px-4 py-6 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Base clients</p>
                    <h1 class="text-3xl font-semibold text-gray-900">Clients</h1>
                    <p class="text-sm text-gray-500">
                        @{{ totalClients }} client@{{ totalClients > 1 ? 's' : '' }} enregistré@{{ totalClients > 1 ? 's' : '' }}.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-60"
                        @click="fetchClients" :disabled="refreshing">
                        <svg xmlns="http://www.w3.org/2000/svg" :class="['h-4 w-4', refreshing ? 'animate-spin text-indigo-600' : 'text-gray-500']" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 114 9m16 0V4m0 0h-5" />
                        </svg>
                        <span>@{{ refreshing ? 'Actualisation...' : 'Rafraîchir' }}</span>
                    </button>
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500"
                        @click="openCreateModal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6" />
                        </svg>
                        Nouveau client
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative w-full sm:max-w-sm">
                        <input type="search" v-model="searchQuery"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                            placeholder="Rechercher un nom, numéro, email..." />
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M21 21l-4.35-4.35m1.6-4.65a6.25 6.25 0 11-12.5 0 6.25 6.25 0 0112.5 0z" />
                            </svg>
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">
                        Tapez au moins 2 caractères pour filtrer rapidement vos clients.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="cursor-pointer px-4 py-3" @click="setSort('nom')">
                                    <div class="flex items-center gap-1">
                                        <span>Client</span>
                                        <i v-if="sortField === 'nom'" :class="['text-[10px]', sortIcon('nom')]"></i>
                                    </div>
                                </th>
                                <th class="cursor-pointer px-4 py-3" @click="setSort('phone1')">
                                    <div class="flex items-center gap-1">
                                        <span>Contacts</span>
                                        <i v-if="sortField === 'phone1'" :class="['text-[10px]', sortIcon('phone1')]"></i>
                                    </div>
                                </th>
                                <th class="cursor-pointer px-4 py-3 text-center" @click="setSort('nombre_locations')">
                                    <div class="flex items-center justify-center gap-1">
                                        <span>Locations</span>
                                        <i v-if="sortField === 'nombre_locations'" :class="['text-[10px]', sortIcon('nombre_locations')]"></i>
                                    </div>
                                </th>
                                <th class="cursor-pointer px-4 py-3 text-right" @click="setSort('chiffre_affaire')">
                                    <div class="flex items-center justify-end gap-1">
                                        <span>Chiffre d’affaires</span>
                                        <i v-if="sortField === 'chiffre_affaire'" :class="['text-[10px]', sortIcon('chiffre_affaire')]"></i>
                                    </div>
                                </th>
                                <th class="cursor-pointer px-4 py-3 text-right" @click="setSort('paiements_percus')">
                                    <div class="flex items-center justify-end gap-1">
                                        <span>Paiements perçus</span>
                                        <i v-if="sortField === 'paiements_percus'" :class="['text-[10px]', sortIcon('paiements_percus')]"></i>
                                    </div>
                                </th>
                                <th class="cursor-pointer px-4 py-3 text-right" @click="setSort('solde')">
                                    <div class="flex items-center justify-end gap-1">
                                        <span>Solde</span>
                                        <i v-if="sortField === 'solde'" :class="['text-[10px]', sortIcon('solde')]"></i>
                                    </div>
                                </th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="client in filteredClients" :key="client.id"
                                class="cursor-pointer hover:bg-gray-50"
                                @click="relocateTo(`/clients/${client.id}`)">
                                <td class="px-4 py-4 align-top">
                                    <p class="font-semibold text-gray-900">@{{ clientFullName(client) }}</p>
                                    <p class="text-sm text-gray-500">@{{ client.numero_permis || 'Permis non renseigné' }}</p>
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <p class="text-sm text-gray-900">@{{ client.phone1 || '—' }}</p>
                                    <p class="text-xs text-gray-500">
                                        @{{ client.mail || 'Email non renseigné' }} • @{{ client.ville || 'Ville inconnue' }}
                                    </p>
                                </td>
                                <td class="px-4 py-4 text-center text-sm font-semibold text-gray-900">
                                    @{{ client.nombre_locations }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-semibold text-gray-900">
                                    @{{ formatCurrency(client.chiffre_affaire) }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-700">
                                    @{{ formatCurrency(client.paiements_percus) }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-semibold"
                                    :class="client.solde < 0 ? 'text-rose-600' : 'text-emerald-600'">
                                    @{{ formatCurrency(client.solde) }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                            class="inline-flex items-center rounded-full border border-gray-200 px-3 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                            @click.stop="relocateTo(`/clients/${client.id}`)">
                                            Voir
                                        </button>
                                        <button type="button"
                                            class="inline-flex items-center rounded-full border border-rose-300 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50"
                                            @click.stop="confirmDeleteClient(client)">
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!filteredClients.length">
                                <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                                    Aucun client ne correspond à votre recherche.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showCreateModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 py-6">
                <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Nouveau client</h3>
                            <p class="text-xs text-gray-500">Renseignez les informations principales.</p>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600" @click="closeCreateModal">✕</button>
                    </div>
                    <form class="px-6 py-6 space-y-5" @submit.prevent="createClient">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Nom *</label>
                                <input type="text" v-model="form.nom"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <p v-if="errors.nom" class="text-xs text-rose-600 mt-1">@{{ errors.nom[0] }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" v-model="form.prenom"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Téléphone principal *</label>
                                <input type="text" v-model="form.numero_telephone"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <p v-if="errors.numero_telephone" class="text-xs text-rose-600 mt-1">
                                    @{{ errors.numero_telephone[0] }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Téléphone secondaire</label>
                                <input type="text" v-model="form.numero_telephone2"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Email</label>
                                <input type="email" v-model="form.mail"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Ville</label>
                                <input type="text" v-model="form.ville"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Numéro de permis</label>
                                <input type="text" v-model="form.numero_permis"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Téléphone 3</label>
                                <input type="text" v-model="form.numero_telephone3"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Adresse</label>
                                <textarea v-model="form.addresse" rows="3"
                                    class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Permis (optionnel)</label>
                                <permis-pond
                                    folder="permis"
                                    @file-processed="setCreateClientImage"
                                    @file-removed="removeCreateClientImage"
                                    label="Glissez-déposez le permis ou cliquez"
                                ></permis-pond>
                            </div>
                            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                            <button type="button"
                                class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                @click="closeCreateModal">Annuler</button>
                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 disabled:opacity-60"
                                :disabled="saving">
                                <svg v-if="saving" class="mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </clients-index>
@endsection
