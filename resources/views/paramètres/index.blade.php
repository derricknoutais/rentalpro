@extends('layouts.app')


@section('content')
    <div class="container-fluid">
        <h1 class="text-center mt-5">Paramètres</h1>
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-2">
                <h3 class="" style="visibility: hidden">Mes Documents</h3>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#ajouterTypeDocument">
                    Ajouter Type Document
                </button>
                <!-- Modal -->
                <div class="modal fade" id="ajouterTypeDocument" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajouter Type Document</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="/documents" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="">Type</label>
                                        <input type="text" class="form-control" name="type" placeholder="Visite Technique">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-block mt-3" data-toggle="modal" data-target="#ajouterTypeAccessoire">
                    Ajouter Accessoire
                </button>
                <!-- Modal -->
                <div class="modal fade" id="ajouterTypeAccessoire" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajouter Accessoire</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="/accessoires" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="">Type</label>
                                        <input type="text" class="form-control" name="type" placeholder="Manivelle">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-10">
                <div class="row">
                    <div class="col-4 text-center">
                        <h3>Mes Documents</h3>
                        <div class="list-group">
                            @foreach ($documents as $document)
                                <div class="list-group-item list-group-item-action">{{ $document->type }}
                                    <a href="#" class="float-right ml-2">
                                        <i class="fas fa-times text-danger" data-toggle="modal" data-target="#{{ str_replace(' ', '', $document->type) }}"></i>
                                    </a>
                                    <a href="#" class="float-right mx-2">
                                        <i class="fas fa-edit text-primary" data-toggle="modal" data-target="#edit{{ str_replace(' ', '', $document->type) }}"></i>
                                    </a>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="{{ str_replace(' ', '', $document->type) }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Attention</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/documents/{{ $document->id }}/destroy" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    Voulez-vous vraiment supprimer "{{ $document->type }}"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="btn btn-primary">Supprimer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="edit{{ str_replace(' ', '', $document->type) }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Document</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/documents/{{ $document->id }}/update" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="">Type</label>
                                                        <input type="text" class="form-control" name="type" placeholder="Manivelle" value="{{ $document->type }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <h3>Mes Accessoires</h3>
                        <div class="list-group">
                            @foreach ($accessoires as $accessoire)
                                <div class="list-group-item list-group-item-action">{{ $accessoire->type }}
                                    <a href="#" class="float-right ml-2">
                                        <i class="fas fa-times text-danger" data-toggle="modal" data-target="#{{ str_replace(' ', '', $accessoire->type) }}"></i>
                                    </a>
                                    <a href="#" class="float-right mx-2">
                                        <i class="fas fa-edit text-primary" data-toggle="modal" data-target="#edit{{ str_replace(' ', '', $accessoire->type) }}"></i>
                                    </a>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="{{ str_replace(' ', '', $accessoire->type) }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Attention</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/accessoires/{{ $accessoire->id }}/destroy" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    Voulez-vous vraiment supprimer "{{ $accessoire->type }}"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="btn btn-primary">Supprimer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="edit{{ str_replace(' ', '', $accessoire->type) }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Accessoires</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/accessoires/{{ $accessoire->id }}/update" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="">Type</label>
                                                        <input type="text" class="form-control" name="type" placeholder="Manivelle" value="{{ $accessoire->type }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-4">
                        <h3 class="text-center">Mes Voitures</h3>
                        <div class="list-group">
                            @foreach ($voitures as $voiture)
                                <a class="list-group-item list-group-item-action" data-toggle="modal" data-target="#voiture{{ $voiture->id }}" href="#">{{ $voiture->immatriculation }} </a>

                                <!-- Modal -->
                                <div class="modal  fade" id="voiture{{ $voiture->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Attacher Accessoire & Documents</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/{{ $voiture->id }}/voiture-documents-accessoires" method="POST">
                                                @csrf
                                                <div class="modal-body row">
                                                    <div class="col-6">
                                                        @foreach ($documents as $document)
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" 
                                                                    name="{{ str_replace(' ', '',  $document->type) }}" 
                                                                    @foreach ($voiture->documents as $voit_document)
                                                                        @if ($voit_document->type === $document->type)
                                                                            checked
                                                                        @endif
                                                                    @endforeach
                                                                    value="{{ $document->id }}"
                                                                    >
                                                                    {{ $document->type }}
                                                                </label>
                                                            </div>
                                                            <div class="form-group col-6">
                                                                <input type="date" class="form-control" 
                                                                name="date{{ str_replace(' ', '', $document->type) }}"
                                                                    @foreach ($voiture->documents as $voit_doc)
                                                                        @if ($voit_doc->type === $document->type)
                                                                            value="{{ $voit_doc->pivot->date_expiration }}"
                                                                        @endif
                                                                    @endforeach
                                                                >
                                                            </div>
                                                        @endforeach 
                                                    </div>
                                                    <div class="col-6">
                                                        @foreach ($accessoires as $accessoire)
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" 
                                                                    name="{{ str_replace(' ', '', $accessoire->type) }}"
                                                                    value="{{ $accessoire->id }}"
                                                                    @foreach ($voiture->accessoires as $voit_access)
                                                                        @if ($voit_access->type === $accessoire->type)
                                                                            checked
                                                                        @endif
                                                                    @endforeach
                                                                    >
                                                                    {{ $accessoire->type }}
                                                                </label>
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control" min=0 max=3
                                                                    name="quantité{{ str_replace(' ', '', $accessoire->type) }}"
                                                                    @foreach ($voiture->accessoires as $voit_access)
                                                                        @if ($voit_access->type === $accessoire->type)
                                                                            value="{{ $voit_access->pivot->quantité }}"
                                                                        @endif
                                                                    @endforeach
                                                                >
                                                            </div>
                                                        @endforeach 
                                                    </div>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="btn btn-primary">Attacher</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
    

    
    

@endsection