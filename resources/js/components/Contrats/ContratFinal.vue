<template>
    <div class='container'>
        <div class="">
            <!-- EN TETE -->
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-1">
                            <img src="/img/stalogo.png" width=200 />
                        </div>
                        <div class="col pl-5 mt-1 ml-5 ">
                            <p class="pl-5">Location, Vente de véhicules et Pièces Détachées Auto</p>
                            <p class="pl-5">Tel: 01 56 08 55 / 07 15 82 15</p>
                            <p class="pl-5">B.P: 1268 Port-Gentil </p>
                            <p class="pl-5">E-mail: stapog98@gmail.com</p>
                        </div>
                        <div class="col">
                            <div class="row mt-1">
                                <div class="offset-3 col-6 border">
                                    <div class="row mt-3 ml-1">
                                        <h5><u>Client:</u></h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <p>{{ contrat.client.nom + ' ' + contrat.client.prenom  }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col">
                                            <p><i>{{ contrat.client.phone1 }}</i></p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            </div>
                        </div>
                    <h3 class="display-6 letter-spaced-1 mt-2 text-center mt-3" ><u>CONTRAT {{ contrat.numéro }}</u></h3>
                </div>
            </div>
            

            <!-- INFORMATION VOITURE -->
            <div class="row mt-3">
                <p>
                    Location Véhicule de Marque <i>{{ contrat.voiture.marque }} {{ contrat.voiture.type }}  {{ contrat.voiture.annee }} </i> immatriculé <i>{{ contrat.voiture.immatriculation }}</i>
                </p>
            
            </div>
            <div class="row">
                <p>
                    Ce contrat est valable à partir du <i>{{ contrat.check_out }}</i> jusqu'au <i>{{ contrat.check_in }}</i> soit pour une durée de {{ contrat.nombre_jours }} jours ({{ contrat.nombre_jours* 24 }} heures)
                </p>
            </div>
            <div class="row">
                <p>Une caution de {{ contrat.caution }} F a été versée et ne sera restitué entièrement qu'en cas de respect des clauses suivantes:</p>
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
                <div class="col text-center" id="buttons">
                    
                    <!-- Bouton Retour -->
                    <button class="btn btn-secondary" @click="decrementStep()" v-if="contrat_enregistre === null">Retour</button>
                    <!-- Bouton Enregistrer -->
                    <button type="button" class="btn btn-primary" @click="enregistrer" v-if="contrat_enregistre === null">Enregistrer</button>
                    <!-- Bouton Envoyer a Cashier -->
                    <button type="button" class="btn btn-primary" @click="envoyerACashier" v-if="contrat_enregistre !== null && contrat.cashier_facture_id === null"> <i v-if="isLoading" class="fas fa-spinner "></i> Envoyer à Cashier</button>
                    <!-- Bouton Voir Dans Cashier -->
                    <a target="_blank" :href="'https://thecashier.ga/STA/Facture/' + contrat.cashier_facture_id" class="btn btn-primary" v-if="contrat_enregistre !== null && contrat.cashier_facture_id !== null && environment === 'production'" >Voir Facture dans Cashier</a>
                    
                    <a target="_blank" :href="'http://thecashier.test/Lesch%20Group/Facture/' + contrat.cashier_facture_id" class="btn btn-primary" v-if="contrat_enregistre !== null && contrat.cashier_facture_id !== null && environment === 'local'" >Voir Facture dans Cashier</a>
                    <!-- Bouton Imprimer -->
                    <button type="button" class="btn btn-primary" @click="imprimer" v-if="contrat_enregistre !== null">Imprimer</button>
                    <!-- Button trigger modal -->
                    <a v-if="contrat_enregistre !== null"  class="btn btn-primary" :href="'/test-upload/' + contrat.id">Attacher Photos</a>
                    <!-- Voir Photos -->
                    <a :href="'/contrat/' + this.contrat.id + '/voir-uploads'" class="btn btn-primary" v-if="contrat_enregistre !== null">Voir Photos</a>
                </div>
            </div>

            
        </div>
        <attache-photos :contrat="contrat_enregistre" v-if="contrat_enregistre !== null"></attache-photos>




        <div id="copie_client" class=" pt-5" >
            <!-- EN TETE -->
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-1">
                            <img src="/img/stalogo.png" width=200 />
                        </div>
                        <div class="col pl-5 mt-1 ml-5 ">
                            <p class="pl-5">Location, Vente de véhicules et Pièces Détachées Auto</p>
                            <p class="pl-5">Tel: 01 56 08 55 / 07 15 82 15</p>
                            <p class="pl-5">B.P: 1268 Port-Gentil </p>
                            <p class="pl-5">E-mail: stapog98@gmail.com</p>
                        </div>
                        <div class="col">
                            <div class="row mt-1">
                                <div class="offset-3 col-6 border">
                                    <div class="row mt-3 ml-1">
                                        <h5><u>Client:</u></h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <p>{{ contrat.client.nom }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <p><i>{{ contrat.client.phone1 }}</i></p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            </div>
                        </div>
                    <h3 class="display-6 letter-spaced-1 mt-2 text-center mt-3" ><u>CONTRAT {{ contrat.numéro }}</u></h3>
                </div>
            </div>


            <!-- INFORMATION VOITURE -->
            <div class="row mt-3">
                <p>
                    Location Véhicule de Marque <i>{{ contrat.voiture.marque }} {{ contrat.voiture.type }}  {{ contrat.voiture.annee }} </i> immatriculé <i>{{ contrat.voiture.immatriculation }}</i>
                </p>

            </div>
            <div class="row">
                <p>
                    Ce contrat est valable à partir du <i>{{ contrat.check_out }}</i> jusqu'au <i>{{ contrat.check_in }}</i> soit pour une durée de {{ contrat.nombre_jours }} jours ({{ contrat.nombre_jours* 24 }} heures)
                </p>
            </div>
            <div class="row">
                <p>Une caution de la somme de {{ contrat.caution }} F a été versée et ne sera restitué entièrement qu'en cas de respect des clauses suivantes:</p>
            </div>
            <div class="row mt-1">
                <ol>
                    <li> sera restitué à l'heure prévue par ce contrat</li>
                    <li>Le Véhicule devra être restitué dans le même état qu'il a été pris</li>
                    <li>Les images enregistrées dans le système et envoyées au client par e-mail feront office de réference de l'état du véhicule</li> 
                    <li>S.T.A se réserve le droit de récuperer le véhicule loué en cas de retard de paiement</li>
                    <li>S.T.A se réserve le droit de récuperer le véhicule loué au cas où une personne autre que {{ contrat.client.nom }} est aperçu entrain de conduire ce véhicule.</li>
                    <li>Afin de renouveler le contrat, {{ contrat.client.nom }} devra avertir avant la fin de contrat sous peine d'une pénalité de 10.000 F retiré de sa caution</li>
                </ol>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <h5 class=""><u>Arrêté la présente facture à la somme de : </u></h5>
                </div>
                <div class="col">
                    <p class="ml-4">Le Client</p>
                </div>
                <div class="col">
                    <p>Le Responsable</p>
                </div>
            </div>

            <h4 class="mt-3">{{ wn( contrat.prix_journalier * contrat.nombre_jours ) }} Francs CFA</h4>

            <div class="row mt-5">
                <div class="col text-center" id="buttons2">
                    <button type="button" class="btn btn-primary" @click="enregistrer">Enregistrer</button>
                    <button type="button" class="btn btn-primary" @click="envoyerACashier">Envoyer à Cashier</button>
                    <button type="button" class="btn btn-primary" @click="imprimer">Imprimer</button>
                </div>
            </div>
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
            isLoading : false
        }
    },
    methods:{
        decrementStep(event){
            this.$emit('decrementStep', this.voiture)
        },
        envoyerACashier(){
            console.log('Beee')
            if(! this.isLoading){
                // setTimeout(() => {
                    this.$emit('cashier', this.contrat) 
                // }, 5000);
                this.isLoading = true
            } else {
                alert('La patience est une grande vertue! ')
            }
            
            
            
        },
        enregistrer(){
            this.$emit('enregistrer')
        },
        imprimer(){
            document.getElementById('copie_client').style.visibility = 'visible'
            document.getElementById('buttons').style.visibility = 'hidden'
            document.getElementById('buttons2').style.visibility = 'hidden'
            window.print()

            setTimeout(() => {
                document.getElementById('copie_client').style.visibility = 'hidden'
                document.getElementById('buttons').style.visibility = 'visible'
                document.getElementById('buttons2').style.visibility = 'visible'
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
        document.getElementById('copie_client').style.visibility = 'hidden'
    },
    created(){
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
   