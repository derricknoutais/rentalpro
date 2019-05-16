@extends('layouts.app')


@section('content')
    <contrats-index inline-template>
        <div>
            <div class="container">
                <h1 class="text-left my-5">Contrat</h1>
            </div>
            <div class="container-fluid bg-white py-5">
                <div class="container">
                    <div class="row px-5">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Cherche Contrat par Nº Contrat</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Nom, Prénom, Nº Téléphone Client</label>
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row px-5">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Date du</label>
                                <input type="date" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Date au</label>
                                <input type="date" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
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

                        <div class="col-2 offset-1">
                            <div class="form-group">
                                <label for="" style="visibility: hidden">Type</label>
                                <button type="button" class="btn btn-primary btn-block">Chercher</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="container">
                <table class="table table-hover mt-5">
                    <thead>
                        <tr>
                            <th>Contrat Nº</th>
                            <th>Client</th>
                            <th>Voiture</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contrats as $contrat)
                            <tr>
                                <td scope="row">
                                    <div class="row">
                                        <a href="/contrat/{{ $contrat->id }}">
                                            {{ $contrat->numéro }}
                                        </a>
                                    </div>
                                    <div class="row">
                                        @if (! $contrat->cashier_facture_id)
                                                <button class="btn btn-primary btn-sm px-1 py-0 mr-2" role="button" @click="envoieACashier( {{ $contrat }} )">Envoyer à Cashier</button>
                                        @else
                                                <a class="btn btn-success btn-sm px-1 py-0 mr-2" role="button" href="https://thecashier.ga/STA/Facture/{{ $contrat->cashier_facture_id }}">Voir dans Cashier</a>
                                        @endif
                                        
                                        <!-- Si le contrat n'est plus en cours -->
                                        @if ( $contrat->real_check_in )
                                            <!-- Si le contrat a été prolongé -->
                                            @if ($contrat->prolongation_id)
                                                <span class="mx-1 badge badge-pill badge-success">Prolongé</span>
                                                <a class="btn btn-secondary btn-sm px-1 py-0 mr-2" role="button"
                                                    href="/contrat/{{ $contrat->prolongation_id }}">Voir Contrat Prolongé</a>
                                            <!-- Si le contrat n'a jamais été prolongé -->
                                            @else
                                                <span class="mx-1 badge badge-pill badge-success">Retourné</span>
                                            @endif
                                        
                                        <!-- Si le contrat est en cours -->
                                        @else

                                            <button type="button" class="btn btn-primary btn-sm px-1 py-0 mr-2 " data-toggle="modal" data-target="#prolongation{{ $contrat->id }}">
                                                <i class="fas fa-clock"></i> Prolonger Contrat
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm px-1 py-0" data-toggle="modal" data-target="#changervoiture{{ $contrat->id }}">
                                                <i class="fas fa-exchange-alt mr-1"></i> Changer Voiture 
                                            </button>
                                            <span class="badge badge-pill badge-warning mr-2">En Location</span>
                                        @endif

                                        <div class="modal fade" id="changervoiture{{ $contrat->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Changer Véhicule</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                    </div>
                                                    <form action="/contrats/{{ $contrat->id }}/changer-voiture" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="">Sélectionner Voiture</label>
                                                                <select class="custom-select" name="voiture">
                                                                    <option value="{{ $contrat->voiture->id }}" selected>{{ $contrat->voiture->immatriculation }}</option>
                                                                    @foreach ($voitures as $voiture)
                                                                        @if($voiture->etat === 'disponible')
                                                                            <option value="{{ $voiture->id }}">{{ $voiture->immatriculation }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" >Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="prolongation{{ $contrat->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Prolonger Contrat</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                    </div>
                                                    <form action="/contrats/{{ $contrat->id }}/prolonger" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="">Nouvelle Date Prolongation</label>
                                                                <input type="date" class="form-control" name="check_in">
                                                            {{-- </div>
                                                            <div class="form-group">
                                                                <label for="">Sélectionner Voiture</label>
                                                                <select class="custom-select" name="voiture">
                                                                    <option value="{{ $contrat->voiture->id }}" selected>{{ $contrat->voiture->immatriculation }}</option>
                                                                    @foreach ($voitures as $voiture)
                                                                        @if($voiture->etat === 'disponible')
                                                                            <option value="{{ $voiture->id }}">{{ $voiture->immatriculation }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div> --}}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" >Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        

                                    </div>
                                </td>
                                <td>{{ $contrat->client['nom'] . ' ' . $contrat->client['prenom']}}</td>
                                <td>{{ $contrat->voiture->immatriculation }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="row">
                    <div class="col d-flex justify-content-center">
                        {{ $contrats->links() }}
                    </div>
                </div>
                
            </div>
            <!-- Modal Prolongation -->
            
        </div>
    </contrats-index>
    
    
@endsection