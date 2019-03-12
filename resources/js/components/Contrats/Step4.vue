<template>
    <div class='container' style="height:95vh">
        <h1 class="text-center mt-5">Montants</h1>
        <div class="row mt-5">
            <div class="col-6 offset-3">
                <label>Prix Journalier</label>
                <input type="number" class="form-control" v-model.number="montants.prix_journalier"/>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-6 offset-3">
                <label>Caution</label>
                <input type="number" class="form-control" v-model.number="montants.caution"/>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col text-center">
                <button class="btn btn-secondary">Annuler</button>
                <button  class="btn btn-primary" :class="(montants.prix_journalier && montants.caution) ? '' : 'disabled'" @click="passeAEtape5()">Suivant</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['voiture'],
    data(){
        return {
            montants: {
                prix_journalier: this.voiture.prix,
                caution: null,
            }   
        }
    },
    methods:{
        passeAEtape5(event){
            this.$emit('passeAEtape5', this.montants)
        }
    },
    mounted(){

    }
}
</script>