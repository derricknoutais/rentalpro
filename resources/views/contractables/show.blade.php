@extends('layouts.app')

@section('content')
    <contractable-show
        :contractable_prop='@json($contractable)'
        :contrats_prop='@json($contrats)'
        :documents_prop='@json($documents)'
        :accessoires_prop='@json($accessoires)'>
    </contractable-show>
@endsection
