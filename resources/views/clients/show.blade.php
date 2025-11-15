@extends('layouts.app')

@section('content')
    <client-show inline-template :client_prop="{{ $client }}">
        <div class="px-4 py-6 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Profil client</p>
                        <h1 class="text-3xl font-semibold text-gray-900">{{ $client->nom }} {{ $client->prenom }}</h1>
                        <p class="text-sm text-gray-500">{{ $client->ville ?? 'Ville non renseignée' }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="/contrats/create?client_id={{ $client->id }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">
                            <i class="fas fa-plus"></i>
                            Ajouter un contrat
                        </a>
                        <a href="/clients/{{ $client->id }}/edit"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                            <i class="fas fa-edit text-sm"></i>
                            Modifier
                        </a>
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-lg border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50"
                            @click="deleteClient">
                            <i class="fas fa-trash text-sm"></i>
                            Supprimer
                        </button>
                    </div>
                </div>
                <div class="grid gap-6 px-6 py-8 md:grid-cols-3">
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Coordonnées</p>
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">Téléphone 1 :</span>
                            <span>{{ $client->phone1 }}</span>
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">Téléphone 2 :</span>
                            <span>{{ $client->phone2 ?? '—' }}</span>
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">Téléphone 3 :</span>
                            <span>{{ $client->phone3 ?? '—' }}</span>
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">Email :</span>
                            <span>{{ $client->mail ?? '—' }}</span>
                        </p>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Identité</p>
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">Adresse :</span>
                            <span>{{ $client->adresse ?? '—' }}</span>
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">Ville :</span>
                            <span>{{ $client->ville ?? '—' }}</span>
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">Permis :</span>
                            <span>{{ $client->numero_permis ?? '—' }}</span>
                        </p>
                        <p class="text-sm text-gray-500">
                            Numéro de contrats : {{ $client->nombreLocations() }}
                        </p>
                    </div>
                    <div class="space-y-3 text-sm text-gray-700">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Permis scanné</p>
                        @if ($client->image)
                            <div class="rounded-xl border border-gray-100 p-2">
                                <img src="{{ $client->image->url }}" alt="Permis" class="h-48 w-full object-contain" />
                            </div>
                        @else
                            <div class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-gray-400">
                                Aucun permis enregistré.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Contrats associés</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3 text-left">Contrat Nº</th>
                                @if ($client->compagnie->type === 'véhicules')
                                    <th class="px-4 py-3 text-left">Immatriculation</th>
                                @else
                                    <th class="px-4 py-3 text-left">Numéro Chambre</th>
                                @endif
                                <th class="px-4 py-3 text-left">Date du</th>
                                <th class="px-4 py-3 text-left">Date au</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-sm text-gray-700">
                            @forelse ($client->contrats as $contrat)
                                <tr class="cursor-pointer hover:bg-gray-50"
                                    @click="relocateTo('/contrat/{{ $contrat->id }}')">
                                    <td class="px-4 py-3">
                                        <a href="/contrat/{{ $contrat->id }}" class="font-semibold text-gray-900">
                                            {{ $contrat->numéro }}
                                        </a>
                                    </td>
                                    @if ($contrat->compagnie->type === 'véhicules')
                                        <td class="px-4 py-3">
                                            @if ($contrat->contractable)
                                                <a href="/voiture/{{ $contrat->contractable->id }}"
                                                    class="text-indigo-600 hover:underline">
                                                    {{ $contrat->contractable->immatriculation }}
                                                </a>
                                            @else
                                                Voiture supprimée
                                            @endif
                                        </td>
                                    @else
                                        <td class="px-4 py-3">
                                            {{ $contrat->contractable->nom ?? '—' }}
                                        </td>
                                    @endif
                                    <td class="px-4 py-3">
                                        {{ optional($contrat->du)->format('d-M-Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ optional($contrat->au)->format('d-M-Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Aucun contrat pour ce client.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </client-show>
@endsection
