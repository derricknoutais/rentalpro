@extends('layouts.app')


@section('content')
<reporting-index inline-template :chiffre="{{ json_encode($chiffre_DAffaire_Annuel, TRUE) }}" :voitures="{{ json_encode($voitures) }}">
    <div>
        {{-- En-tête --}}
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="text-center mt-5">Reporting</h1>
                </div>
            </div>
        </div>
        <span v-if="dates">
            @{{ dates.length }}
        </span>
        
        {{-- Selection véhicule Reporting --}}

        <div class="container-fluid">

            <div class="row">
                <div class="offset-3 col-6">
                    <multiselect v-model="voiture_selectionée" :options="{{ $voitures }}" 
                        placeholder="Select one" label="immatriculation">
                    </multiselect>  
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="row mt-5" v-if="voiture_selectionée">
                <div class="col-4">

                    <div class="card text-white bg-purple-gradient">
                        <div class="card-body">
                            <h4 class="card-title text-center">Reporting Location</h4>
                            <div class="row mt-5">
                                <p class="d-inline-block display-6 col-6" >
                                    <i class="fas fa-car-alt    "></i>
                                    @{{ voiture_selectionée.contrats.length }} Locations
                                </p>
                                <p class=" d-inline-block display-6 col-6"> 
                                    <i class="fas fa-clock    "></i>
                                    @{{ joursDeLocation() }} Jours de Location
                                </p>
                            </div>
                            <div class="row">
                                <p class="d-inline-block display-6 col-6">
                                    <i class="fas fa-money-bill    "></i>
                                    @{{ chiffreDAffaires() | currency }}
                                </p>
                                <p class="d-inline-block display-6 col-6">
                                    <i class="fas fa-money-bill    "></i>
                                    @{{ prixMoyenDeLocation() | currency }} / Location
                                </p>
                            </div>
                            <div class="row">
                                <p class="d-inline-block display-6 col-6">
                                    ???
                                </p>
                                <p class="d-inline-block display-6 col-6">
                                    ???
                                </p>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="col-4">

                    <div class="card text-white bg-dark-gradient" >
                        <div class="card-body">
                            <h4 class="card-title text-center">Reporting Maintenance & Entretien</h4>
                            <div class="row mt-5">
                                <p class="d-inline-block display-6 col-6">
                                    <i class="fas fa-tools    "></i>
                                    @{{ voiture_selectionée.maintenances.length }} Maintenances:</p>
                                </p>
                                <p class=" d-inline-block display-6 col-6">
                                    <i class="fas fa-clock    "></i>
                                    @{{ joursDeLocation() }} Jours de Location
                                </p>
                            </div>
                            <div class="row">
                                <p class="d-inline-block display-6 col-6">
                                    ???
                                </p>
                                <p class="d-inline-block display-6 col-6">
                                    ???
                                </p>
                            </div>
                            <div class="row">
                                <p class="d-inline-block display-6 col-6">
                                    ???
                                </p>
                                <p class="d-inline-block display-6 col-6">
                                    ???
                                </p>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-white bg-light-blue-gradient">
                        <div class="card-body">
                            <h4 class="card-title text-center">Analyse Financière</h4>
                            <div class="row mt-5">
                                <p class="d-inline-block display-6 col-9">
                                    <i class="fas fa-tools    "></i>
                                    Seuil de Rentabilité: @{{ seuilDeRentabilité() | currency }}
                                </p>
                                <p class=" d-inline-block display-6 col-9">
                                    <i class="fas fa-money-bill    "></i>
                                    Marge sur Coût Variable: @{{ marge() | currency }}
                                </p>
                            </div>
                            <div class="row">
                                
                                <p class="d-inline-block display-6 col-12">
                                    <i class="fas fa-money-bill    "></i>
                                    @{{ pointMort() }} Jours de location avant le point mort
                                </p>
                            </div>
                        </div>
                    
                    </div>
                    
                </div>
            </div>
{{-- 
            <div class="row">
                <div class="col">
                    <GChart type="ColumnChart" :data="chartData" :options="chartOptions" />
                </div>
            </div> --}}

        </div>



    </div>
</reporting-index>
    
@endsection