<template>
    <div class='container my-5' style="height:95vh">
        <h1 class="text-center mt-5">Accessoires & Documents</h1>
        <div class="row mt-5">
            <div class="col-6">
                <h3>Documents</h3>
                <div class="row mt-5" v-for="document in voiture.documents">
                    <div class="col-4">
                        {{ document.type }}
                    </div>
                    <div class="col">
                        <div class="form-check form-check-inline ml-5">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" :name="document.type" disabled checked> Oui
                                <input class="form-check-input ml-3" type="radio" :name="document.type" disabled value="checkedValue"> Non
                            </label>
                        </div>
                    </div>
                </div>
                <!-- <div class="row mt-4">
                    <div class="col-4">
                        Assurance:
                    </div>
                    <div class="col">
                        <div class="form-check form-check-inline ml-5">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="carte_grise" id="" value="checkedValue"> Oui
                                <input class="form-check-input ml-3" type="radio" name="carte_grise" id="" value="checkedValue"> Non
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-4">
                        Visite Technique:
                    </div>
                    <div class="col">
                        <div class="form-check form-check-inline ml-5">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="carte_grise" id="" value="checkedValue"> Oui
                                <input class="form-check-input ml-3" type="radio" name="carte_grise" id="" value="checkedValue"> Non
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-4">
                        Carte Extincteur:
                    </div>
                    <div class="col">
                        <div class="form-check form-check-inline ml-5">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="carte_grise" id="" > Oui
                                <input class="form-check-input ml-3" type="radio" name="carte_grise" id="" value="checkedValue"> Non
                            </label>
                        </div>
                    </div>
                </div> -->
                
            </div>
            <div class="col-6">
                <h3>Acessoires</h3>
                <div class="row mt-5" v-for="accessoire in voiture.accessoires">
                    <div class="col-4">
                        {{ accessoire.type }}
                    </div>
                    <div class="col">
                        <div class="form-check form-check-inline ml-5">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" :name="accessoire.type" value="checkedValue" disabled :checked="accessoire.pivot.quantité === 0"> 0
                                <input class="form-check-input ml-3" type="radio" :name="accessoire.type" disabled :checked="accessoire.pivot.quantité === 1"> 1
                                <input class="form-check-input ml-3" type="radio" :name="accessoire.type" disabled :checked="accessoire.pivot.quantité === 2"> 2
                                <input class="form-check-input ml-3" type="radio" :name="accessoire.type" disabled :checked="accessoire.pivot.quantité === 3"> 3
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col text-center">
                <a class="btn btn-secondary" href="/contrats/menu">Annuler</a>
                <button class="btn btn-secondary" @click="decrementStep()">Retour</button>
                <button  class="btn btn-primary" :class="(autres.accessoires && autres.documents) ? '' : 'disabled'" @click="passeAEtape6()">Suivant</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['voiture'],
    data(){
        return {
            autres: {
                accessoires: this.voiture.accessoires,
                documents: this.voiture.documents
            }
        }
    },
    methods:{
        decrementStep(event){
            this.$emit('decrementStep', this.voiture)
        },
        passeAEtape6(event){
            this.$emit('passeAEtape6')
        }
    },
    mounted(){

    }
}
</script>