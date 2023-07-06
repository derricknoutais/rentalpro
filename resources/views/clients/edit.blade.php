@extends('layouts.app')


@section('content')
<client-edit inline-template :prop_client="{{ $client }}">

    <form action="/clients/{{ $client->id }}/update" method="POST"
        enctype="multipart/form-data" id="clientUpdateForm"
        {{-- @submit.prevent="updateClient()"  --}}
    >
        <div class="container-fluid">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" v-model="client.nom">
                            <input type="hidden" class="form-control" name="image_id" v-model="client.image_id">
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
                        @if ($client->image)
                            <img src="https://rentalpro.fra1.digitaloceanspaces.com/permis/{{ $client->image->name }}" class="w-1/3">
                            <div class="w-full">

                                <button type="button" class="btn btn-danger block w-1/3" @click="deleteImage">Supprimer Image</button>
                            </div>
                        @else
                            <permis-pond @file-processed="attributeImage"></permis-pond>
                        @endif
                    </div>
                    <div class="col">
                        
                        
                    </div>
                </div>

        </div>
        <div class="container">
            <div class="row mt-5">
                <div class="col-12">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </div>

        </div>
    </form>
</client-edit>

@endsection
