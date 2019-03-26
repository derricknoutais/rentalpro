
@extends('layouts.app')

@section('content')
    @if(sizeof($voitures) > 0)
        <div class="container">
            <div class="row mt-5" >
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="card " style="height: 30vh" >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <h3 class="card-title mt-1">Disponible</h3>
                            </div>
                            <div class="col-2 ">
                                <span class="badge badge-success px-3"><h6 class="mt-2">{{ $voitures[0]->compteVoitures('disponible') }}</h6></span>
                            </div>
                        </div>

                        <div class="list-group mt-3 overflow-auto" style="height: 20vh">
                            @foreach ($voitures as $voiture)
                                @if ($voiture->etat == 'disponible')
                                    <a href="/voiture/{{ $voiture->id }}" class="list-group-item list-group-item-action">{{ $voiture->immatriculation }}</a>
                                @endif
                            @endforeach    
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="card " style="height: 30vh">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <h3 class="card-title mt-1">En Maintenance </h3>
                            </div>
                            <div class="col-2 ">
                                <span class="badge badge-danger px-3"><h6 class="mt-2">{{ $voitures[0]->compteVoitures('maintenance') }}</h6></span>
                            </div>
                        </div>

                        <div class="list-group mt-3 overflow-auto" style="height: 20vh">
                            @foreach ($voitures as $voiture)
                                @if ($voiture->etat == 'maintenance')
                                    <a href="/voiture/{{ $voiture->id }}" class="list-group-item list-group-item-action">{{ $voiture->immatriculation }}</a>
                                @endif
                            @endforeach

                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="card" >
                    <div class="card-body" style="height:30vh">
                        <div class="row">
                            <div class="col-10">
                                <h3 class="card-title mt-1">En Location </h3>
                            </div>
                            <div class="col-2 ">
                                <span class="badge badge-warning px-3"><h6 class="mt-2">{{ $voitures[0]->compteVoitures('loué') }}</h6></span>
                            </div>
                        </div>

                        <div class="list-group mt-3 overflow-auto" style="height:20vh">
                            @foreach ($voitures as $voiture)
                                @if ($voiture->etat == 'loué')
                                    <a href="/voiture/{{ $voiture->id }}" class="list-group-item list-group-item-action">{{ $voiture->immatriculation }}</a>
                                @endif
                            @endforeach

                        </div>
                    </div>
                    </div>
                </div>
                
            </div>
            {{-- <div class="row mt-5">
                <div class="col">
                    <a name="" id="" class="" href="/contrats/create" role="button">
                        <h4>Créer un Nouveau Contrat</h4>
                    </a>
                </div>

                <div class="col">
                    <a name="" id="" class="" href="#" role="button">
                        <h4x c >Réceptionner un véhicule</h4>
                    </a>
                </div>

                <div class="col">
                    <a name="" id="" class="" href="#" role="button">Voir les maintenances</a>
                </div>
            </div> --}}
        </div>
    @else
        <div class="container">
            
            <div class="row mt-5">
                <div class="col text-center">
                    <img src="https://requestreduce.org/images/car-clipart-image-17.png" width="30%"/>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col text-center mt-5">
                    Aucune voiture n'est enregistrée. Veuillez enregistrer une voiture
                </div>
            </div>
            
        </div>
    @endif
    
@endsection