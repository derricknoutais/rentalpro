@extends('layouts.app')


@section('content')
    <clients-index inline-template>
        <div class="container">
            <h1 class="text-center mt-5">Répertoire Clients</h1>
            <div class="row mt-5">
                <div class="col">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Numéro de Phone</th>
                                <th>Nombre Locations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr class="pointer" @click="relocateTo('/clients/ {{ $client->id }}')">
                                    <td scope="row">{{ $client->nom }}</td>
                                    <td>{{ $client->phone1 }}</td>
                                    <td>{{ $client->nombreLocations() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </clients-index>
@endsection