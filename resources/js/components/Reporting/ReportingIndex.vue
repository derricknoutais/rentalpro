
<script>
import { GChart } from 'vue-google-charts'

export default {
    components: {
      'gchart' : GChart
    },
    props: ['voitures', 'chiffre'],
    data(){
        return {
            voiture_selectionée: this.voitures[0],
            dates: null,
            chartData: [
              ['Mois', "Chiffre d'Affaires"]
            ],
            chartOptions: {
              chart: {
                title: 'Company Performance',
                subtitle: 'Sales, Expenses, and Profit: 2014-2017',
              }
            }
        }
    },
    watch : {
        voiture_selectionée(){
            this.chart()
        }
    },
    methods:{
        // Location
        joursDeLocation(){
            var nombreJours = 0
            if(this.voiture_selectionée){
                this.voiture_selectionée.contrats.forEach( contrat => {
                    nombreJours += contrat.nombre_jours
                });
            }
            return nombreJours;
        },
        prixMoyenDeLocation(){
            var moyenne = 0;
            var prixTotalLocation = 0;
            if(this.voiture_selectionée){
                this.voiture_selectionée.contrats.forEach( contrat => {
                    prixTotalLocation += contrat.total
                });
            }
            return Math.ceil( (prixTotalLocation / this.joursDeLocation()) )
        },
        chiffreDAffaires(){
            var total = 0;
            if(this.voiture_selectionée){
                this.voiture_selectionée.contrats.forEach( contrat => {
                    total += contrat.total
                });
            }
            return total;
        },

        // Maintenance

        coûtDeMaintenance(){
            var total = 0;
            if(this.voiture_selectionée){
                this.voiture_selectionée.maintenances.forEach( maintenance => {
                    total += maintenance.coût
                });
            }
            return total;
        },

        // Analyse 
        seuilDeRentabilité(){
            var seuil = 0;
            if(this.voiture_selectionée){
                seuil = this.voiture_selectionée.douane + 
                    this.voiture_selectionée.prix_achat + 
                    this.voiture_selectionée.transport + this.coûtDeMaintenance()

            }
            return seuil;
        },
        coutsVariables(){
            return this.coûtDeMaintenance();
        }, 
        marge(){
            return this.chiffreDAffaires() - this.coutsVariables()
        },
        pointMort(){
            return Math.ceil( (this.seuilDeRentabilité() - this.chiffreDAffaires() ) / this.prixMoyenDeLocation())
        },
        chart(){
            // var date = new Date();

            // this.dates = this.voiture_selectionée.contrats.filter( contrat => {
            //     return Date.parse(contrat.created_at) > new Date(date.getFullYear(), date.getMonth(), 1)
                
            // })

            
            
        }

    },
    mounted(){
        var months = ['January','February','March','April','May','June','July','August','September','October','November','December']

        months.forEach( (month, index) => {
            this.chartData.push(
                [
                    month, this.chiffre[index]
                ]
            )
        })
    }
}
</script>