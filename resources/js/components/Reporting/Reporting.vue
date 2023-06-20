
<script>
export default {
    components: {
    },
    props:['contrats', 'reporting_props'],
    events: ['hide-modal'],
    data(){
        return {
            displayModalData : false
            // metrics : JSON.parse(JSON.stringify(this.reporting_props)),
            // report : 5
        }
    },
    methods:{
        display(check){
            console.log(check)
            var found = this.metrics.filter( metric => {
                return metric.checksum === check
            })
            // if(found.length > 0 )
            //     this.report = found[0].chiffre_affaires
            console.log(this.metrics)
            console.log(found)
            this.$forceUpdate()
        },
        displayModal(){
            this.displayModalData = true
            this.$forceUpdate()
        },
        hideModal(){
            this.displayModalData = false
            this.$forceUpdate()
        }
    }
}
</script>
