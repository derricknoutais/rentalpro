@extends('layouts.app')


@section('content')
    <clients-index inline-template>
        <div>
            <div class="container">
                <h1 class="text-left my-5">Clients</h1>
            </div>
            <div class="container-fluid bg-primary py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-10">
                            <p class="text-white">Ajoute, Visualise et Modifie tous tes clients.</p>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-light" data-toggle="modal" data-target="#ajoutClient">Ajouter Client</button>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="container-fluid bg-white py-5">
                <div class="container">
                    <div class="row px-5">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Cherche Clients par Nom, Prénom</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Nº Téléphone</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row px-5">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Nº Permis</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Ville</label>
                                <select class="form-control" name="" id="">
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">E-mail</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
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
            
            <!-- Modal -->
            <div class="modal fade" id="ajoutClient" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Nouveau Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/clients/ajout-client" method="POST" enctype="multipart/form-data" id="clientForm" @submit.prevent="enregistreClientDansCashier()">
                        <div class="modal-body">
                            <div class="container-fluid">
                                
                                    @csrf
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                              <label for="">Nom Complet</label>
                                              <input type="text" class="form-control" name="nom" v-model="client.nom">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                              <label for="">Nom Complet</label>
                                              <input type="text" class="form-control" name="prenom" v-model="client.prenom">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                              <label for="">Numéro Télephone</label>
                                              <input type="text" class="form-control" name="numero_telephone" v-model="client.numero_telephone">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                              <label for="">Numéro Permis</label>
                                              <input type="text" class="form-control" name="numero_permis" v-model="client.numero_permis">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                              <label for="">E-mail</label>
                                              <input type="text" class="form-control" name="mail" v-model="client.mail">
                                              <input type="hidden" class="form-control" name="cashier_id" v-model="client.cashier_id">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="">Ville</label>
                                                <select class="form-control" name="ville" v-model="client.ville">
                                                    <option>Libreville</option>
                                                    <option>Port-Gentil</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                              <label for="">Addresse</label>
                                              <textarea class="form-control" name="addresse" v-model="client.addresse"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                              <label for="">Joindre Permis</label>
                                              <input type="file" class="form-control-file" name="permis" id="" placeholder="" aria-describedby="fileHelpId">
                                            </div>
                                        </div>
                                    </div>
                                
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </clients-index>
@endsection