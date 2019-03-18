{{-- <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
              <div class="card" style="width: 18rem;">
                <div class="card-body">
                  <h5 class="card-title">Card title</h5>
                  <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  <a href="#" class="card-link">Card link</a>
                  <a href="#" class="card-link">Another link</a>
                </div>
              </div>
            </div>
        </div>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Rental Pro
                </div>

                <div class="links">
                    <a href="/clients">Clients</a>
                    <a href="/voitures">Voitures</a>
                    <a href="/contrats/menu">Contrats</a>
                    <a href="https://blog.laravel.com">Accessoires</a>
                    <a href="https://nova.laravel.com">Documents</a>
                </div>
            </div>
        </div>
    </body>
</html> --}}
@extends('layouts.app')

@section('content')
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
                            <span class="badge badge-danger px-3"><h6 class="mt-2">{{ $voitures[0]->compteVoitures('disponible') }}</h6></span>
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

            <div class="col-4">
                <div class="card" >
                <div class="card-body" style="height:30vh">
                    <div class="row">
                        <div class="col-10">
                            <h3 class="card-title mt-1">En Location </h3>
                        </div>
                        <div class="col-2 ">
                            <span class="badge badge-warning px-3"><h6 class="mt-2">{{ $voitures[0]->compteVoitures('disponible') }}</h6></span>
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
@endsection