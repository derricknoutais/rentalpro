@extends('layouts.app')


@section('content')
    <contrats-index inline-template>
        <div>
            <div class="container">
                <h1 class="text-left my-5">Contrat</h1>
            </div>
            <div class="container-fluid bg-white py-5">
                <div class="container">
                    <div class="row px-5">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Cherche Contrat par Nº Contrat</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Nom, Prénom, Nº Téléphone Client</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row px-5">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Date du</label>
                                <input type="date" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Date au</label>
                                <input type="date" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">État</label>
                                <select class="form-control" name="" id="">
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-2 offset-1">
                            <div class="form-group">
                                <label for="" style="visibility: hidden">Type</label>
                                <button type="button" class="btn btn-primary btn-block">Chercher</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="container">
                <table class="table table-hover mt-5">
                    <thead>
                        <tr>
                            <th>Contrat Nº</th>
                            <th>Client</th>
                            <th>Voiture</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contrats as $contrat)
                            <tr class="pointer">
                                <td scope="row">
                                    <div class="row">
                                        <a href="/contrat/{{ $contrat->id }}">
                                            {{ $contrat->numéro }}
                                        </a>
                                    </div>
                                    @if (! $contrat->cashier_facture_id)
                                        <div class="row">
                                            <button class="btn btn-primary btn-sm px-1 py-0" role="button" @click="envoieACashier( {{ $contrat }} )">Envoyer à Cashier</button>
                                        </div>
                                    @else
                                        <div class="row">
                                            <a class="btn btn-success btn-sm px-1 py-0" role="button" href="https://thecashier.ga/STA/Facture/{{ $contrat->cashier_facture_id }}">Voir dans Cashier</a>
                                        </div>
                                    @endif

                                </td>
                                <td>{{ $contrat->client['nom'] . ' ' . $contrat->client['prenom']}}</td>
                                <td>{{ $contrat->voiture->immatriculation }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </contrats-index>
    
    
@endsection