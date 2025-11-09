@extends('layouts.app')

@section('content')
    <reservation-calendar
        :clients='@json($clients)'
        :contractables='@json($contractables)'
        :statuses='@json($statusOptions)'
        :compagnie='@json($compagnie)'>
    </reservation-calendar>
@endsection
