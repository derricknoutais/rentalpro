@extends('layouts.app')

@section('content')
<contractable-show inline-template
    :contractable_props="{{ $contractable }}"
    :pannes_props="{{ $contractable->pannes }}"
>
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
                    <h1 class="text-2xl font-bold text-gray-900">{{ $contractable->immatriculation }}</h1>
                    <p class="text-sm font-medium text-gray-500">{{ $contractable->marque }} {{ $contractable->type }}</p>
                </div>
            </div>
            <div
                class="flex flex-col-reverse mt-6 space-y-4 space-y-reverse justify-stretch sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">

                <button type="button"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                    Faire Louer
                </button>
                <button type="button"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
                    Envoyer en Maintenance
                </button>
            </div>
        </div>

        <div class="mt-10">
            <div>
                <h3 class="text-lg font-medium leading-6 text-gray-900">
                    Détails Voiture
                </h3>
                <p class="max-w-2xl mt-1 text-sm text-gray-500">
                    Personal details and application.
                </p>
            </div>
            <div class="mt-5 border-t border-gray-200">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 w-1/2 border border-0 border-r-2 ">
                        <dt class="sticky top-0 text-lg font-medium text-gray-500">
                            Contrats
                        </dt>
                        <dd class="max-h-screen mt-10 overflow-y-auto text-sm text-gray-900 sm:mt-10 sm:col-span-2">
                            <ul role="list" class="border border-gray-200 divide-y divide-gray-200 rounded-md">
                                @foreach ($contractable->contrats->reverse()->take(3) as $contrat)
                                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                                    <div class="flex items-center flex-1 w-0">
                                        <span class="flex-1 w-0 ml-2 truncate">
                                            {{ $contrat->client->nom }} {{ $contrat->client->prenom }}
                                        </span>
                                        <span class="flex-1 w-0 ml-2 text-red-400 truncate">
                                            {{ $contrat->du->format('d/m/Y') }} - {{ $contrat->au->format('d/m/Y') }}
                                        </span>

                                    </div>
                                    <div class="flex-shrink-0 ml-4">
                                        <a target="_blank" href="/contrat/{{ $contrat->id }}"
                                            class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Voir Contrat
                                        </a>
                                    </div>
                                </li>
                                @endforeach

                            </ul>
                        </dd>
                    </div>
                    {{-- PANNES --}}
                    <div class="py-4 sm:py-5 flex flex-col w-1/2">
                        <dt class="text-lg font-medium text-gray-500 col-span-2 flex justify-between">
                            <span>
                                Pannes
                            </span>
                            <button class="bg-green-400 px-3 py-1 text-gray-700" @click="toggleFormulairePanne">Ajouter Panne</button>
                        </dt>

                        <form v-show="display_formulaire_panne" class="flex flex-col mt-3" @submit.prevent="submitFormulairePannes">
                            <input type="text" class="mt-3" v-model="form.description">
                            <input type="hidden" name="contractable_id" >
                            <div class="flex mt-3">
                                <button type="submit" class="bg-green-400 px-3 py-1 text-gray-700">Enregistrer</button>
                                <button type="button" @click="toggleFormulairePanne" class="bg-red-400 px-3 py-1 text-gray-700 ml-3">Annuler</button>
                            </div>

                        </form>

                        <dd class="max-h-screen mt-10 overflow-y-auto text-sm text-gray-900 sm:mt-10 sm:col-span-3">
                            <ul role="list" class="border border-gray-200 divide-y divide-gray-200 rounded-md">
                                {{-- @foreach ($contractable->pannes as $panne) --}}
                                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm" v-for="panne in pannes">
                                    <div class="flex justify-between flex-1 w-0">
                                        <span class="flex-1 w-0 ml-2 truncate">
                                            @{{ panne.description }}
                                        </span>
                                        <span class="flex-1 w-0 ml-2 text-red-400 truncate text-right">
                                            <button class="text-blue-500 ">Edit</button>
                                            <button class="text-red-500 ml-3">Supprimer</button>
                                        </span>

                                    </div>
                                    <div class="flex-shrink-0 ml-4">
                                        {{-- <a target="_blank" href="/contrat/{{ $contrat->id }}"
                                            class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Voir Contrat
                                        </a> --}}
                                    </div>
                                </li>
                                {{-- @endforeach --}}

                            </ul>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</contractable-show>


@endsection
