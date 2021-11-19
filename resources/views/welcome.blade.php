
@extends('layouts.app')

@section('content')
@if(Auth::user()->compagnie->type === 'véhicules')
    <Welcome inline-template :contrats="{{ $contrats }}" :clients_prop="{{ $clients }}">
        @include('welcome_véhicules')
    </Welcome>
@else
    <Welcome inline-template :contrats="{{ $contrats }}" :chambres_prop="{{ $chambres }}" :clients_prop="{{ $clients }}">

        @include('welcome_hotel')
    </Welcome>

@endif

@endsection
