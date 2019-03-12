@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-6">
            <img class="img-fluid w-100" src="/uploads/{{ $contrat->lien_photo_avant }}"/>
        </div>
        <div class="col-6">
            <img class="img-fluid w-100" src="/uploads/{{ $contrat->lien_photo_droit }}"/>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col">
            <img class="img-fluid w-100" src="/uploads/{{ $contrat->lien_photo_gauche }}"/>
        </div>
        <div class="col">
            <img class="img-fluid w-100" src="/uploads/{{ $contrat->lien_photo_arriere }}"/>
        </div>
    </div>
    
@endsection