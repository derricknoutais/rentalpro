@extends('layouts.app')


@section('content')
    <parametres-index inline-template :documents_prop="{{ $documents }}" :accessoires_prop="{{ $accessoires }}"
        :techniciens_prop="{{ $techniciens }}" :contractables_prop="{{ $contractables }}">
        <div class="mx-auto max-w-7xl pt-8 lg:gap-x-16 lg:px-8">
            <header class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Paramètres
                </h2>
            </header>
            <main class="mt-16 sm:px-6 lg:flex-auto lg:px-0 ">

                {{-- Documents --}}
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Documents Véhicules</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">Liste de Documents Administratifs du véhicule</p>

                    <dl class="mt-6 space-y-6 divide-y divide-gray-100 border-t border-gray-200 text-sm leading-6 w-1/2">
                        <div class="pt-6 sm:flex flex justify-between" v-for="document in documents">
                            <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">@{{ document.type }}
                            </dt>
                            <dd class="mt-1 flex justify-end gap-x-6 sm:mt-0 sm:flex-auto">

                                <button type="button" data-toggle="modal" data-target="#editerTypeDocument"
                                    class="font-semibold text-blue-600 hover:text-blue-500"
                                    @click="passData(document)">Editer</button>
                                <button type="button" class="font-semibold text-red-600 hover:text-red-500"
                                    @click="deleteResource('/documents/' + document.id, 'Document' )">Supprimer</button>
                            </dd>
                        </div>

                    </dl>
                    <div class="flex border-t border-gray-100 pt-6">
                        <button type="button" data-target="#ajouterTypeDocument" data-toggle="modal"
                            class="text-sm font-semibold leading-6 text-blue-600 hover:text-blue-500"><span
                                aria-hidden="true">+</span> Ajouter un Type de Document</button>
                    </div>
                </div>
                {{-- Accessoires --}}
                <div class="mt-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Accessoires Véhicules</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">Liste des Accessoires du Véhicule</p>

                    <dl class="mt-6 space-y-6 divide-y divide-gray-100 border-t border-gray-200 text-sm leading-6 w-1/2">
                        <div class="pt-6 sm:flex flex justify-between" v-for="accessoire in accessoires">
                            <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">@{{ accessoire.type }}
                            </dt>
                            <dd class="mt-1 flex justify-end gap-x-6 sm:mt-0 sm:flex-auto">

                                <button type="button" class="font-semibold text-blue-600 hover:text-blue-500"
                                    data-toggle="modal" data-target="#editerTypeAccessoire"
                                    @click="passData(accessoire)">Editer</button>
                                <button type="button" class="font-semibold text-red-600 hover:text-red-500"
                                    @click="deleteResource('/accessoires/' + accessoire.id, 'Accessoire' )">Supprimer</button>
                            </dd>
                        </div>

                    </dl>
                    <div class="flex border-t border-gray-100 pt-6">
                        <button type="button" data-target="#ajouterTypeAccessoire" data-toggle="modal"
                            class="text-sm font-semibold leading-6 text-blue-600 hover:text-blue-500"><span
                                aria-hidden="true">+</span> Ajouter un Type d'Accessoires</button>
                    </div>
                </div>
                {{-- Techniciens --}}
                <div class="mt-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Techniciens & Fournisseurs</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">Liste des Techniciens et des Fournisseurs</p>

                    <dl class="mt-6 space-y-6 divide-y divide-gray-100 border-t border-gray-200 text-sm leading-6 w-1/2">
                        <div class="pt-6 sm:flex flex justify-between" v-for="technicien in techniciens">
                            <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">@{{ technicien.nom }}
                            </dt>
                            <dd class="mt-1 flex justify-end gap-x-6 sm:mt-0 sm:flex-auto">

                                <button type="button" class="font-semibold text-blue-600 hover:text-blue-500"
                                    data-toggle="modal" data-target="#editerTechnicien"
                                    @click="passData(technicien)">Editer</button>
                                <button type="button" class="font-semibold text-red-600 hover:text-red-500"
                                    @click="deleteResource('/techniciens/' + technicien.id, 'Technicien' )">Supprimer</button>
                            </dd>
                        </div>

                    </dl>
                    <div class="flex border-t border-gray-100 pt-6">
                        <button type="button" data-target="#ajouterTechnicien" data-toggle="modal"
                            class="text-sm font-semibold leading-6 text-blue-600 hover:text-blue-500"><span
                                aria-hidden="true">+</span> Ajouter un Techncien</button>
                    </div>
                </div>



            </main>




            <div class="modal fade" id="ajouterTypeDocument" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
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
            <div class="modal fade" id="editerTypeDocument" tabindex="-1" role="dialog"
                aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter Type Document</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form :action="'/documents/' + modalData.id + '/update'" method="POST" v-if="modalData">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <input type="text" class="form-control" name="type" placeholder="Manivelle"
                                        :value="modalData.type">
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
            <div class="modal fade" id="editerTypeAccessoire" tabindex="-1" role="dialog"
                aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter Accessoire</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form :action="'/accessoires/' + modalData.id + '/update'" method="POST" v-if="modalData">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <input type="text" class="form-control" name="type" placeholder="Manivelle"
                                        :value="modalData.type">
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
            <div class="modal fade" id="ajouterTechnicien" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="editerTechnicien" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form :action="'/techniciens/' + modalData.id + '/update'" method="POST" v-if="modalData">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Nom</label>
                                    <input type="text" class="form-control" name="nom" :value="modalData.nom">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


        </div>


    </parametres-index>
@endsection
