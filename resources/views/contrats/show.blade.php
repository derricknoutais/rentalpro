@extends('layouts.app')


@section('content')
    <div class="container">
        <contrat-show inline-template>
            <contrat-final :contrat="{{ $contrat }}" @cashier="envoieACashier">

            </contrat-final>
        </contrat-show>
        
    </div>
@endsection