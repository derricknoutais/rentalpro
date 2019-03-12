@extends('layouts.app')


@section('content')
    <contrats-index inline-template style="height:100vh; margin-top:-5vh">
        <div class="container d-flex align-items-center justify-content-center" >
            <div class="row">
                <div @click="relocateTo('/contrats/create')" class="col border py-5 px-5 rounded text-center d-flex align-items-center justify-content-center mx-5 pointer bg-primary text-white">
                    Créer Nouveau Contrat
                </div>
                <div @click="relocateTo('/contrats')" class="col border py-5 px-5 rounded text-center d-flex align-items-center justify-content-center mx-5 pointer bg-primary text-white">
                    Voir Contrat
                </div>
            </div>
        </div>
    </contrats-index>
@endsection