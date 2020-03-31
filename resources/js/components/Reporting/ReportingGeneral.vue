
<script>
import { GChart } from 'vue-google-charts'

export default {
    components: {
      'gchart' : GChart
    },
    props:['contrats'],
    data(){
        return {
            localContrats: null,
            reporting_hebdomadaire: {
                show: false,
                nombre_locations: [],
                nombre_locations_options: {},
                revenus: [],
                revenus_options : {}
            },
            reporting_mensuel: {
                show: false,
                nombre_locations: [],
                nombre_locations_options: {},
                revenus: [],
                revenus_options : {}
            },
            reporting_annuel: {
                show: false,
                nombre_locations: [],
                nombre_locations_options: {},
                revenus: [],
                revenus_options : {}
            },

            chartOptions: null
        }
    },
    methods:{
        selectMonthlyContracts(){
            // Instancie une nouvelle dta
            var date = new Date();

            this.localContrats = this.contrats.filter(contrat => {
                return Date.parse(contrat.created_at) > Date.parse(new Date(date.getFullYear(), date.getMonth(), 1))
            })

        },
        selectWeeklyContracts(){
            this.reporting_annuel.show = false;
            this.reporting_mensuel.show = false;
            // Instancier un nouvelle date avec la date du jour
            var today = new Date();

            // Déterminer la date du 1er (Lundi) et 7eme (Dimanche) jour de la semaine
            var firstDayOfWeek = today.getDate() - today.getDay() + 1
            var lastDayOfWeek  = firstDayOfWeek + 6

            // Instancier les dates correspondants au 1er et Dernier Jour de la semaine
            var firstDateOfWeek = new Date( today.setDate(firstDayOfWeek) )
            var lastDateOfWeek = new Date( today.setDate(lastDayOfWeek) )

            // On parse ces dates
            var firstDateParsed = Date.parse( firstDateOfWeek )
            var lastDateParsed = Date.parse( lastDateOfWeek )

            console.log('First Day: ' + firstDayOfWeek)

            // On retourne les contrats ayant été créés pendant la semaine actuelle
            this.localContrats = this.contrats.filter( contrat => {
                var contractDateParsed = Date.parse(contrat.created_at)
                return (  contractDateParsed > firstDateParsed && contractDateParsed < lastDateParsed )
            });
            console.log('Date : ' + (new Date(Date.parse(this.localContrats[1].created_at))).getDate() );


            // Je crée un tableau contenant les 7 jours de la semaine
            var contrats_classés_par_jours = new Array();
            for(let i = 0; i < 7; i++){
                contrats_classés_par_jours[i] = new Array();
            }
            this.localContrats.forEach( contrat => {
                var date = (new Date(Date.parse(contrat.created_at))).getDate()
                var index = date - firstDayOfWeek
                contrats_classés_par_jours[index].push(contrat)
            });

            var nombre_locations = [['Jour', 'Nombre De Contrats']];
            for (let index = 1; index < 8; index++) {
                nombre_locations[index] = new Array()
            }
            var jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']
            contrats_classés_par_jours.forEach( (jour, index) => {
                nombre_locations[index + 1] = [ jours[index], jour.length ]
            })
            this.reporting_hebdomadaire.nombre_locations = nombre_locations

            this.reporting_hebdomadaire.nombre_locations_options = {
                title: 'Performance Locations ',
                subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                height: 600,
            }
            this.reporting_hebdomadaire.show = true
        },
        selectYearlyContracts(){
            this.reporting_hebdomadaire.show = false;
            this.reporting_mensuel.show = false;
            // Instancie une nouvelle date
            var date = new Date();

            // Retourne les contrats de l'année
            this.localContrats = this.contrats.filter( contrat => {
                return Date.parse(contrat.created_at) > Date.parse(new Date( date.getFullYear(), 0, 1))
            })
            // Instancie un nouveau tableau
            var contract_per_month = []
            // Instancie 12 array
            for (let index = 0; index < 12; index++) {
                contract_per_month[index] = new Array()
            }

            // Classe chaque contrat par mois dans contract_per_months
            this.localContrats.forEach( (contract) => {
                var month_index = new Date( contract.created_at ).getMonth()
                contract_per_month[month_index].push(contract)
            });

            // Crée des tableaux contenant le mois et le nombre de contrat par mois
            var nombre_locations = [ ['Mois', 'Nombre de Locations']]
            var mois = new Array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
            for (let index = 0; index < contract_per_month.length; index++) {
                nombre_locations[index + 1] = [
                    mois[index] , contract_per_month[index].length
                ]
            }
            this.reporting_annuel.nombre_locations = nombre_locations
            this.reporting_annuel.nombre_locations_options = {
                title: 'Performance Locations ' + date.getFullYear(),
                subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                height: 600,
            }
            this.reporting_annuel.revenus_options = {
                title: 'Revenus Locations ' + date.getFullYear(),
                height: 600,
                colors: ['#ffcc00']
            }

            var revenus_locations = [ ['Mois', 'Revenus Location'] ]
            for (let index = 0; index < contract_per_month.length; index++) {
                var total = 0;
                contract_per_month[index].forEach( contrat => {
                    console.log(contrat.total)
                    total += contrat.total
                })
                revenus_locations.push([ mois[index], total ])
            }
            this.reporting_annuel.revenus = revenus_locations

            this.reporting_annuel.show = true
        }
    },
    mounted(){

    }
}
</script>
