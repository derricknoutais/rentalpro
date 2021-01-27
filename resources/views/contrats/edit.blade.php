@extends('layouts.app')


@section('content')
<contrat-edit :contrat_prop="{{ $contrat }}" :clients_prop="{{ $clients }}" inline-template >
    <div class="tw-flex tw-container tw-mx-auto tw-flex-col tw-items-center tw-py-48">
        <h1 class="tw-text-4xl">Modifier Contrat {{ $contrat->numéro }}</h1>
        <form
            class="tw-flex tw-flex-col tw-w-2/3 tw-mt-10" action="/contrat/{{ $contrat->id }}/update-all"
            method="POST" enctype="multipart/form-data" id="clientForm"
            {{-- @submit.prevent="enregistreClientDansCashier()" --}}
        >
            @csrf
            @method('PUT')
            {{-- Champs Clients --}}
            <input type="hidden" name="client" v-model="client.id">
            <div class="tw-flex tw-flex-col" >
                <label for="">Client</label>
                <multiselect :options="clients" label="nom_complet" v-model="client">
                    <template slot="noResult">
                        Ce client n'existe pas
                    </template>
                </multiselect>
            </div>



            {{-- Champs Chambre & Contrat --}}
            {{-- <input type="hidden" class="form-control" id="chambre_id" name="chambre_id" :value="chambreADetailler.id"> --}}
            <label for="" class="tw-mt-3">Date Check-In</label>
            <input type="date" class="form-control " name="du" value="{{ $contrat->du->format('Y-m-d') }}">
            <label for="" class="tw-mt-3">Date Check-Out</label>
            <input type="date" class="form-control " name="au" value="{{ $contrat->au->format('Y-m-d') }}">
            {{-- <input type="text" class="form-control tw-mt-3" name="nombre_jours" value="{{ $contrat->nombre_jours }}"
                placeholder="Nombre de Jours"  readonly> --}}
            <label for="" class="tw-mt-3">Prix Journalier</label>
            <input type="number" class="form-control" name="prix_journalier" placeholder="Prix Journalier" value="{{ $contrat->prix_journalier }}">

            <label for="" class="tw-mt-3">Chambre</label>
            <select type="date" class="form-control" name="contractable">
                <option value="{{ $contrat->contractable->id }}">{{ $contrat->contractable->nom }}</option>
                @foreach ($chambresDisponibles as $chambre)
                    @if ($contrat->contractable->id !== $chambre->id)
                        <option value="{{ $chambre->id }}">{{ $chambre->nom }}</option>
                    @endif
                @endforeach
            </select>

            {{-- Boutons Annuler et Ajouter Client & Réserver --}}
            <div class="tw-flex">
                <button type="submit" class="form-control tw-mb-3 tw-mt-10 tw-bg-gray-800 tw-text-gray-100">
                    Modifier Contrat
                </button>
            </div>

        </form>
    </div>
</contrat-edit>
@endsection
