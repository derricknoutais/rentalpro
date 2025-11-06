@extends('layouts.app')


@section('content')
    <contractable-show
        :contractable_prop='@json($voiture)'
        :contrats_prop='@json($contrats)'
        :documents_prop='@json($documents)'
        :accessoires_prop='@json($accessoires)'>
    </contractable-show>
@endsection
