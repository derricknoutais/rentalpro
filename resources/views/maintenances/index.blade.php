@extends('layouts.app')


@section('content')

    <h1 class="text-center mt-5">
        Maintenances Voitures
    </h1>

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
                            <div id="accordianId" role="tablist" aria-multiselectable="true">
                                @foreach ($maintenances as $maintenance)
                                    <div class="card">
                                        <div class="card-header" role="tab" id="section1HeaderId" >
                                            <h5 class="mb-0">
                                                <a data-toggle="collapse" data-parent="#accordianId" href="#content{{ $maintenance->id }}" aria-expanded="true" aria-controls="section1ContentId">
                                                    {{ $maintenance->titre }}
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="content{{ $maintenance->id }}" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                            <div class="card-body">
                                                <h3>Détails</h3>
                                                <div class="row mt-3">
                                                    <div class="col-4">
                                                        <h5>
                                                            Maintenancier: {{ $maintenance->technicien->nom }}
                                                        </h5>
                                                        
                                                    </div>
                                                    <div class="col-4">
                                                        <h5>
                                                            Coût de Maintenance: {{ $maintenance->coût }} F CFA
                                                        </h5>
                                                        
                                                    </div>
                                                </div>
                                                <h3 class="mt-5">Pannes</h3>
                                                <div class="list-group">
                                                    @foreach($maintenance->pannes as $panne)
                                                        <a href="#" class="list-group-item list-group-item-action">
                                                            @if ( $panne->estRésolue() )
                                                                <i class="fas fa-check text-success"></i>
                                                            @elseif( $panne->estNonRésolue())
                                                                <i class="fas fa-times text-danger "></i>
                                                            @elseif( $panne->estEnMaintenance()) 
                                                            <i class="fas fa-tools text-primary"></i>

                                                            @endif
                                                            
                                                            {{ $panne->description }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            

                            
                            
                        </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
