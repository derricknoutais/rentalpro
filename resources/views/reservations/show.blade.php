@extends('layouts.app')

@section('content')
    <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 py-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold tracking-wide text-indigo-600 uppercase">Réservation #{{ $reservation->id }}</p>
                <h1 class="mt-1 text-2xl font-semibold text-gray-900">Détails de la réservation</h1>
                <p class="text-sm text-gray-500">Créée le {{ $reservation->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/reservations"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Retour au calendrier
                </a>
                <a href="/contrats/create?reservation_id={{ $reservation->id }}"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">
                    Convertir en contrat
                </a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-2xl bg-white p-6 shadow ring-1 ring-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Informations client</h2>
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset ring-gray-200 text-gray-600">
                        {{ $statusOptions[$reservation->statut] ?? ucfirst($reservation->statut) }}
                    </span>
                </div>
                <dl class="mt-6 space-y-4 text-sm text-gray-600">
                    <div>
                        <dt class="font-medium text-gray-500">Nom complet</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">
                            {{ optional($reservation->client)->nom }} {{ optional($reservation->client)->prenom }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="font-medium text-gray-500">Téléphone</dt>
                            <dd class="mt-1">{{ optional($reservation->client)->phone1 ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Email</dt>
                            <dd class="mt-1">{{ optional($reservation->client)->mail ?? '—' }}</dd>
                        </div>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Adresse</dt>
                        <dd class="mt-1">{{ optional($reservation->client)->adresse ?? '—' }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow ring-1 ring-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Détails opérationnels</h2>
                <dl class="mt-6 space-y-4 text-sm text-gray-600">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="font-medium text-gray-500">Début</dt>
                            <dd class="mt-1 text-base font-semibold text-gray-900">
                                {{ optional($reservation->du)->format('d/m/Y H:i') ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Fin</dt>
                            <dd class="mt-1 text-base font-semibold text-gray-900">
                                {{ optional($reservation->au)->format('d/m/Y H:i') ?? '—' }}
                            </dd>
                        </div>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Contractable</dt>
                        <dd class="mt-1">
                            {{ optional($reservation->contractable)->immatriculation ?? optional($reservation->contractable)->nom ?? '—' }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="font-medium text-gray-500">Montant chauffeur</dt>
                            <dd class="mt-1">{{ number_format($reservation->montant_chauffeur ?? 0, 0, ',', ' ') }} FCFA</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Demi-journée</dt>
                            <dd class="mt-1">{{ number_format($reservation->demi_journee ?? 0, 0, ',', ' ') }} FCFA</dd>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="font-medium text-gray-500">Caution</dt>
                            <dd class="mt-1">{{ number_format($reservation->caution ?? 0, 0, ',', ' ') }} FCFA</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Total estimé</dt>
                            <dd class="mt-1 text-base font-semibold text-gray-900">
                                {{ number_format($reservation->total ?? 0, 0, ',', ' ') }} FCFA
                            </dd>
                        </div>
                    </div>
                </dl>
            </section>
        </div>

        @if ($reservation->note)
            <section class="mt-6 rounded-2xl bg-white p-6 shadow ring-1 ring-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Notes</h2>
                <p class="mt-4 text-sm text-gray-700 whitespace-pre-wrap">{{ $reservation->note }}</p>
            </section>
        @endif
    </div>
@endsection
