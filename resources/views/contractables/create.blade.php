@extends('layouts.app')

@section('content')
    <contractable-create inline-template>
        <div class="max-w-5xl px-4 py-10 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm ring-1 ring-gray-200 sm:rounded-xl">
                <div class="px-6 py-6 border-b border-gray-100 sm:px-8">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Ajouter un contractable
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Renseignez les informations du véhicule et ajoutez les photos nécessaires pour compléter le dossier.
                    </p>
                </div>

                <form ref="form" action="/voitures/ajout-voiture" method="POST" class="px-6 py-8 space-y-10 sm:px-8"
                    @submit.prevent="submitForm">
                    @csrf

                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Informations générales</h2>
                        <p class="mt-1 text-sm text-gray-500">Ces informations permettront d'identifier rapidement le véhicule.</p>

                        <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-6 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="immatriculation" class="block text-sm font-medium text-gray-700">Immatriculation <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="immatriculation" id="immatriculation" required
                                    v-model="form.immatriculation"
                                    class="block w-full px-3 py-2 mt-2 text-gray-900 placeholder-gray-400 transition border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="marque" class="block text-sm font-medium text-gray-700">Marque</label>
                                <input type="text" name="marque" id="marque" v-model="form.marque"
                                    class="block w-full px-3 py-2 mt-2 text-gray-900 placeholder-gray-400 transition border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="type" class="block text-sm font-medium text-gray-700">Modèle</label>
                                <input type="text" name="type" id="type" v-model="form.type"
                                    class="block w-full px-3 py-2 mt-2 text-gray-900 placeholder-gray-400 transition border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="numero_chassis" class="block text-sm font-medium text-gray-700">Numéro de châssis</label>
                                <input type="text" name="numero_chassis" id="numero_chassis" v-model="form.numero_chassis"
                                    class="block w-full px-3 py-2 mt-2 text-gray-900 placeholder-gray-400 transition border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="annee" class="block text-sm font-medium text-gray-700">Année</label>
                                <input type="number" name="annee" id="annee" v-model="form.annee" min="1900"
                                    class="block w-full px-3 py-2 mt-2 text-gray-900 placeholder-gray-400 transition border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="prix" class="block text-sm font-medium text-gray-700">Prix journalier</label>
                                <input type="number" name="prix" id="prix" step="0.01" min="0" v-model="form.prix"
                                    class="block w-full px-3 py-2 mt-2 text-gray-900 placeholder-gray-400 transition border-0 rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Photos du véhicule</h2>
                        <p class="mt-1 text-sm text-gray-500">Ajoutez plusieurs photos pour documenter l'état du véhicule.</p>

                        <div class="mt-4">
                            <permis-pond :multiple="true" folder="voitures"
                                label="Déposez ou cliquez pour ajouter des photos du véhicule"
                                @file-processed="addImage" @file-removed="removeImage"></permis-pond>
                        </div>

                        <div v-if="form.images.length" class="flex flex-wrap gap-2 mt-4">
                            <span class="px-3 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-full">
                                @{{ form.images.length }} image(s) sélectionnée(s)
                            </span>
                        </div>

                        <input v-for="(imageId, index) in form.images" :key="'image-' + index" type="hidden" name="images[]"
                            :value="imageId">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="/contractables"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-700 transition bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Annuler
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white transition bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-70"
                            :disabled="isSubmitting">
                            <svg v-if="isSubmitting" class="w-4 h-4 mr-2 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>@{{ isSubmitting ? 'Enregistrement en cours...' : 'Créer le véhicule' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </contractable-create>
@endsection
