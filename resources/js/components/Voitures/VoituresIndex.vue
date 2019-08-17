<script>

export default {
    props:['contrats'],
    data(){
        return {
            yo: 'jkdhj',
            isLoading: false,
            fcEvents: [
                
            ]
        }
    },
    methods:{
        relocateTo(id){
            window.location = '/voiture/' + id
        },
        submitForm(){
            this.isLoading = true
            document.getElementById("voitureForm").submit();
        },
        hello(){

        }
    },
    mounted(){
        console.log(this.contrats)
        
        for (let index = 0; index < this.contrats.length; index++) {
            var contrat = this.contrats[index];
            this.fcEvents.push({
                title : contrat.voiture.immatriculation,
                start : contrat.check_out,
                end : contrat.check_in,
                cssClass: 'bg-danger text-white',
                
            })
        }
    }
}
</script>