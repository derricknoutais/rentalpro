<div class="flex px-1 py-3 bg-gray-100 rounded" v-if="contrat.deleted_at === null">

    @can('créer paiement')
        <button type="button" class="px-1 py-0 mr-2 text-white bg-blue-500 btn" data-toggle="modal"
            data-target="#ajouter-paiement-modal" @click="passDataToModal('contrat', contrat)">
            Effectuer Paiement
        </button>
    @endcan


    {{-- <button type="button" class="sm:hidden px-1 py-0 mr-2 text-white bg-blue-500 btn"
                                    data-toggle="modal" data-target="#demi-journee-modal"
                                    @click="passDataToModal('contrat', contrat)" v-if="! contrat.demi_journee">
                                    Ajouter 1/2 Journee
                                    </button> --}}

    {{-- <button type="button" v-if="! contrat.montant_chauffeur"
                                        class=" px-1 py-0 mr-2 text-white bg-blue-500 btn" data-toggle="modal"
                                        data-target="#montant-chauffeur-modal" @click="passDataToModal('contrat', contrat)">
                                        Ajouter Chauffeur
                                    </button> --}}

    <!-- Si le contrat n'est plus en cours -->



    <!-- Si le contrat est en cours -->

    @can('prolonger contrat')
        <button type="button" class="px-1 py-0 mr-2 btn btn-primary btn-sm " @click="passDataToModal('contrat', contrat)">
            <i class="fas fa-clock"></i> Prolonger Contrat
        </button>
    @endcan
    @can('editer contrat')
        <button type="button" class="px-1 py-0 btn btn-secondary btn-sm" data-toggle="modal"
            data-target="#changer-voiture-modal" @click="passDataToModal('contrat', contrat)">
            <i class="mr-1 fas fa-exchange-alt"></i> Changer Voiture
        </button>
    @endcan
    {{-- <span class="ml-4 badge badge-pill badge-warning">En Location</span> --}}


</div>
