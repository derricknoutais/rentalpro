@extends('layouts.app')


@section('content')

    <h1 class="text-center mt-5">Maintenances Voitures</h1>

    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <table class="table table-hover">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Maintenance Nº</th>
                            <th>Technicien</th>
                            <th>Voiture</th>
                            <th>Coût</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($maintenances as $maintenance)
                                <tr data-toggle="collapse" data-target="#maintenance{{ $maintenance->id }}" class="clickable">
                                    <td scope="row">{{ $maintenance->id }}</td>
                                    <td>{{ $maintenance->technicien->nom }}</td>
                                    <td>{{ $maintenance->voiture->immatriculation }}</td>
                                    <td>{{ $maintenance->coût }}</td>
                                </tr>
                                <tr  id="maintenance{{ $maintenance->id }}" class="collapse">
                                    <td colspan="3">
                                        <div>Hidden by default</div>
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
