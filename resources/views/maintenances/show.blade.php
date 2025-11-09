@extends('layouts.app')

@section('content')
    @php
        $statusStyles = [
            'en cours' => ['label' => 'En cours', 'classes' => 'bg-blue-50 text-blue-700 ring-blue-500/20'],
            'en pause' => ['label' => 'En pause', 'classes' => 'bg-yellow-50 text-yellow-700 ring-yellow-500/20'],
            'terminé' => ['label' => 'Terminé', 'classes' => 'bg-green-50 text-green-700 ring-green-500/20'],
        ];
        $status = $statusStyles[$maintenance->statut ?? 'en cours'] ?? $statusStyles['en cours'];
    @endphp

    <div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Maintenance #{{ $maintenance->id }}
                        @if ($maintenance->titre)
                            <span class="text-lg text-gray-500 font-normal">· {{ $maintenance->titre }}</span>
                        @endif
                    </h1>
                    <span
                        class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold ring-1 {{ $status['classes'] }}">
                        {{ $status['label'] }}
                    </span>
                </div>
                <p class="text-sm text-gray-500">
                    Créée le {{ optional($maintenance->created_at)->format('d/m/Y H:i') }}
                    — Mise à jour le {{ optional($maintenance->updated_at)->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ url('/maintenances') }}"
                    class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    ← Retour
                </a>
                <a href="{{ url('/maintenance/' . $maintenance->id . '/edit') }}"
                    class="inline-flex items-center rounded-lg border border-indigo-200 px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50">
                    Modifier
                </a>
                <a href="{{ $printUrl }}" target="_blank"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">
                    Imprimer
                </a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <section class="rounded-2xl bg-white p-5 shadow ring-1 ring-gray-100">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Informations principales</h2>
                <dl class="mt-4 space-y-4 text-sm text-gray-700">
                    <div class="flex items-start justify-between gap-3">
                        <dt class="font-medium text-gray-500">Contractable</dt>
                        <dd class="text-right">
                            @if ($maintenance->contractable)
                                <p class="font-semibold text-gray-900">
                                    {{ $maintenance->contractable->immatriculation ?? $maintenance->contractable->nom }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ class_basename($maintenance->contractable) }}
                                </p>
                                @if ($contractableUrl)
                                    <a href="{{ $contractableUrl }}" target="_blank"
                                        class="text-indigo-600 text-xs font-semibold hover:underline">Voir la fiche</a>
                                @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <dt class="font-medium text-gray-500">Technicien</dt>
                        <dd class="text-right">
                            @if ($maintenance->technicien)
                                <p class="font-semibold text-gray-900">
                                    {{ $maintenance->technicien->nom }}
                                </p>
                                @if ($technicienUrl)
                                    <a href="{{ $technicienUrl }}" target="_blank"
                                        class="text-indigo-600 text-xs font-semibold hover:underline">Voir le profil</a>
                                @endif
                            @else
                                <span class="text-gray-400">Non attribué</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <dt class="font-medium text-gray-500">ID interne</dt>
                        <dd class="text-right text-gray-900 font-semibold">#{{ $maintenance->id }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl bg-white p-5 shadow ring-1 ring-gray-100">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Finances</h2>
                <dl class="mt-4 space-y-4 text-sm text-gray-700">
                    <div class="flex items-center justify-between">
                        <dt class="font-medium text-gray-500">Main d'oeuvre</dt>
                        <dd class="text-right font-semibold text-gray-900">
                            {{ $maintenance['coût'] ? number_format($maintenance['coût'], 0, ',', ' ') . ' FCFA' : '—' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="font-medium text-gray-500">Pièces</dt>
                        <dd class="text-right font-semibold text-gray-900">
                            {{ $maintenance['coût_pièces'] ? number_format($maintenance['coût_pièces'], 0, ',', ' ') . ' FCFA' : '—' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-dashed border-gray-100 pt-4">
                        <dt class="font-semibold text-gray-900">Total estimé</dt>
                        @php
                            $total = ($maintenance['coût'] ?? 0) + ($maintenance['coût_pièces'] ?? 0);
                        @endphp
                        <dd class="text-right font-semibold text-gray-900">
                            {{ $total ? number_format($total, 0, ',', ' ') . ' FCFA' : '—' }}
                        </dd>
                    </div>
                </dl>
            </section>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <section class="rounded-2xl bg-white p-5 shadow ring-1 ring-gray-100">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Commentaire</h2>
                <p class="mt-3 text-sm text-gray-700 leading-relaxed">
                    {{ $maintenance->note ? $maintenance->note : 'Aucune note n’a été enregistrée.' }}
                </p>
            </section>

            <section class="rounded-2xl bg-white p-5 shadow ring-1 ring-gray-100">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Raccourcis</h2>
                <div class="mt-4 grid gap-3 text-sm text-indigo-600 font-semibold">
                    @if ($contractableUrl)
                        <a href="{{ $contractableUrl }}" target="_blank" class="hover:underline flex items-center gap-2">
                            <span>→ Voir le contractable</span>
                        </a>
                    @endif
                    @if ($technicienUrl)
                        <a href="{{ $technicienUrl }}" target="_blank" class="hover:underline flex items-center gap-2">
                            <span>→ Voir le technicien</span>
                        </a>
                    @endif
                    <a href="{{ url('/maintenance/' . $maintenance->id . '/edit') }}" class="hover:underline flex items-center gap-2">
                        <span>→ Modifier la maintenance</span>
                    </a>
                    <a href="{{ $printUrl }}" target="_blank" class="hover:underline flex items-center gap-2">
                        <span>→ Version imprimable</span>
                    </a>
                </div>
            </section>
        </div>

        <section class="rounded-2xl bg-white p-5 shadow ring-1 ring-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Pannes prises en charge</h2>
                <span class="text-xs text-gray-400">{{ $maintenance->pannes->count() }} entrée(s)</span>
            </div>

            <div class="mt-4 divide-y divide-gray-100">
                @forelse ($maintenance->pannes as $panne)
                    @php
                        $panneStatus = [
                            'non-résolue' => ['label' => 'Non résolue', 'classes' => 'bg-red-50 text-red-700 ring-red-500/20'],
                            'en-maintenance' => ['label' => 'En maintenance', 'classes' => 'bg-yellow-50 text-yellow-700 ring-yellow-500/20'],
                            'résolue' => ['label' => 'Résolue', 'classes' => 'bg-green-50 text-green-700 ring-green-500/20'],
                        ][$panne->etat] ?? ['label' => ucfirst($panne->etat), 'classes' => 'bg-gray-50 text-gray-600 ring-gray-400/20'];
                    @endphp
                    <div class="flex flex-wrap items-start justify-between gap-4 py-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $panne->description ?? 'Sans description' }}</p>
                            <p class="text-xs text-gray-500">
                                Signalée le {{ optional($panne->created_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $panneStatus['classes'] }}">
                            {{ $panneStatus['label'] }}
                        </span>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-gray-500">
                        Aucune panne n’est associée à cette maintenance.
                    </p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
