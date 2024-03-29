@if(sizeof($voitures) > 0)

    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col">
                <transition
                    name="fade"
                    enter-active-class="animated tada"
                    leave-active-class="animated fadeOut"
                    :duration="{ enter: 50000, leave: 800 }"
                >
                    <h1 class="text-center jumbotron">
                        @if( Auth::check() )
                            Bienvenue sur Rental Pro
                        @endif
                    </h1>
                </transition>

            </div>
        </div>
        <div class="container">
            <div class="col-12">

            </div>
            <div class="row mt-5" >
                {{-- Disponible --}}
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="card " style="height: 100%" >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <h3 class="card-title mt-1">Disponible</h3>
                            </div>
                            <div class="col-2 ">
                                <span class="badge badge-success px-2"><h6 class="mt-2">{{ $voitures[0]->compteVoitures('disponible') }}</h6></span>
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

                {{-- En Maintenance --}}
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="card " style="height: 100%">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <h3 class="card-title mt-1">En Maintenance </h3>
                            </div>
                            <div class="col-2 ">
                                <span class="badge badge-danger px-2"><h6 class="mt-2">{{ $voitures[0]->compteVoitures('maintenance') }}</h6></span>
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

                {{-- En Location --}}
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="card" >
                    <div class="card-body" style="height:100%">
                        <div class="row">
                            <div class="col-10">
                                <h3 class="card-title mt-1">En Location </h3>
                            </div>
                            <div class="col-2 ">
                                <span class="badge badge-warning px-2"><h6 class="mt-2">{{ $voitures[0]->compteVoitures('loué') }}</h6></span>
                            </div>
                        </div>

                        <div class="list-group mt-3 overflow-auto" style="height:20vh">
                            @foreach ($voitures as $voiture)
                                @if ($voiture->etat == 'loué' && sizeof($voiture->contrats) > 0 )
                                    <a href="/voiture/{{ $voiture->id }}" class="list-group-item list-group-item-action">{{ $voiture->immatriculation }}<span class="pl-5">{{ $voiture->contrats[0]->du->format('d-M-Y') }}</span></a>
                                @endif
                            @endforeach

                        </div>
                    </div>
                    </div>
                </div>
            </div>


            <div class="row mt-5" >

                {{-- Prochaines Arrivées --}}
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="card " style="height: 100%" >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <h3 class="card-title mt-1">Prochaines Arrivées</h3>
                            </div>
                            <div class="col-2 ">
                                <span class="badge badge-success px-2"><h6 class="mt-2">{{ sizeof($contrats_en_cours) }}</h6></span>
                            </div>
                        </div>

                        <div class="list-group mt-3 overflow-auto" style="height: 20vh">
                            @foreach ($contrats_en_cours as $contrat)
                                <a href="/contrat/{{ $contrat->id }}" class="list-group-item list-group-item-action">{{ $contrat->du }}</a>
                            @endforeach
                        </div>
                    </div>
                    </div>
                </div>

                {{-- En Retard --}}
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="card " style="height: 100%" >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <h3 class="card-title mt-1">En Retard</h3>
                                </div>
                                <div class="col-2">
                                    <span class="badge badge-danger px-2"><h6 class="mt-2">{{ sizeof($contrats_en_retard) }}</h6></span>
                                </div>
                            </div>
                            <div class="list-group mt-3 overflow-auto" style="height: 20vh">
                                @foreach ($contrats_en_retard as $contrat)
                                    <a href="/contrat/{{ $contrat->id }}" class="list-group-item list-group-item-action">
                                        {{ $contrat->du->format('d-M-Y') }} |
                                        <span >
                                            {{ $contrat->contractable->immatriculation }}
                                        </span>

                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


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
