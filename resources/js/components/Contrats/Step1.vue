<template >
    <div class='container' style="height:95vh"  >
        <h1 class="text-center mt-5">Selectionne Client</h1>
        <!-- Informations Client Sélectionné -->
        <div>
            <div class="row mt-5">
                <div class="col">Nom:</div>
                <div class="col">Numéro Phone 1:</div>
                <div class="col">Addresse Maill:</div>
            </div>
            <transition name="fade" 
                enter-active-class="animated fadeIn"
                leave-active-class="animated fadeOut">
                <div class="row" v-if="client">
                    <div class="col" v-if="client">{{ client.nom }}</div>
                    <div class="col" v-if="client">{{ client.phone1 }}</div>
                    <div class="col" v-if="client">{{ client.mail }}</div>
                </div>
            </transition>
            
            <div class="row mt-5">
                <div class="col">Nº Permis:</div>
                <div class="col">Adresse:</div>
                <div class="col">Nombre Locations:</div>
            </div>
            <transition name="fade" 
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut">
                <div class="row" v-if="client">
                    <div class="col">{{ client.numero_permis }}</div>
                    <div class="col">{{ client.adresse }}</div>
                    <div class="col"></div>
                </div>
            </transition>
        </div>
        <!-- V-Select Element -->
        <div class="row mt-5">
            <div class="col">
                <label for="">Selectionne Client</label>
                <multiselect :value="value" :options="list" label="nom_complet" v-model="client">
                    <template slot="noResult">
                        Cet utilisateur n'existe pas 
                    </template>
                </multiselect>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <small class="text-danger">{{ error }}</small>
            </div>
            
        </div>
        <!-- Boutons de Validation -->
        <div class="row mt-5">
            <div class="col text-center">
                <button class="btn btn-secondary">Annuler</button>
                <button  class="btn btn-primary" :class="client ? '' : 'disabled'" @click="passeAEtape2()">Suivant</button>
            </div>
        </div>
        

    </div>
</template>

<script>
export default {
    props: ['list'],
    data(){
        return {
            client: null,
            value: [],
            error : null
        }
    },
    watch: {
    },
    methods:{
        show(){
            console.log('dfdsfsd ')
        },
        passeAEtape2(event){
            if(this.client)
                this.$emit('passeAEtape2', this.client)
            else
                this.error = "S'il vous plaît, veuillez sélectionner un client"
        }
    },
    mounted(){
        this.list.forEach(element => {
            element.nom_complet = element.nom + ' ' + element.prenom 
        });
    },
}
</script>