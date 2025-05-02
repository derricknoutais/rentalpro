@extends('layouts.app')

@section('content')
    <div class="border border-1">

        <div class="flex flex-col px-6 py-12">
            <p>Signez ici</p>
            <div class="w-full" style="width: 300px;">
                <my-signature-pad contrat_id="{{ $contrat->id }}"></my-signature-pad>
            </div>
        </div>

    </div>
@endsection
