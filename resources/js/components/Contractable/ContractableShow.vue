<script>
export default {
    props: [
        'contractable_prop',
        'pannes_prop',
        'contrats_prop'
    ],
    data() {
        return {
            display_formulaire_panne: false,
            pannes: this.pannes_prop,
            form: {
                description: '',
                contractable_id: null
            },
            contractable: this.contractable_prop,
            contrats: this.contrats_prop

        }
    },
    methods: {
        toggleFormulairePanne() {
            this.display_formulaire_panne = !this.display_formulaire_panne
        },
        submitFormulairePannes() {
            axios.post('/pannes', this.form).then((response) => {
                console.log(response.data)
                this.pannes.push(response.data)
                this.$forceUpdate()
            });
        }
    },
    mounted() {
        this.form.contractable_id = this.contractable_prop.id
    }
}
</script>
