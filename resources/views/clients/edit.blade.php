@extends('layouts.app')

@section('content')
    <client-edit inline-template :prop_client="{{ $client }}">
        <div class="px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Clients</p>
                    <h1 class="text-3xl font-semibold text-gray-900">Modifier {{ $client->nom }} {{ $client->prenom }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="/clients/{{ $client->id }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-arrow-left text-sm"></i>
                        Retour
                    </a>
                </div>
            </div>
            <form action="/clients/{{ $client->id }}/update" method="POST" enctype="multipart/form-data" id="clientUpdateForm">
                @csrf
                <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nom *</label>
                            <input type="text" name="nom" v-model="client.nom"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Prénom *</label>
                            <input type="text" name="prenom" v-model="client.prenom"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Téléphone principal *</label>
                            <input type="text" name="numero_telephone" v-model="client.numero_telephone"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Téléphone 2</label>
                            <input type="text" name="numero_telephone2" v-model="client.numero_telephone2"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Téléphone 3</label>
                            <input type="text" name="numero_telephone3" v-model="client.numero_telephone3"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="mail" v-model="client.mail"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Ville</label>
                            <input type="text" name="ville" v-model="client.ville"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Numéro de permis</label>
                            <input type="text" name="numero_permis" v-model="client.numero_permis"
                                class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Adresse</label>
                        <textarea name="addresse" v-model="client.addresse" rows="3"
                            class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Permis (optionnel)</label>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <permis-pond
                                    folder="permis"
                                    @file-processed="attributeImage"
                                    @file-removed="clearAttributeImage"
                                    label="Importer le permis"
                                ></permis-pond>
                            </div>
                            <div class="space-y-2">
                                @if ($client->image)
                                    <img src="{{ $client->image->url }}" alt="Permis"
                                        class="h-40 w-full rounded-xl border border-gray-100 object-contain" />
                                    <button type="button" class="rounded-lg border border-rose-300 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50"
                                        @click="deleteImage">
                                        Supprimer le permis actuel
                                    </button>
                                @else
                                    <div class="rounded-xl border border-dashed border-gray-200 p-4 text-center text-xs text-gray-500">
                                        Aucun permis n’est encore enregistré.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="image_id" :value="client.image_id">
                    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            onclick="window.history.back();">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">
                            <i class="fas fa-save"></i>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </client-edit>
@endsection
