@extends('layouts.app')


@section('content')

    <parametres-index inline-template :documents_prop="{{ $documents }}" :accessoires_prop="{{ $accessoires }}"
        :techniciens_prop="{{ $techniciens }}" :contractables_prop="{{ $contractables }}">
        <div class="mx-auto max-w-7xl pt-16 lg:flex lg:gap-x-16 lg:px-8">
            <main class="px-4 py-16 sm:px-6 lg:flex-auto lg:px-0 lg:py-20">
                <div class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none">
                    {{-- Documents --}}
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Documents Véhicules</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Liste de Documents Administratifs du véhicule</p>

                        <dl class="mt-6 space-y-6 divide-y divide-gray-100 border-t border-gray-200 text-sm leading-6">
                            <div class="pt-6 sm:flex" v-for="document in documents">
                                <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">@{{ document.type }}
                                </dt>
                                <dd class="mt-1 flex justify-between gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-gray-900">Tom Cook</div>
                                    <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                                </dd>
                            </div>

                        </dl>
                        <div class="flex border-t border-gray-100 pt-6">
                            <button type="button"
                                class="text-sm font-semibold leading-6 text-indigo-600 hover:text-indigo-500"><span
                                    aria-hidden="true">+</span> Ajouter un Type de Document</button>
                        </div>
                    </div>
                    {{-- Accessoires --}}
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Accessoires Véhicules</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Liste des Accessoires du Véhicule</p>

                        <dl class="mt-6 space-y-6 divide-y divide-gray-100 border-t border-gray-200 text-sm leading-6">
                            <div class="pt-6 sm:flex" v-for="accessoire in accessoires">
                                <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">@{{ accessoire.type }}
                                </dt>
                                <dd class="mt-1 flex justify-between gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-gray-900">Tom Cook</div>
                                    <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                                </dd>
                            </div>

                        </dl>
                    </div>
                    {{-- Techniciens --}}
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Techniciens & Fournisseurs</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Liste des Techniciens et des Fournisseurs</p>

                        <dl class="mt-6 space-y-6 divide-y divide-gray-100 border-t border-gray-200 text-sm leading-6">
                            <div class="pt-6 sm:flex" v-for="technicien in techniciens">
                                <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">@{{ technicien.type }}
                                </dt>
                                <dd class="mt-1 flex justify-between gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-gray-900">Tom Cook</div>
                                    <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                                </dd>
                            </div>

                        </dl>
                    </div>


                </div>
            </main>
        </div>
    </parametres-index>






    <div class="container-fluid">
        <h1 class="mt-5 text-4xl font-semibold">Paramètres</h1>
        <div class="row mt-10">
            {{-- Sidebar --}}
            <div class="col-2">
                <h3 class="text-2xl" style="visibility: hidden">Documents</h3>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                    data-target="#ajouterTypeDocument">
                    Ajouter Type Document
                </button>
                <!-- Modal -->
                <div class="modal fade" id="ajouterTypeDocument" tabindex="-1" role="dialog"
                    aria-labelledby="modelTitleId" aria-hidden="true">
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
                                        <input type="text" class="form-control" name="type"
                                            placeholder="Visite Technique">
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

                <button type="button" class="mt-3 btn btn-primary btn-block" data-toggle="modal"
                    data-target="#ajouterTypeAccessoire">
                    Ajouter Accessoire
                </button>
                <!-- Modal -->
                <div class="modal fade" id="ajouterTypeAccessoire" tabindex="-1" role="dialog"
                    aria-labelledby="modelTitleId" aria-hidden="true">
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
                                        <input type="text" class="form-control" name="type"
                                            placeholder="Manivelle">
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

                    <div class="text-center col-4">
                        <h3 class="text-2xl text-left">Mes Documents</h3>
                        <div class="list-group">
                            @isset($documents)
                                @forelse ($documents as $document)
                                    <div class="list-group-item list-group-item-action">{{ $document->type }}
                                        <a href="#" class="float-right ml-2">
                                            <i class="fas fa-times text-danger" data-toggle="modal"
                                                data-target="#{{ str_replace(' ', '', $document->type) }}"></i>
                                        </a>
                                        <a href="#" class="float-right mx-2">
                                            <i class="fas fa-edit text-primary" data-toggle="modal"
                                                data-target="#edit{{ str_replace(' ', '', $document->type) }}"></i>
                                        </a>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="{{ str_replace(' ', '', $document->type) }}" tabindex="-1"
                                        role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Attention</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="/documents/{{ $document->id }}/destroy" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        Voulez-vous vraiment supprimer "{{ $document->type }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Supprimer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="edit{{ str_replace(' ', '', $document->type) }}"
                                        tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modifier Document</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="/documents/{{ $document->id }}/update" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="">Type</label>
                                                            <input type="text" class="form-control" name="type"
                                                                placeholder="Manivelle" value="{{ $document->type }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Ajouter</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>Aucun Document Disponible. Veuillez en creer</p>
                                @endforelse
                            @endisset


                        </div>
                    </div>
                    <div class="text-center col-4">
                        <h3>Mes Accessoires</h3>
                        <div class="list-group">
                            @isset($accessoires)
                                @forelse ($accessoires as $accessoire)
                                    <div class="list-group-item list-group-item-action">{{ $accessoire->type }}
                                        <a href="#" class="float-right ml-2">
                                            <i class="fas fa-times text-danger" data-toggle="modal"
                                                data-target="#{{ str_replace(' ', '', $accessoire->type) }}"></i>
                                        </a>
                                        <a href="#" class="float-right mx-2">
                                            <i class="fas fa-edit text-primary" data-toggle="modal"
                                                data-target="#edit{{ str_replace(' ', '', $accessoire->type) }}"></i>
                                        </a>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="{{ str_replace(' ', '', $accessoire->type) }}"
                                        tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Attention</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="/accessoires/{{ $accessoire->id }}/destroy" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        Voulez-vous vraiment supprimer "{{ $accessoire->type }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Supprimer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="edit{{ str_replace(' ', '', $accessoire->type) }}"
                                        tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modifier Accessoires</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="/accessoires/{{ $accessoire->id }}/update" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="">Type</label>
                                                            <input type="text" class="form-control" name="type"
                                                                placeholder="Manivelle" value="{{ $accessoire->type }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Modifier</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>Veuillez Creer des Accessoires</p>
                                @endforelse
                            @endisset


                        </div>
                    </div>
                    <div class="col-4">
                        <h3 class="text-center">Mes Voitures / Mes Chambres</h3>
                        <div class="list-group">
                            @foreach ($contractables as $contractable)
                                <a class="list-group-item list-group-item-action" data-toggle="modal"
                                    data-target="#voiture{{ $contractable->id }}"
                                    href="#">{{ $contractable->nom() }} </a>

                                <!-- Modal -->
                                <div class="modal fade" id="voiture{{ $contractable->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Attacher Accessoire & Documents</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="/{{ $contractable->id }}/voiture-documents-accessoires"
                                                method="POST">
                                                @csrf
                                                <div class="modal-body row">
                                                    <div class="col-6">
                                                        @isset($documents)
                                                            @forelse ($documents as $document)
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input"
                                                                            name="{{ str_replace(' ', '', $document->type) }}"
                                                                            @isset($contractable->documents)
                                                                @foreach ($contractable->documents as $voit_document)
                                                                @if ($voit_document->type === $document->type)
                                                                checked
                                                                @endif
                                                                @endforeach
                                                                @endisset
                                                                            value="{{ $document->id }}">
                                                                        {{ $document->type }}
                                                                    </label>
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <input type="date" class="form-control"
                                                                        name="date{{ str_replace(' ', '', $document->type) }}"
                                                                        @isset($contractable->documents)
                                                            @foreach ($contractable->documents as $voit_doc)
                                                            @if ($voit_doc->type === $document->type)
                                                            value="{{ $voit_doc->pivot->date_expiration }}"
                                                            @endif
                                                            @endforeach
                                                            @endisset>
                                                                </div>
                                                            @empty
                                                                <div>
                                                                    Aucun Document
                                                                </div>
                                                            @endforelse
                                                        @endisset

                                                    </div>
                                                    <div class="col-6">
                                                        @isset($accessoires)
                                                            @foreach ($accessoires as $accessoire)
                                                                <div class="form-check">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input"
                                                                            name="{{ str_replace(' ', '', $accessoire->type) }}"
                                                                            value="{{ $accessoire->id }}"
                                                                            @isset($contractable->accessoires)
                                                                        @foreach ($contractable->accessoires as $voit_access)
                                                                        @if ($voit_access->type === $accessoire->type)
                                                                        checked
                                                                        @endif
                                                                        @endforeach
                                                                        @endisset>
                                                                        {{ $accessoire->type }}
                                                                    </label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control" min=0 max=3
                                                                        name="quantité{{ str_replace(' ', '', $accessoire->type) }}"
                                                                        @isset($contractable->accessoires)
                                                                            @foreach ($contractable->accessoires as $voit_access)
                                                                                @if ($voit_access->type === $accessoire->type)
                                                                                value="{{ $voit_access->pivot->quantité }}"
                                                                                @endif
                                                                            @endforeach
                                                                        @endisset>
                                                                </div>
                                                            @endforeach
                                                        @endisset
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Fermer</button>
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

        <div class="row">
            <div class="col-4">
                <h3>Mes Techniciens
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ajouteTechnicien">
                        <i class="fas fa-plus-square"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="ajouteTechnicien" tabindex="-1" role="dialog"
                        aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/techniciens" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="">Nom</label>
                                            <input type="text" class="form-control" name="nom">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </h3>
                <ul class="list-group">
                    @foreach ($techniciens as $technicien)
                        <li class="list-group-item">{{ $technicien->nom }}</li>
                    @endforeach

                </ul>
            </div>
            <livewire:api-setting-component>
        </div>


    </div>









@endsection
