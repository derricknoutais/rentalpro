@extends('layouts.app')


@section('content')
    <clients-index inline-template>
        <div>

            <h1 class="text-center tw-text-3xl my-5">Clients</h1>

            {{-- Tableau index sur les clients --}}

            <div class="container">
                <div class="row mt-5">
                    <div class="col">

                        {{-- Tableau des clients --}}
                        <table class="table table-hover">
                            <thead>
                                <tr class="tw-bg-yellow-300">
                                    <th>Nom</th>
                                    <th>Numéro de Phone</th>
                                    <th>Nombre Locations</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr class="pointer" @click="relocateTo('/clients/{{ $client->id }}')">
                                        <td scope="row">{{ $client->nom . ' ' . $client->prenom }}</td>
                                        <td>{{ $client->phone1 }}</td>
                                        <td>{{ $client->nombreLocations() }}</td>
                                        <td>
                                            <a href="/clients/{{ $client->id }}/edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            {{-- Modal Ajouter Client --}}
            <div class="modal fade" id="ajoutClient" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" @keyup.enter="enregistreClientDansCashier()">

                <div class="modal-dialog modal-lg" role="document">

                    {{-- Contenu Modal --}}

                    <div class="modal-content">
                        {{-- En-tete Modal --}}
                        <div class="modal-header">
                            <h5 class="modal-title">Nouveau Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        {{-- Formulaire Ajout Client --}}

                        <form action="/clients/ajout-client" method="POST"  enctype="multipart/form-data" id="clientForm" @submit.prevent="enregistreClientDansCashier()">

                            <div class="modal-body">
                                <div class="container-fluid">

                                        @csrf
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                <label for="">Nom <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="nom" v-model="client.nom">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                <label for="">Prénom <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="prenom" v-model="client.prenom">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                <label for="">Numéro Télephone <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="numero_telephone" v-model="client.numero_telephone">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                <label for="">Numéro Télephone 2</label>
                                                <input type="text" class="form-control" name="numero_telephone2" v-model="client.numero_telephone2">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                <label for="">Numéro Télephone 3</label>
                                                <input type="text" class="form-control" name="numero_telephone3" v-model="client.numero_telephone3">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                <label for="">Numéro Permis</label>
                                                <input type="text" class="form-control" name="numero_permis" v-model="client.numero_permis">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                <label for="">E-mail</label>
                                                <input type="text" class="form-control" name="mail" v-model="client.mail">
                                                <input type="hidden" class="form-control" id="cashier_id" name="cashier_id" v-model="client.cashier_id">
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
                                <button type="submit" class="btn btn-primary">
                                    <i v-if="isLoading" class="fas fa-spinner fa-spin"></i>
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </clients-index>
@endsection
