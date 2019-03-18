<template>
    <div class="container-fluid" >

        <step-1 :list="clients" @passeAEtape2="lanceEtape2" v-show="this.step === 1"></step-1>

        <transition 
            name="fade" 
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut"
        >
            <step-2 :list="voitures" @passeAEtape3="lanceEtape3" v-show="this.step === 2"></step-2>
        </transition>
        
        <transition 
            name="fade" 
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut"
        >
            <step-3 :list="voitures" @passeAEtape4="lanceEtape4" v-show="this.step === 3"></step-3>
        </transition>

        <transition 
            name="fade" 
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut"
        >
            <step-4 :voiture="this.contrat.voiture" @passeAEtape5="lanceEtape5" v-if="this.step === 4"></step-4>
        </transition>

        <transition 
            name="fade" 
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut"
        >
            <step-5 :voiture="this.contrat.voiture" @passeAEtape6="lanceEtape6" 
                v-if="this.step === 5"
            ></step-5>
        </transition>

        <!-- <transition 
            name="fade" 
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut"
        >
            <step-6 v-if=" this.step === 6" @passeAEtape7="lanceEtape7">

            </step-6>
        </transition> -->
            
        <transition 
            name="fade" 
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut"
        > 
            <contrat-final @enregistrer="enregistrer" @envoyerACashier="envoyerACashier()" :contrat="this.contrat" :contrat_enregistre="this.contrat_enregistré" v-if=" this.step === 6">
                
            </contrat-final>
        </transition>
        
        <!--  -->
    </div>
</template>         
<script>
export default {
    props: ['prop_clients', 'prop_voitures'],
    data(){
        return {

            clients: this.prop_clients,
            voitures: this.prop_voitures,
            finalStep: false,
            step: 1,
            contrat : {
                client : null, //this.prop_clients[0]
                voiture: null, // this.prop_voitures[0]
                check_out: null,
                check_in: null,
                nombre_jours: null,
                prix_journalier: null,
                caution: null,
                photos: {
                    avant: null,
                    arriere: null,
                    droit: null,
                    gauche: null
                }
            },
            contrat_enregistré: null,
            cashier_id: null,
            data : null
        }
    },
    watch: {
        cashier_id(){
            axios.post('/contrats/' + this.contrat_enregistré.id + '/update-cashier', {id: this.cashier_id}).then(response => {
            
                this.contrat_enregistré.cashier_facture_id = this.cashier_id
                this.$forceUpdate();
                
            }).catch(error => {
                console.log(error);
            });
            console.log(this.cashier_id)
        }
    },
    computed: {
        documentString(){
            let binary = '';
            if(this.contrat.voiture){
                this.contrat.voiture.documents.forEach(document => {
                    if(document.type === 'Carte Grise'){
                        binary += '1'
                    }
                })
                if(binary.length === 0){
                    binary += '0'
                }
                this.contrat.voiture.documents.forEach(document => {
                    if(document.type === 'Visite Technique'){
                        binary += '1'
                    }
                })
                if(binary.length === 1){
                    binary += '0'
                }
                this.contrat.voiture.documents.forEach(document => {
                    if(document.type === 'Assurance'){
                        binary += '1'
                    }
                })
                if(binary.length === 2){
                    binary += '0'
                }
                this.contrat.voiture.documents.forEach(document => {
                    if(document.type === 'Carte Extincteur'){
                        binary += '1'
                    }
                })
                if(binary.length === 3){
                    binary += '0'
                }
                return binary
            }
        },
        accessoireString(){
            let binary = '';
            if(this.contrat.voiture){
                this.contrat.voiture.accessoires.forEach(accessoire => {
                    if(accessoire.type === 'Crick'){
                        binary += accessoire.pivot.quantité
                    }
                })
                this.contrat.voiture.accessoires.forEach(accessoire => {
                    if(accessoire.type === 'Triangle'){
                        binary += accessoire.pivot.quantité
                    }
                })
                this.contrat.voiture.accessoires.forEach(accessoire => {
                    if(accessoire.type === 'Manivelle'){
                        binary += accessoire.pivot.quantité
                    }
                })
                this.contrat.voiture.accessoires.forEach(accessoire => {
                    if(accessoire.type === 'Calle Métallique'){
                        binary += accessoire.pivot.quantité
                    }
                }) 
                
                this.contrat.voiture.accessoires.forEach(accessoire => {
                    if(accessoire.type === 'Pneu Secours'){
                        binary += accessoire.pivot.quantité
                    }
                })
                this.contrat.voiture.accessoires.forEach(accessoire => {
                    if(accessoire.type === 'Gilet'){
                        binary += accessoire.pivot.quantité
                    }
                })
                this.contrat.voiture.accessoires.forEach(accessoire => {
                    if(accessoire.type === 'Extincteur'){
                        binary += accessoire.pivot.quantité
                    }
                })
                this.contrat.voiture.accessoires.forEach(accessoire => {
                    if(accessoire.type === 'Trousse Secours'){
                        binary += accessoire.pivot.quantité
                    }
                })
                return binary
            }
        }
    },
    methods:{
        lanceEtape2(value){
            this.contrat.client = value  
            this.step = 2
        },
        lanceEtape3(value){
            this.contrat.voiture = value  
            this.step = 3
        },
        lanceEtape4(value){
            this.step = 4
            this.contrat.check_out = value.check_out
            this.contrat.check_in = value.check_in  
            this.contrat.nombre_jours = (new Date(this.contrat.check_in) - new Date(this.contrat.check_out) ) / ( 1000 * 60 * 60 * 24)
        },
        lanceEtape5(value){
            this.step = 5
            this.contrat.prix_journalier = value.prix_journalier
            this.contrat.caution = value.caution
        },
        lanceEtape6(value){
            this.step = 6
        },
        lanceEtape7(payload){
            this.contrat.photos.arriere = payload.arriere
            this.contrat.photos.avant = payload.avant
            this.contrat.photos.gauche = payload.gauche
            this.contrat.photos.droit = payload.droit
            this.step = 7
            this.finalStep = true;
            this.$forceUpdate()
            
        },
        envoyerACashier(){
            var data;
            this.data = data = {
                'objet': 'Location ' + this.contrat.voiture.marque + ' ' + this.contrat.voiture.type + ' ' + this.contrat.voiture.immatriculation,
                'échéance' : this.contrat.check_in,
                'quantité' : this.contrat.nombre_jours,
                'description' : 'Jours',
                'prix_unitaire' : this.contrat.prix_journalier
            };
            
            axios.post('http://facture.test/api/facture', this.data).then(response => {
                var cashier_id = response.data.id
                this.cashier_id = cashier_id       
                // if( cashier_id !== null ){
                //     axios.post('/contrat/' + this.contrat_enregistré.id + '/update-cashier-id', {cashier_id: this.cashier_id}).then( response => {
                //         console.log(response.data);
                //     }).catch(error => {
                //         console.log(error);
                //     });
                // }   
            }).catch(error => {
                console.log(error);
            });
        },
        enregistrer(){
            this.contrat.accessoireString = this.accessoireString
            this.contrat.documentString = this.documentString
            
            axios.post('/contrats/store', this.contrat).then(response => {
                window.location = '/contrat/' + response.data.id
                this.contrat_enregistré = response.data
                
            }).catch(error => {
                
            });
        },
        submitFile(file, id){
            let formData = new FormData();
            formData.append('droit', file.droit);
            formData.append('avant', file.avant);
            formData.append('arriere', file.arriere);
            formData.append('gauche', file.gauche);
            axios.post( '/contrats/' + id +'/ajoute-photos',
            formData,
            {
            headers: {
                'Content-Type': 'multipart/form-data'
                }
            }
            ).then(function(){
                console.log('SUCCESS!!');
            })
            .catch(function(){
                console.log('FAILURE!!');
            });

        },
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>