@extends('layouts.app')


@section('content')
    <div class="container">
        <contrat-show inline-template>
            <contrat-final :contrat="{{ $contrat }}">

            </contrat-final>
        </contrat-show>
        
    </div>
@endsection