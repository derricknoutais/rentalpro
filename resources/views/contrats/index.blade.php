@extends('layouts.app')


@section('content')
    <div class="container">
        <h3 class="text-center mt-5">Liste Contrat</h3>
        <table class="table table-hover table-bordered mt-5">
            <thead>
                <tr>
                    <th>Contrat Nº</th>
                    <th>Client</th>
                    <th>Voiture</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contrats as $contrat)
                    <tr class="pointer" @click="relocateTo('/contrat/{{ $contrat->id }}')">
                        <td scope="row">{{ $contrat->numéro }}</td>
                        <td>{{ $contrat->client['nom'] }}</td>
                        <td>{{ $contrat->voiture->immatriculation }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    
@endsection