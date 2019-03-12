<template>
    <div class="modal  fade" id="receptionvehicule" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div v-if="this.step === 1">
                <div class="modal-header">
                    <h5 class="modal-title">Controle Documents & Accessoires</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class="col-6">
                            <h3>Documents</h3>

                            <div class="row mt-5">
                                <div class="col-4">
                                    Carte Grise
                                    <i class="fa fa-check text-success" v-if="documents.carte_grise && realDocuments.carte_grise"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col-8">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="cg" v-model.number="documents.carte_grise" value=1> Oui
                                            <input class="form-check-input ml-3" type="radio" name="cg" v-model.number="documents.carte_grise" value=0> Non
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-4">
                                    Visite Technique
                                    <i class="fa fa-check text-success" v-if="documents.visite_technique && realDocuments.visite_technique"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col-8">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="vt" v-model.number="documents.visite_technique" value=1> Oui
                                            <input class="form-check-input ml-3" type="radio" name="vt" v-model.number="documents.visite_technique" value=0> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-4">
                                    Assurance
                                    <i class="fa fa-check text-success" v-if="documents.assurance && realDocuments.assurance"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col-8">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="ass" v-model.number="documents.assurance" value=1> Oui
                                            <input class="form-check-input ml-3" type="radio" name="ass" v-model.number="documents.assurance" value=0> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-4">
                                    Carte Extincteur
                                    <i class="fa fa-check text-success" v-if="documents.carte_extincteur && realDocuments.carte_extincteur"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col-8">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="cex" v-model.number="documents.carte_extincteur" value=1> Oui
                                            <input class="form-check-input ml-3" type="radio" name="cex" v-model.number="documents.carte_extincteur" value=0> Non
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-6">
                            <h3>Acessoires</h3>
                            <!-- <div class="row mt-5" v-for="(accessoire, index) in voiture.accessoires">
                                <div class="col-4">
                                    {{ accessoire.type }}
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" :name="accessoire.type" v-model="documents[index]" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" :name="accessoire.type" v-model="documents[index]" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" :name="accessoire.type"  v-model="documents[index]" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Crick -->
                            <div class="row mt-5">
                                <div class="col-4">
                                    Crick
                                    <i class="fa fa-check text-success" v-if="accessoires.crick === realAccessoires.crick"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="crick" v-model.number="accessoires.crick" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" name="crick" v-model.number="accessoires.crick" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" name="crick" v-model.number="accessoires.crick" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Triangle -->
                            <div class="row mt-5">
                                <div class="col-4">
                                    Triangle
                                    <i class="fa fa-check text-success" v-if="accessoires.triangle === realAccessoires.triangle"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="triangle" v-model.number="accessoires.triangle" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" name="triangle" v-model.number="accessoires.triangle" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" name="triangle" v-model.number="accessoires.triangle" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Manivelle -->
                            <div class="row mt-5">
                                <div class="col-4">
                                    Manivelle
                                    <i class="fa fa-check text-success" v-if="accessoires.manivelle === realAccessoires.manivelle"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="manivelle" v-model.number="accessoires.manivelle" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" name="manivelle" v-model.number="accessoires.manivelle" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" name="manivelle" v-model.number="accessoires.manivelle" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Calle Métallique -->
                            <div class="row mt-5">
                                <div class="col-4">
                                    Calle Métallique
                                    <i class="fa fa-check text-success" v-if="accessoires.calle_metallique === realAccessoires.calle_metallique"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="calle_metallique" v-model.number="accessoires.calle_metallique" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" name="calle_metallique" v-model.number="accessoires.calle_metallique" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" name="calle_metallique" v-model.number="accessoires.calle_metallique" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Pneu Secours -->
                            <div class="row mt-5">
                                <div class="col-4">
                                    Pneu Secours
                                    <i class="fa fa-check text-success" v-if="accessoires.pneu_secours === realAccessoires.pneu_secours"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="pneu_secours" v-model.number="accessoires.pneu_secours" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" name="pneu_secours" v-model.number="accessoires.pneu_secours" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" name="pneu_secours" v-model.number="accessoires.pneu_secours" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Gilet -->
                            <div class="row mt-5">
                                <div class="col-4">
                                    Gilet
                                    <i class="fa fa-check text-success" v-if="accessoires.gilet === realAccessoires.gilet"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gilet" v-model.number="accessoires.gilet" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" name="gilet" v-model.number="accessoires.gilet" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" name="gilet" v-model.number="accessoires.gilet" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Extincteur -->
                            <div class="row mt-5">
                                <div class="col-4">
                                    Extincteur
                                    <i class="fa fa-check text-success" v-if="accessoires.extincteur === realAccessoires.extincteur"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="extincteur" v-model.number="accessoires.extincteur" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" name="extincteur" v-model.number="accessoires.extincteur" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" name="extincteur" v-model.number="accessoires.extincteur" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Trousse Secours -->
                            <div class="row mt-5">
                                <div class="col-4">
                                    Trousse Secours
                                    <i class="fa fa-check text-success" v-if="accessoires.trousse_secours === realAccessoires.trousse_secours"></i>
                                    <i class="fas fa-times text-danger" v-else></i>
                                </div>
                                <div class="col">
                                    <div class="form-check form-check-inline ml-5">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="trousse_secours" v-model.number="accessoires.trousse_secours" value=0> 0
                                            <input class="form-check-input ml-3" type="radio" name="trousse_secours" v-model.number="accessoires.trousse_secours" value=1> 1
                                            <input class="form-check-input ml-3" type="radio" name="trousse_secours" v-model.number="accessoires.trousse_secours" value=2> 2
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="stepUp()">Suivant</button>
                </div>
            </div>
            <div v-if="this.step === 2">
                <div class="modal-header">
                    <h5 class="modal-title">Niveau Carburant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='row'>
                        <div class="col-12 text-center">
                            <div class="form-check form-check-inline my-3">
                                <label class="form-check-label">
                                    <input class="form-check-input ml-5" type="radio" name="cg" v-model="carburant" value="0"> 0
                                    <input class="form-check-input ml-5" type="radio" name="cg" v-model="carburant" value="1/4"> 1/4
                                    <input class="form-check-input ml-5" type="radio" name="cg" v-model="carburant" value="1/2"> 1/2
                                    <input class="form-check-input ml-5" type="radio" name="cg" v-model="carburant" value="3/4"> 3/4
                                    <input class="form-check-input ml-5" type="radio" name="cg" v-model="carburant" value="1/1"> 1/1
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="stepDown()">Précedent</button>
                    <button type="button" class="btn btn-primary" @click="stepUp()">Suivant</button>
                </div>
            </div>
            <div v-if="this.step === 3">
                <div class="modal-header">
                    <h5 class="modal-title">Caution</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class='row'>
                        <div class="col-6 offset-3 text-center mt-3">
                            <div class="form-group">
                              <label for="">Caution Versée</label>
                              <input type="text" class="form-control" :value="this.contrat.caution" disabled>
                            </div>
                        </div>
                        <div class="col-6 offset-3 text-center mt-3">
                            <div class="form-group">
                              <label for="">Caution Retournée</label>
                              <input type="text" class="form-control" v-model.number="caution_retournée">
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="this.différence_caution && this.différence_caution > 0 ">
                        <div class="col">
                            <p class="text-danger" >Remboursement : {{ this.différence_caution }} F CFA</p>
                        </div>
                    </div>
                    <div class="row" v-if="this.différence_caution && this.différence_caution < 0 ">
                        <div class="col">
                            <p class="text-danger" >La caution remboursée ne peut être supérieure à la caution versée</p>
                        </div>
                    </div>
                    <div class="row mt-3" v-if="this.différence_caution && this.différence_caution > 0 ">
                        <div class="col">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="" id="" value="checkedValue" >
                                Défaut Retard Retour
                              </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="" id="" value="Value" >
                                Défaut État Véhicule
                              </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="" id="" value="Value" >
                                Défaut Carburant
                              </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="" id="" value="Value" >
                                Défaut Accessoire
                              </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="" id="" value="Value">
                                Défaut Document
                              </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="stepDown()">Précedent</button>
                    <button type="button" class="btn btn-primary" @click="receptionnerVoiture()">Réceptionner le véhicule</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: ['voiture', 'contrat'],
    data(){
        return {
            accessoires: {
                crick: null,
                triangle: null,
                manivelle: null,
                calle_metallique: null,
                pneu_secours: null,
                gilet: null,
                extincteur: null,
                trousse_secours: null,
            },
            documents: {
                carte_grise: null,
                visite_technique: null,
                assurance: null,
                carte_extincteur: null,
            },
            caution_retournée: null, 
            carburant: null,
            realDocuments: {
                carte_grise: false,
                visite_technique: false,
                assurance: false,
                carte_extincteur: false,
            },
            
            realAccessoires : {
                crick: false,
                triangle: false,
                manivelle: false,
                calle_metallique: false,
                pneu_secours: false,
                gilet: false,
                extincteur: false,
                trousse_secours: false,
            },
            step : 1
        }
    },
    computed: {
        hasCarteGrise(){
            flag = false
        },
        différence_caution(){
            if(this.caution_retournée){
                return this.contrat.caution - this.caution_retournée
            }
        },
        documentString(){
            let binary = ''
            for(var property in this.documents){
                if(this.documents[property] !== null ){
                    binary += this.documents[property]
                }
            }
            return binary
        },
        accessoireString(){
            let binary = ''
            for(var property in this.accessoires){
                if(this.accessoires[property] !== null){
                    binary += this.accessoires[property]
                }
            }
            return binary;
        }
    },
    methods:{
        stepUp(){
            this.step += 1
        },
        stepDown(){
            this.step -= 1
        },
        receptionnerVoiture(){
            axios.post('/voiture/reception',{
                accessoires: this.accessoireString,
                documents : this.documentString,
                caution_retournée : this.caution_retournée,
                carburant: this.carburant,
                voiture: this.voiture
            } ).then(response => {

            }).catch(error => {
            });
        },
        
    },
    mounted(){
        this.voiture.documents.forEach( document => {
            if( document.type === "Carte Grise")
            {
                this.realDocuments.carte_grise = true
            }
            if( document.type === "Visite Technique")
            {
                this.realDocuments.visite_technique = true
            }
            if( document.type === "Assurance")
            {
                this.realDocuments.assurance = true
            }
            if( document.type === "Carte Extincteur")
            {
                this.realDocuments.carte_extincteur = true
            }
        });
        this.voiture.accessoires.forEach( accessoire => {
            if( accessoire.type === "Crick")
            {
                this.realAccessoires.crick = accessoire.pivot.quantité
            }
            if( accessoire.type === "Triangle")
            {
                this.realAccessoires.triangle = accessoire.pivot.quantité
            }
            if( accessoire.type === "Manivelle")
            {
                this.realAccessoires.manivelle = accessoire.pivot.quantité
            }
            if( accessoire.type === "Calle Métallique")
            {
                this.realAccessoires.calle_metallique = accessoire.pivot.quantité
            }
            if( accessoire.type === "Pneu Secours")
            {
                this.realAccessoires.pneu_secours = accessoire.pivot.quantité
            }
            if( accessoire.type === "Extincteur")
            {
                this.realAccessoires.extincteur = accessoire.pivot.quantité
            }
            if( accessoire.type === "Gilet")
            {
                this.realAccessoires.gilet = accessoire.pivot.quantité
            }
            if( accessoire.type === "Trousse Secours")
            {
                this.realAccessoires.trousse_secours = accessoire.pivot.quantité
            }
        });
    }
}
</script>