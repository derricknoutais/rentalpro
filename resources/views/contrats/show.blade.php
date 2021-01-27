@extends('layouts.app')

@section('content')
    <div >
        <contrat-show inline-template environment="{{ app()->environment() }}">
            <contrat-final  :contrat="{{ $contrat }}" @cashier="envoieACashier" environment="{{ app()->environment() }}" @paiement="ajouterPaiement">

            </contrat-final>
        </contrat-show>

    </div>
@endsection
