@extends('layouts.app')


@section('content')
    <div class="container" >
        <contrat-show inline-template environment="{{ app()->environment() }}">
            <contrat-final :contrat="{{ $contrat }}" @cashier="envoieACashier" environment="{{ app()->environment() }}">
                
            </contrat-final>
        </contrat-show>
        
    </div>
@endsection