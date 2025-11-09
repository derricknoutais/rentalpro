@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Rapport de performance</h1>
            <p class="text-sm text-gray-500">Analysez les ventes, réservations, taux d’occupation et ROI sur les périodes clés.</p>
        </div>
    </div>

    <rapport-dashboard
        stats-url="{{ route('rapports.analytics.stats') }}"
        :presets='@json($presets)'
        :voitures='@json($voitures)'
        default-preset="{{ $defaultPreset }}"
    ></rapport-dashboard>
</div>
@endsection
