@extends('layouts.app')

@section('content')
    @php
        $formatMoney = function ($value) {
            if (is_null($value)) {
                return '—';
            }

            return number_format($value, 0, ',', '.') . ' F CFA';
        };

        $formatPercent = function ($value) {
            if (is_null($value)) {
                return '—';
            }

            return number_format($value, 1, ',', '.') . ' %';
        };
    @endphp

    <div class="px-4 py-6 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-gray-500">Bonjour {{ Auth::user()->name }},</p>
                <h1 class="text-3xl font-semibold text-gray-900">Tableau de bord</h1>
                <p class="text-sm text-gray-500">Vue d’ensemble des ventes, locations et réservations.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/reservations/create"
                    class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    Nouvelle réservation
                </a>
                <a href="/contrats/create"
                    class="inline-flex items-center gap-2 rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75A2.25 2.25 0 0014.25 4.5h-9A2.25 2.25 0 003 6.75v10.5A2.25 2.25 0 005.25 19.5h9A2.25 2.25 0 0016.5 17.25V13.5l4.5 4.5v-12l-4.5 4.5z" />
                    </svg>
                    Nouveau contrat
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Chiffre d’affaires YTD</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $formatMoney($dashboardSummary['revenue']) }}</p>
                <p class="mt-1 text-xs text-gray-500">Montant encaissé sur les contrats {{ now()->format('Y') }}</p>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Taux d’encaissement</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $formatPercent($dashboardSummary['payment_rate']) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Ratio paiements / total facturé</p>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Jours loués</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ number_format($dashboardSummary['days_rented'] ?? 0, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Volume cumulé de jours de location</p>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Réservations en attente</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ number_format($dashboardSummary['pending_reservations'] ?? 0, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-gray-500">À traiter dès que possible</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="xl:col-span-2 space-y-6">
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Locations actives</h3>
                            <p class="text-sm text-gray-500">Contrats en cours aujourd’hui</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                            {{ $dashboardSummary['active_contracts'] }} en cours
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                                <tr>
                                    <th class="px-5 py-3">Contrat</th>
                                    <th class="px-5 py-3">Client</th>
                                    <th class="px-5 py-3">Période</th>
                                    <th class="px-5 py-3 text-right">Solde</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($activeContracts as $contrat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-3">
                                            <p class="font-semibold text-gray-900">#{{ $contrat->numéro ?? $contrat->id }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ optional($contrat->contractable)->immatriculation ?? (optional($contrat->contractable)->nom ?? '—') }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-3">
                                            <p class="font-medium text-gray-900">
                                                {{ optional($contrat->client)->nom }}
                                                {{ optional($contrat->client)->prenom }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ optional($contrat->client)->telephone }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-3 text-gray-700">
                                            {{ optional($contrat->du)->format('d M') }} →
                                            {{ optional($contrat->au)->format('d M') }}
                                        </td>
                                        <td class="px-5 py-3 text-right text-sm font-semibold text-gray-900">
                                            {{ $formatMoney($contrat->solde()) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-500">
                                            Aucune location active pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Performance flotte</h3>
                            <p class="text-sm text-gray-500">Utilisation actuelle du parc</p>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            {{ $formatPercent($dashboardSummary['fleet_utilization']) }}
                        </span>
                    </div>
                    <div class="p-5 text-sm text-gray-600">
                        <p>
                            {{ $dashboardSummary['active_contracts'] }} véhicules / chambres mobilisés sur
                            {{ number_format($contractables->count(), 0, ',', '.') }} disponibles.
                        </p>
                        <p class="mt-2 text-gray-500">
                            Gardez un œil sur les entretiens programmés et les véhicules immobiles pour optimiser vos
                            revenus.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Réservations à suivre</h3>
                        <p class="text-sm text-gray-500">Les 5 prochaines échéances</p>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse ($upcomingReservations as $reservation)
                            <li class="px-5 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ optional($reservation->client)->nom }}
                                            {{ optional($reservation->client)->prenom }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($reservation->du)->format('d M') }} →
                                            {{ optional($reservation->au)->format('d M') }}
                                        </p>
                                    </div>
                                    <span
                                        class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600 capitalize">
                                        {{ \App\Reservation::statusOptions()[$reservation->statut] ?? $reservation->statut }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="px-5 py-6 text-center text-sm text-gray-500">
                                Aucune réservation à venir.
                            </li>
                        @endforelse
                    </ul>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h3 class="text-base font-semibold text-gray-900">Derniers paiements</h3>
                        <p class="text-sm text-gray-500">Encaissements les plus récents</p>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse ($recentPayments as $paiement)
                            <li class="px-5 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $formatMoney($paiement->montant) }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($paiement->created_at)->format('d M Y - H:i') }}
                                        </p>
                                    </div>
                                    <span class="text-xs uppercase text-gray-500">{{ $paiement->type_paiement }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="px-5 py-6 text-center text-sm text-gray-500">
                                Aucun paiement enregistré récemment.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Contrats récemment créés</h3>
                    <p class="text-sm text-gray-500">Historique des derniers dossiers</p>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse ($latestContracts as $contrat)
                        <li class="px-5 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        Contrat #{{ $contrat->numéro ?? $contrat->id }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ optional($contrat->created_at)->format('d M Y') }} ·
                                        {{ optional($contrat->contractable)->immatriculation ?? (optional($contrat->contractable)->nom ?? '—') }}
                                    </p>
                                </div>
                                <div class="text-right text-sm font-semibold text-gray-900">
                                    {{ $formatMoney($contrat->total()) }}
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-sm text-gray-500">
                            Aucun contrat créé récemment.
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Clients</h3>
                    <p class="text-sm text-gray-500">Vos profils récemment ajoutés</p>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse ($clients->sortByDesc('created_at')->take(6) as $client)
                        <li class="px-5 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $client->nom }} {{ $client->prenom }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $client->telephone ?? 'Aucun contact' }}</p>
                                </div>
                                <span class="text-xs text-gray-400">
                                    {{ optional($client->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-sm text-gray-500">
                            Aucun client enregistré pour l’instant.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
