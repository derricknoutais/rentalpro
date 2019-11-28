<template>
    <div class="container-fluid">
            <div>
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
                        <u><strong>Période:</strong></u> <i class="text-primary">{{ contrat.check_out | moment("Do MMMM YYYY, h:mm ") }}</i> - <i class="text-primary">{{ contrat.check_in | moment("Do MMMM YYYY, h:mm ")  }}</i> ({{ contrat.nombre_jours }} jours)
                    </p>
                </div>
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

                <h4 class="mt-3">{{ wn( contrat.prix_journalier * contrat.nombre_jours ) }} Francs CFA</h4>

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
                        <a target="_blank" :href="'https://thecashier.ga/STA/Facture/' + contrat.cashier_facture_id" class="btn btn-primary" v-if="contrat_enregistre !== null && contrat.cashier_facture_id !== null && environment === 'production'" >Voir Facture dans Cashier</a>

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
            <!-- Modal -->
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
                        <u><strong>Période:</strong></u> <i class="text-primary">{{ contrat.check_out | moment("Do MMMM YYYY, h:mm ") }}</i> - <i class="text-primary">{{ contrat.check_in | moment("Do MMMM YYYY, h:mm ")  }}</i> ({{ contrat.nombre_jours }} jours)
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

                <h4 class="mt-3">{{ wn( contrat.prix_journalier * contrat.nombre_jours ) }} Francs CFA</h4>
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
                var link = 'https://thecashier.ga/api/get-paiements/' + this.contrat.cashier_facture_id ;
                if(this.environment === 'local'){
                    link = 'http://thecashier.test/api/get-paiements/' + this.contrat.cashier_facture_id;
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
   