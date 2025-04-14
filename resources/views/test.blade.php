@extends('layouts.app')
@section('content')
    <form enctype="multipart/form-data" method="post" action="{{ url('upload') }}">
        {{ csrf_field() }}

        <div class="row">
            <div class="col">
                <input type="hidden" name="contrat_id" value="{{ $contrat->id }}">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Coté Droit</label>
                <input type="file" ref="droit" name="cote_droit" accept="image/*" capture="camera">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Coté Gauche</label>
                <input type="file" ref="gauche" name="cote_gauche" accept="image/*" capture="camera">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Arrière Voiture</label>
                <input type="file" ref="arriere" name="arriere" accept="image/*" capture="camera">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Avant Voiture</label>
                <input type="file" ref="avant" name="avant" accept="image/*" capture="camera" />
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
