<template>
    <div class='container' style="height:95vh">
        <h1 class="text-center mt-5">Selectionne Dates</h1>
        <div class="row mt-5">
            <div class="col">
                <label>Dates du</label>
            </div>
            <div class="col">
                <label>Dates au</label>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col">
                <input type="date" class="form-control" v-model="dates.check_out"/>
            </div>
            <div class="col">
                <input type="date" class="form-control" v-model="dates.check_in"/>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col text-center">
                <a class="btn btn-secondary" href="/contrats/menu">Annuler</a>
                <button class="btn btn-secondary" @click="decrementStep()">Retour</button>
                <button  class="btn btn-primary" :class="(dates.check_out && dates.check_in) ? '' : 'disabled'" @click="passeAEtape4()">Suivant</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['list'],
    data(){
        return {
            dates: {
                check_out: null,
                check_in: null
            },
            value: [],
        }
    },
    methods:{
        decrementStep(event){
            this.$emit('decrementStep', this.voiture)
        },
        passeAEtape4(event){
            this.$emit('passeAEtape4', this.dates)
        }
    },
    mounted(){

    }
}
</script>