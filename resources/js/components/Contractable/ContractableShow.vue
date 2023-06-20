<script>
    export default {
        props : [
            'contractable_props',
            'pannes_props'
        ],
        data(){
            return {
                display_formulaire_panne : false,
                pannes : this.pannes_props,
                form : {
                    description : '',
                    contractable_id : null
                }

            }
        },
        methods : {
            toggleFormulairePanne(){
                this.display_formulaire_panne = ! this.display_formulaire_panne
            },
            submitFormulairePannes(){
                axios.post('/pannes', this.form ).then( (response) => {
                    console.log(response.data)
                    this.pannes.push(response.data)
                    this.$forceUpdate()
                });
            }
        },
        mounted(){
            this.form.contractable_id = this.contractable_props.id
        }
    }
</script>
