@extends('layouts.app')


@section('content')

<div class="container flex items-center justify-center min-h-full mt-10">
    @livewire('maintenance-edit', compact('voitures', 'techniciens', 'maintenance'))
</div>

@endsection
