@extends('layouts.app')


@section('content')
<div class="container-fluid">
    <cree-contrats :prop_clients="{{ $clients }}" :prop_voitures="{{ $voitures }}">
        
    </cree-contrats>
    
</div>
@endsection