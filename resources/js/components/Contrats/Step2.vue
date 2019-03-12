<template>
    <div class='container' style="height:95vh">
        <h1 class="text-center mt-5">Selectionne Voiture</h1>
        <div>
            <div class="row mt-5">
                <div class="col">Marque:</div>
                <div class="col">Type:</div>
                <div class="col">Immatriculation:</div>
            </div>
            <div class="row" v-if="voiture">
                <div class="col">{{ voiture.marque }}</div>
                <div class="col">{{ voiture.type }}</div>
                <div class="col">{{ voiture.immatriculation }}</div>
            </div>
            <div class="row mt-3">
                <div class="col">Nº Chassis</div>
                <div class="col">Année</div>
                <div class="col">Nombre Locations</div>
            </div>
            <div class="row" v-if="voiture">
                <div class="col">{{ voiture.chassis }}</div>
                <div class="col">{{ voiture.annee }}</div>
                <div class="col"></div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col">
                <label for="">Selectionne Voiture</label>
                <multiselect :value="value" :options="list" label="immatriculation" v-model="voiture">
                    <template slot="noResult">
                        Cette voiture n'existe pas 
                    </template>
                </multiselect>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col text-center">
                <button class="btn btn-secondary">Annuler</button>
                <button  class="btn btn-primary" :class="voiture ? '' : 'disabled'" @click="passeAEtape3()">Suivant</button>
            </div>
        </div>
        

    </div>
</template>

<script>
export default {
    props: ['list'],
    data(){
        return {
            voiture: null,
            value: [],
        }
    },
    methods:{
        passeAEtape3(event){
            this.$emit('passeAEtape3', this.voiture)
        }
    },
    mounted(){

    }
}
</script>