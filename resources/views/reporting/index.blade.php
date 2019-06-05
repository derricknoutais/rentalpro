@extends('layouts.app')


@section('content')
    <reporting-general inline-template :contrats="{{ $contrats }}">
        <div>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1 class="text-center mt-5">Reporting General</h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#mensuel" @click="selectMonthlyContracts()" >Reproting Mensuel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#hebdomadaire" @click="selectWeeklyContracts()">Reporting Hebdomadaire</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="#annuel" data-toggle="tab" @click="selectYearlyContracts()">Reporting Annuel</a>
                            </li>
                        </ul>
                        {{-- <div class="row">
                            <div class="col-12">
                                <GChart type="ColumnChart" :data="reporting_annuel.nombre_locations" :options="reporting_annuel.nombre_locations_options"
                                v-if="reporting_annuel.nombre_locations" />
                            </div>
                            
                        </div> --}}
                            <div class="row tab-pane fade" id="annuel" role="tabpanel" v-if="reporting_annuel.show">
                                <div class="col-12">
                                    <GChart type="ColumnChart" :data="reporting_annuel.nombre_locations" :options="reporting_annuel.nombre_locations_options"
                                    v-if="reporting_annuel.nombre_locations" />
                                </div>
                                <div class="col-12">
                                    <GChart type="ColumnChart" :data="reporting_annuel.revenus" :options="reporting_annuel.revenus_options" v-if="reporting_annuel.revenus" />
                                </div>
                            </div>
                            <div class="row tab-pane fade show active" id="mensuel" role="tabpanel">
                                <div class="col-12">
                                    Mensuel
                                </div>
                            </div>
                            <div class="row tab-pane fade" id="hebdomadaire" role="tabpanel" >
                                <div class="col-12">
                                    Mensuel
                                </div>
                            </div>
                    </div>

                </div>
            </div>
        </div>
    </reporting-general>
    
@endsection