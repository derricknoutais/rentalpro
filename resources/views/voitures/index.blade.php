@extends('layouts.app')


@section('content')
    <voitures-index inline-template>
        <div class="container">
            <h1 class="text-center my-5">Répertoire de Véhicules</h1>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Immatriculation</th>
                        <th>Type</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($voitures as $voiture)
                        <tr @click="relocateTo({{ $voiture->id }})">
                            <td scope="row"><a href="/voiture/{{ $voiture->id }}">{{ $voiture->immatriculation }}</a></td>
                            <td>{{ $voiture->marque . ' ' . $voiture->type }}</td>
                            <td>
                                @if($voiture->etat === 'loué')
                                    <span class="dot bg-warning"></span>
                                @elseif($voiture->etat === 'disponible')
                                    <span class="dot bg-success"></span>
                                @elseif($voiture->etat === 'maintenance')
                                    <span class="dot bg-danger"></span>    
                                @endif
                                {{ $voiture->etat }}
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
            
        </div>
        
    </voitures-index>
@endsection