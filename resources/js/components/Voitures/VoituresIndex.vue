<script>

export default {
    props:['contrats'],
    data(){
        return {
            yo: 'jkdhj',
            isLoading: false,
            fcEvents: [
                {
                  title : 'FZ-885-AA',
                  start : '2019-08-25',
                  end : '2019-08-27',
                  cssClass: 'bg-danger text-white'
                },
                {
                  title : 'EZ-442-AA',
                  start : '2019-08-28',
                  end : '2019-08-30',
                  cssClass: 'bg-success text-white'
                },
                {
                  title : 'EZ-443-AA',
                  start : '2019-08-23',
                  end : '2019-08-30',
                  cssClass: 'bg-warning'
                },

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
                start : contrat.au,
                end : contrat.du,
                cssClass: 'bg-danger text-white',

            })
        }
    }
}
</script>
