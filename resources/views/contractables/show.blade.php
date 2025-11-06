@extends('layouts.app')

@section('content')
    <contractable-show inline-template :contractable_prop="{{ $contractable }}" {{-- :pannes_prop="{{ $contractable->pannes }}" --}}
        :contrats_prop="{{ $contrats }}" :documents_prop="{{ $documents }}" :accessoires_prop="{{ $accessoires }}">
        <div>
            <div class="mt-10 md:flex md:items-center md:justify-between md:space-x-5">
                <div class="flex items-start space-x-5">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <img class="w-16 h-16 rounded-full" src="{{ $contractable->photo_url }}" alt="">
                            <span class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="pt-1.5">
                        <h1 class="text-2xl font-bold text-gray-900">@{{ contractable.immatriculation }}</h1>
                        <p class="text-md font-medium text-gray-500">@{{ contractable.marque }} @{{ contractable.type }}
                        </p>
                        <p class="text-sm font-medium text-gray-500">@{{ contractable.chassis }}</p>
                    </div>
                </div>
                <div
                    class="flex flex-col-reverse mt-6 space-y-4 space-y-reverse justify-stretch sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">

                    <a :href="'/contrats/create?contractable_id=' + contractable.id"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                        Faire Louer
                    </a>
                    <button type="button" @click="showModal = true"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-yellow-500">
                        Signaler une Panne
                    </button>
                    <button type="button"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-red-500">
                        Envoyer en Maintenance
                    </button>
                </div>
            </div>
            {{-- PANNES --}}
            <div class="mt-10">
                <div class="mt-5 border-t border-gray-200">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 flex sm:gap-4 w-full border-0 border-r-2 ">
                            <dt class="sticky top-0 text-lg font-medium text-gray-500">
                                Pannes
                            </dt>
                            <dd v-if="contractable.pannes.length > 0"
                                class="sm:w-full md:w-1/2  mt-10 overflow-y-auto text-sm text-gray-900 sm:mt-10 ">
                                <ul role="list"
                                    class="divide-y divide-gray-100 overflow-hidden bg-red-50 shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">

                                    <li class="relative flex justify-between gap-x-6 px-4 py-4 hover:bg-gray-50 sm:px-6"
                                        v-for="panne in contractable.pannes" :key="panne.id">
                                        <div class="flex gap-x-4">
                                            <div class="min-w-0 flex-auto">
                                                <p class="mt-1 flex text-xs leading-5 text-gray-500">
                                                    <span
                                                        class="relative truncate hover:underline capitalize">@{{ panne.created_at | moment('DD MMM YY') }}</span>
                                                </p>
                                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                                    @{{ panne.description }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center gap-2">
                                            <button @click="editPanne(panne)"
                                                class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 "
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                            <button @click="deletePanne(panne.id)"
                                                class="text-red-600 hover:text-red-800 font-medium ml-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 "
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </li>

                                </ul>
                            </dd>
                            <button type="button" @click="showModal = true" v-else
                                class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600 dark:border-white/15 dark:hover:border-white/25 dark:focus:outline-indigo-500">
                                <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" aria-hidden="true"
                                    class="mx-auto w-6 h-6 text-gray-400 dark:text-gray-500">
                                    <path
                                        d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m0-4c0 4.418-7.163 8-16 8S8 28.418 8 24m32 10v6m0 0v6m0-6h6m-6 0h-6"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="mt-2 block text-sm font-semibold text-gray-900 dark:text-white">Ajouter une
                                    panne</span>
                            </button>
                        </div>
                    </dl>

                </div>
            </div>

            {{-- CONTRATS --}}
            <div class="mt-10">
                <div class="mt-5 border-t border-gray-200">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 w-full border border-0 border-r-2 ">
                            <dt class="sticky top-0 text-lg font-medium text-gray-500">
                                Contrats
                            </dt>
                            <dd class="max-h-screen mt-10 overflow-y-auto text-sm text-gray-900 sm:mt-10 sm:col-span-2">
                                <ul role="list"
                                    class="divide-y divide-gray-100 overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">

                                    <li class="relative flex justify-between gap-x-6 px-4 py-4 hover:bg-gray-50 sm:px-6"
                                        v-for="contrat in contrats">
                                        <div class="flex gap-x-4">
                                            <div class="min-w-0 flex-auto">
                                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                                    <a :href="'/contrat/' + contrat.id">
                                                        <span class="absolute inset-x-0 -top-px bottom-0"></span>
                                                        @{{ contrat.numéro }}
                                                    </a>
                                                </p>
                                                <p class="mt-1 flex text-xs leading-5 text-gray-500">
                                                    <span
                                                        class="relative truncate hover:underline capitalize">@{{ contrat.du | moment('DD MMM YY') }}</span>
                                                    <span class="ml-1"> - </span>
                                                    <span
                                                        class="ml-1 relative truncate hover:underline">@{{ contrat.au | moment('DD MMM YY') }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-x-4">
                                            <div class="hidden sm:flex sm:flex-col sm:items-end" v-if="contrat.client">
                                                <p class="text-sm leading-6 text-gray-900">
                                                    @{{ contrat.client.nom }}</p>
                                                <p class="mt-1 text-xs leading-5 text-gray-500"> @{{ contrat.client.phone1 }}
                                                </p>
                                            </div>
                                            <svg class="h-5 w-5 flex-none text-gray-400" viewBox="0 0 20 20"
                                                fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </li>

                                </ul>
                            </dd>
                        </div>
                        {{-- Documents & Accessoires --}}
                        <div
                            class="border-b border-gray-200 pb-5 sm:flex sm:items-center sm:justify-between dark:border-white/10">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Documents</h3>
                            <div class="mt-3 flex sm:ml-4 sm:mt-0">

                                <button type="button"
                                    class="ml-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:shadow-none dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                                    Modifier
                                </button>
                            </div>
                        </div>

                        <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            <li v-for="document in contractable.documents"
                                class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow dark:divide-white/10 dark:bg-gray-800/50 dark:shadow-none dark:outline dark:outline-1 dark:-outline-offset-1 dark:outline-white/10">
                                <div class="flex w-full items-center justify-between space-x-6 p-6">
                                    <div class="flex-1 truncate">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                                @{{ document.type }}</h3>
                                            <span
                                                v-if="document.pivot && document.pivot.date_expiration && document.pivot.date_expiration > new Date()"
                                                class="inline-flex shrink-0 items-center rounded-full bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-500 dark:ring-green-500/10">Valide</span>
                                            <span v-else
                                                class="inline-flex shrink-0 items-center rounded-full bg-red-50 px-1.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-500 dark:ring-red-500/10">Expiré</span>
                                        </div>
                                        <p class="mt-1 truncate text-sm text-gray-500 dark:text-gray-400">
                                            @{{ document.pivot.date_expiration | moment('D MMMM YYYY') }}</p>
                                    </div>
                                </div>
                                <div>
                                    <div class="-mt-px flex divide-x divide-gray-200 dark:divide-white/10">
                                        <div class="flex w-0 flex-1">
                                            <a href="mailto:janecooper@example.com"
                                                class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon"
                                                    aria-hidden="true" class="w-5 text-gray-400 dark:text-gray-500">
                                                    <path
                                                        d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                                                    <path
                                                        d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                                                </svg>
                                                Email
                                            </a>
                                        </div>
                                        <div class="-ml-px flex w-0 flex-1">
                                            <a href="tel:+1-202-555-0170"
                                                class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon"
                                                    aria-hidden="true" class="w-5 text-gray-400 dark:text-gray-500">
                                                    <path
                                                        d="M2 3.5A1.5 1.5 0 0 1 3.5 2h1.148a1.5 1.5 0 0 1 1.465 1.175l.716 3.223a1.5 1.5 0 0 1-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 0 0 6.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 0 1 1.767-1.052l3.223.716A1.5 1.5 0 0 1 18 15.352V16.5a1.5 1.5 0 0 1-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 0 1 2.43 8.326 13.019 13.019 0 0 1 2 5V3.5Z"
                                                        clip-rule="evenodd" fill-rule="evenodd" />
                                                </svg>
                                                Call
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        </ul>

                        <div
                            class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:grid-rows-5 sm:gap-4 w-full border border-0 border-r-2 ">
                            <dt class="sticky top-0 text-lg font-medium text-gray-500">
                                Documents & Accessoires
                            </dt>

                        </div>
                    </dl>
                </div>
            </div>

            {{-- MODAL PANNES --}}
            <div v-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/3 p-6">
                    <h2 class="text-xl font-semibold mb-4">
                        Signaler une Panne
                    </h2>
                    <form @submit.prevent="submitFormulairePannes">
                        @csrf
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 font-medium mb-2">Description de la
                                panne</label>
                            <textarea id="description" v-model="form_panne.description" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <input type="hidden" v-model="form_panne.contractable_id">
                        <div class="flex justify-end space-x-2">
                            <button type="button" class="px-4 py-2 bg-gray-200 rounded" @click="showModal = false">
                                Annuler
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </contractable-show>
@endsection
