<template>
    <div class="">
        <!-- Contrat Véhicule -->
    lk;læ

        <div v-if="contrat.compagnie.type === 'véhicules'" class="tw-h-screen tw-flex tw-flex-col">
            <!-- EN TETE -->
            <div class="row mt-3">
                <div class="col">
                    <img src="/img/logosta.png"/>
                </div>
                <div class="col-3 mt-4 border">
                    <div class="row mt-3 ml-1">
                        <h5><u>Client:</u></h5>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-3">
                            <p>
                                <a :href="'/clients/' + contrat.client.id ">{{ contrat.client.nom + ' ' + contrat.client.prenom  }}</a></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <p><i>{{ contrat.client.phone1 }}</i></p>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="display-6 letter-spaced-1 mt-2 text-center mt-5" ><u>CONTRAT {{ contrat.numéro }}</u></h3>
            <!-- INFORMATION VOITURE -->
            <div class="row mt-3">
                <p>
                    <u><strong>Objet:</strong></u> Location <i>{{ contrat.voiture.marque }} {{ contrat.voiture.type }}  {{ contrat.voiture.annee }} </i> immatriculé <i> <a :href="'/voiture/' + contrat.voiture.id">{{ contrat.voiture.immatriculation }}</a></i>
                </p>

            </div>
            <!-- Période -->
            <div class="row">
                <p>
                    <u><strong>Période:</strong></u>
                    <i class="text-primary">{{ contrat.au | moment("Do MMMM YYYY, h:mm ") }}</i> -
                    <i class="text-primary">{{ contrat.du | moment("Do MMMM YYYY, h:mm ")  }}</i>
                    <!-- ({{ contrat.nombre_jours }} jours) -->
                </p>

            </div>
            <!-- Montant Versé -->
            <div class="row">
                <p v-if="totalPaiement !== -1">
                    <u ><strong>Montant Versé:</strong></u><span>{{ totalPaiement }} F CFA</span>
                </p>
                <p v-else-if="totalPaiement === -1">
                    <u ><strong>Montant Versé:</strong></u><i class="fas fa-sync ml-3 pointer" @click="getPaiements()"></i>
                </p>
                <p v-else>
                    <i class="fas fa-spinner fa-spin"></i>
                </p>
            </div>
            <div class="row">
                <p v-if="contrat.caution > 0">
                    <u><strong>Caution:</strong></u> {{ contrat.caution }} F C FA. <strong>N.B: La caution ne sera restituée entièrement qu'en cas de respect des clauses suivantes:</strong>
                </p>
                <p class="text-danger" v-else>Aucune caution n'a été versé. De ce fait, le client s'engage à endosser toutes conséquences liées au non-respect des clauses suivantes:</p>
            </div>
            <div class="row mt-1">
                <ol>
                    <li>Le Véhicule sera restitué à l'heure indiquée sur le contrat.</li>
                    <li>Le Véhicule devra être restitué dans le même état qu'il a été pris; faute de quoi le locataire endossera les charges afférentes aux dommages éventuels.</li>
                    <li>Les images enregistrées dans le système et envoyées au client par e-mail feront office de réference de l'état du véhicule.</li>
                    <li>S.T.A se réserve le droit de récuperer le véhicule loué pour tout retard de paiement de plus de 48 heures.</li>
                    <li>S.T.A se réserve le droit de récuperer le véhicule loué au cas où une personne autre que le client est aperçu entrain de conduire ce véhicule.</li>
                    <li>Toute prolongation de contrat devra être notifiée 24 heures avant échéance sous peine d'une pénalité de 10.000 F imputable sur la caution</li>
                </ol>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <h5 class=""><u>Arrêter la présente facture à la somme de : </u></h5>
                </div>
                <div class="col">
                    <p class="ml-4">Le Client</p>
                </div>
                <div class="col">
                    <p>Le Responsable</p>
                </div>
            </div>

            <!-- <h4 class="mt-3">{{ wn( contrat.prix_journalier * contrat.nombre_jours ) }} Francs CFA</h4> -->

            <div class="row mt-5 dashed">
                <div class="col text-center" id="buttons" v-if=" ! printing">
                    <!-- Bouton Retour -->
                    <button class="btn btn-secondary" @click="decrementStep()" v-if="contrat_enregistre === null">Retour</button>
                    <!-- Bouton Enregistrer -->
                    <button type="button" class="btn btn-primary" @click="enregistrer" v-if="contrat_enregistre === null">
                        <i v-if="isSaving" class="fas fa-spinner fa-spin"></i>
                        Enregistrer
                        </button>
                    <!-- Bouton Envoyer a Cashier -->
                    <button type="button" class="btn btn-primary" @click="envoyerACashier" v-if="contrat_enregistre !== null && contrat.cashier_facture_id === null">
                    <i v-if="isLoading" class="fas fa-spinner fa-spin"></i> Envoyer à Cashier
                    </button>
                    <!-- Bouton Voir Dans Cashier -->
                    <a target="_blank" :href="'http://cashier.azimuts.ga/STA/Facture/' + contrat.cashier_facture_id" class="btn btn-primary" v-if="contrat_enregistre !== null && contrat.cashier_facture_id !== null && environment === 'production'" >Voir Facture dans Cashier</a>

                    <a target="_blank" :href="'http://thecashier.test/Heaney%20LLC/Facture/' + contrat.cashier_facture_id" class="btn btn-primary" v-if="contrat_enregistre !== null && contrat.cashier_facture_id !== null && environment === 'local'" >Voir Facture dans Cashier</a>

                    <!-- Bouton Ajouter Paiement -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ajoutPaiement" v-if="contrat_enregistre !== null && contrat.cashier_facture_id !== null">
                        Ajouter un Paiement
                    </button>

                    <!-- Bouton Imprimer -->
                    <button type="button" class="btn btn-primary" @click="imprimer" v-if="contrat_enregistre !== null">Imprimer</button>
                    <!-- Button trigger modal -->
                    <a v-if="contrat_enregistre !== null"  class="btn btn-primary" :href="'/test-upload/' + contrat.id">Attacher Photos</a>
                    <!-- Voir Photos -->
                    <a :href="'/contrat/' + this.contrat.id + '/voir-uploads'" class="btn btn-primary" v-if="contrat_enregistre !== null">Voir Photos</a>
                </div>
            </div>

            <!-- <attache-photos :contrat="contrat_enregistre" v-if="contrat_enregistre !== null"></attache-photos> -->
        </div>






        <!-- Facture Hotel -->

        <div v-if="contrat.compagnie.type === 'hôtel'" class="tw-h-screen tw-flex tw-flex-col">

            <!-- En-Tête -->
            <div class="tw-flex tw-bg-blue-500 tw-sticky tw-top-0 tw-flex-none">
                <!-- Information Société -->
                <div class="tw-flex tw-flex-col tw-items-center">
                    <!-- Logo -->
                    <img src="/img/orishainn_logo.png" alt="" class="tw-w-1/4">
                    <!-- Détails Compagnie -->
                    <div class="tw-text-white tw-text-center">
                        <p>MOUKALA, B.P:1268 PORT-GENTIL, GABON</p>
                        <p>Tél: 011-56-08-55 / 077-15-82-15 / 066-26-82-75</p>
                    </div>
                </div>
            </div>

            <!-- Date Création Facture -->
            <p class="tw-flex tw-justify-end tw-flex-none">Port-Gentil, le {{ contrat.created_at | moment("Do MMMM YYYY")}}</p>

            <!-- Information Client -->
            <div class="tw-w-full tw-flex tw-flex-col tw-items-end tw-justify-end tw-mt-20 tw-flex-none">
                <p class="tw-flex tw-w-1/4 tw-p-2 tw-bg-blue-300">Client:</p>
                <div class="tw-flex tw-flex-col tw-w-1/4 tw-py-5 tw-px-5 tw-border tw-border-gray-300">
                    <p>
                        <a :href="'/client/' + contrat.client.id" target="_blank">
                            {{ contrat.client.nom }} {{ contrat.client.prenom }}
                        </a>
                    </p>
                    <p>{{contrat.adresse}}</p>
                    <p>{{ contrat.client.phone1}} <span v-if="contrat.client.phone2"> / {{ contrat.client.phone2 }}</span></p>
                </div>
            </div>

            <!-- Numéro Facture -->
            <h2 class="tw-text-3xl tw-mt-24 tw-flex-none">Facture Nº {{ contrat.numéro }}</h2>

            <!-- Tableau -->
            <table class="table tw-mt-10 tw-flex-none">
                <!-- En-tête -->
                <thead>
                    <tr class="tw-bg-blue-300">
                        <th>Description</th>
                        <th>Nombre de Jours</th>
                        <th>Prix Unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <!--  -->
                <tbody>
                    <tr>
                        <td scope="row" v-if="contrat.compagnie.type === 'hôtel' ">{{ contrat.contractable.nom }}</td>
                        <td>{{contrat.nombre_jours}}</td>
                        <td>{{ contrat.prix_journalier }}</td>

                        <td>{{contrat.nombre_jours * contrat.prix_journalier }}</td>
                    </tr>
                    <tr class="">
                        <td colspan="2"></td>
                        <td class="tw-bg-blue-300">TOTAL </td>
                        <td class="tw-bg-blue-300">{{contrat.nombre_jours * contrat.prix_journalier }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Arrêté de Facture -->
            <p class="tw-mt-16 tw-text-lg tw-font-bold tw-underline tw-flex-none">Arrêté la Présente Facture à la Somme de: </p>
            <p class="tw-mt-3 tw-tracking-wider ">{{ wn(contrat.total) }} Francs CFA</p>


            <!-- Pied de Page -->
            <div class="tw-flex tw-flex-grow tw-items-end tw-sticky tw-bottom-0">

                <div class="tw-flex tw-bg-blue-500  tw-w-full">
                    <!-- Information Société -->
                    <div class="tw-flex tw-flex-col tw-items-center">

                        <!-- Détails Compagnie -->
                        <div class="tw-text-white tw-text-center">
                            <p>MOUKALA, B.P:1268 PORT-GENTIL, GABON</p>
                            <p>Tél: 011-56-08-55 / 077-15-82-15 / 066-26-82-75</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!-- Modal Paiement -->
        <div class="modal fade" id="ajoutPaiement" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un Paiement</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Montant</label>
                            <input type="text" class="form-control" v-model="paiement.montant">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" @click="ajouterUnPaiement"><i v-if="isLoading" class="fas fa-spinner fa-spin"></i>Payer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copie Client -->
        <div id="copie_client" class="" v-if="printing">
            <!-- EN TETE -->
            <div class="row mt-3">
                <div class="col">
                    <img src="/img/logosta.png"/>
                </div>
                <div class="col-3 mt-4 border">
                    <div class="row mt-3 ml-1">
                        <h5><u>Client:</u></h5>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-3">
                            <p>
                                <a :href="'/clients/' + contrat.client.id ">{{ contrat.client.nom + ' ' + contrat.client.prenom  }}</a></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <p><i>{{ contrat.client.phone1 }}</i></p>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="display-6 letter-spaced-1 mt-2 text-center mt-5" ><u>CONTRAT {{ contrat.numéro }}</u></h3>
            <!-- INFORMATION VOITURE -->
            <div class="row mt-3">
                <p>
                    <u><strong>Objet:</strong></u> Location <i>{{ contrat.voiture.marque }} {{ contrat.voiture.type }}  {{ contrat.voiture.annee }} </i> immatriculé <i> <a :href="'/voiture/' + contrat.voiture.id">{{ contrat.voiture.immatriculation }}</a></i>
                </p>

            </div>
            <div class="row">
                <p>
                    <!-- <u><strong>Période:</strong></u> <i class="text-primary">{{ contrat.au | moment("Do MMMM YYYY, h:mm ") }}</i> - <i class="text-primary">{{ contrat.du | moment("Do MMMM YYYY, h:mm ")  }}</i> ({{ contrat.nombre_jours }} jours) -->
                </p>
            </div>
            <div class="row">
                <p v-if="totalPaiement !== -1">
                    <u ><strong>Montant Versé:</strong></u> <span>{{ totalPaiement }} F CFA</span>
                </p>
                <p v-else-if="totalPaiement === -1">
                    <u ><strong>Montant Versé:</strong></u><i class="fas fa-sync ml-3"></i>
                </p>
                <p v-else>
                    <i class="fas fa-spinner fa-spin" ></i>
                </p>
            </div>
            <div class="row">
                <p v-if="contrat.caution > 0">
                    <u><strong>Caution:</strong></u> {{ contrat.caution }} F C FA. <strong>N.B: La caution ne sera restituée entièrement qu'en cas de respect des clauses suivantes:</strong>
                </p>
                <p class="text-danger" v-else>Aucune caution n'a été versé. De ce fait, le client s'engage à endosser toutes conséquences liées au non-respect des clauses suivantes:</p>
            </div>
            <div class="row mt-1">
                <ol>
                    <li>Le Véhicule sera restitué à l'heure indiquée sur le contrat.</li>
                    <li>Le Véhicule devra être restitué dans le même état qu'il a été pris; faute de quoi le locataire endossera les charges afférentes aux dommages éventuels.</li>
                    <li>Les images enregistrées dans le système et envoyées au client par e-mail feront office de réference de l'état du véhicule.</li>
                    <li>S.T.A se réserve le droit de récuperer le véhicule loué pour tout retard de paiement de plus de 48 heures.</li>
                    <li>S.T.A se réserve le droit de récuperer le véhicule loué au cas où une personne autre que le client est aperçu entrain de conduire ce véhicule.</li>
                    <li>Toute prolongation de contrat devra être notifiée 24 heures avant échéance sous peine d'une pénalité de 10.000 F imputable sur la caution</li>
                </ol>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <h5 class=""><u>Arrêter la présente facture à la somme de : </u></h5>
                </div>
                <div class="col">
                    <p class="ml-4">Le Client</p>
                </div>
                <div class="col">
                    <p>Le Responsable</p>
                </div>
            </div>

            <!-- <h4 class="mt-3">{{ wn( contrat.prix_journalier * contrat.nombre_jours ) }} Francs CFA</h4> -->
        </div>
    </div>

</template>

<script>
export default {
    props: ['contrat', 'contrat_enregistre', 'environment'],
    data(){
        return {
            printing: false,
            envoyéACashier: null,
            isLoading : false,
            isSaving : false,
            paiement : {
                facture_id: null,
                montant: null
            },
            paiements: null,
            totalPaiement: null
        }
    },
    computed : {
        nombre_jours(){
            return (new Date(this.contrat.du) - new Date(this.contrat.au))
        }
    },
    methods:{
        decrementStep(event){
            this.$emit('decrementStep', this.voiture)
        },
        envoyerACashier(){
            if(! this.isLoading){
                    this.$emit('cashier', this.contrat)
                this.isLoading = true
            } else {
                alert('La patience est une grande vertue! ')
            }
        },
        ajouterUnPaiement(){
            this.paiement.facture_id = this.contrat.cashier_facture_id
            if(! this.isLoading){
                this.$emit('paiement', this.paiement)
                this.isLoading = true
            } else {
                alert('La patience est une grande vertue! ')
            }
        },
        getPaiements(){
            if(this.contrat.cashier_facture_id ){
                var link = 'https://cashier.azimuts.ga/api/get-paiements/' + this.contrat.cashier_facture_id ;
                if(this.environment === 'local'){
                    link = 'https://thecashier.test/api/get-paiements/' + this.contrat.cashier_facture_id;
                }
                axios.get(link).then(response => {
                    this.paiements = response.data
                }).catch(error => {
                    console.log(error);
                });
            }
            var total = 0
            setTimeout(() => {
                if(this.paiements){
                    this.paiements.forEach(paiement => {
                        total += paiement.montant
                    });
                    this.totalPaiement = total

                } else {
                    this.totalPaiement = -1
                }

                this.$forceUpdate()
            },1000);

        },
        enregistrer(){
            this.isSaving = true;
            this.$emit('enregistrer')
        },
        imprimer(){
            this.printing = true
            setTimeout(() => {
                window.print()
            }, 1000);
            setTimeout(() => {
                this.printing = false
            }, 5000);
        },
        //Utilitaires
        wn(number){
            return this.toUpper(writtenNumber(number))
        },
        toUpper(str) {
            return str
                .toLowerCase()
                .split(' ')
                .map(function(word) {
                    return word[0].toUpperCase() + word.substr(1);
                })
                .join(' ')
                .split('-')
                .map(function(word) {
                    return word[0].toUpperCase() + word.substr(1);
                })
                .join('-');
        },
    },
    mounted(){
        this.getPaiements()
        var total = 0
        setTimeout(() => {
            if(this.paiements){
                this.paiements.forEach(paiement => {
                    total += paiement.montant
                });
                this.totalPaiement = total

            } else {
                this.totalPaiement = -1
            }

            this.$forceUpdate()
        },1000);

    }
}
</script>
<style>
    p{
      font-size: 12pt
    }
    .dashed {
        border-top: 1px dashed black
    }
    .pbn-3 {

    }
</style>
