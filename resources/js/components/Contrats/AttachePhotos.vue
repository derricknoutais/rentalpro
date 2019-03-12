<template>
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label>Coté Droit</label>
                            <input type="file" ref="droit" @change="handleDroit()">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>Coté Gauche</label>
                            <input type="file" ref="gauche" @change="handleGauche()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Arrière Voiture</label>
                            <input type="file" ref="arriere" @change="handleArriere()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Avant Voiture</label>
                            <input type="file" ref="avant" @change="handleAvant()"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="enregistrerPhotos">Enregistrer Photos</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:['contrat'],
    data(){
        
        return {
            file: {
                avant: null,
                arriere: null,
                droit: null,
                gauche: null
            }
        }
    },
    methods:{
        handleAvant(){
          this.file.avant = this.$refs.avant.files[0];
        },
        handleArriere(){
          this.file.arriere = this.$refs.arriere.files[0];
        },
        handleDroit(){
          this.file.droit = this.$refs.droit.files[0];
        },
        handleGauche(){
          this.file.gauche = this.$refs.gauche.files[0];
        },
        enregistrerPhotos(){
            let formData = new FormData();
            formData.append('droit', this.file.droit);
            formData.append('avant', this.file.avant);
            formData.append('arriere', this.file.arriere);
            formData.append('gauche', this.file.gauche);
            axios.post( '/contrats/' + this.contrat.id +'/ajoute-photos',
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
        }
        
    },
    mounted(){

    }
}
</script>