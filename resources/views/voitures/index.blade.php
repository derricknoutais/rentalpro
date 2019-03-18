@extends('layouts.app')


@section('content')
    <voitures-index inline-template>
        <div>
            <div class="container">
                <h1 class="text-left my-5">Voitures</h1>
            </div>
            <div class="container-fluid bg-primary py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-10">
                            <p class="text-white">Ajoute, Visualise et Modifie toutes les voitures.</p>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-light" data-toggle="modal" data-target="#ajoutVoiture">Ajouter Voiture</button>

                        </div>
                    </div>
                </div>

            </div>
            <div class="container-fluid bg-white py-5">
                <div class="container">
                    <div class="row px-5">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Cherche Voitures par Immatriculation</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Marque</label>
                                <select class="form-control" name="" id="">
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row px-5">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Type</label>
                                <select class="form-control" name="" id="">
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">État</label>
                                <select class="form-control" name="" id="">
                                    <option></option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Prix</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        
                        <div class="col-2 offset-1">
                            <div class="form-group">
                                <label for="" style="visibility: hidden">Type</label>
                               <button type="button" class="btn btn-primary btn-block">Chercher</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            <div class="container mt-5" >
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Immatriculation</th>
                            <th>Type</th>
                            <th>État</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($voitures as $voiture)
                            <tr @click="relocateTo({{ $voiture->id }})">
                                <td scope="row"><a href="/voiture/{{ $voiture->id }}">{{ $voiture->immatriculation }}</a></td>
                                <td>{{ $voiture->marque . ' ' . $voiture->type }}</td>
                                <td>
                                    @if($voiture->etat === 'loué')
                                        <span class="dot bg-warning"></span>
                                    @elseif($voiture->etat === 'disponible')
                                        <span class="dot bg-success"></span>
                                    @elseif($voiture->etat === 'maintenance')
                                        <span class="dot bg-danger"></span>    
                                    @endif
                                    {{ $voiture->etat }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>


            <!-- Modal -->
            <div class="modal fade" id="ajoutVoiture" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Nouvelle Voiture</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">

                                    <div class="col">
                                        <div class="form-group">
                                          <label for="">Immatriculation</label>
                                          <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                          <label for="">Nº Chassis</label>
                                          <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                          <label for="">Marque</label>
                                          <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                          <label for="">Type</label>
                                          <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                          <label for="">Année</label>
                                          <input type="number" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </voitures-index>
@endsection