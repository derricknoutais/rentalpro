<script>
export default {
    data(){
        return {
            client: {
                nom: '',
                prenom: '',
                numero_telephone: '',
                numero_telephone2: '',
                numero_telephone3: '',
                numero_permis: '',
                mail: '',
                ville: '',
                addresse: '',
                cashier_id: ''
            },
            isLoading: false
        }
    },
    methods:{
        relocateTo(location){
            window.location = location
        },
        enregistreClientDansCashier(){
            this.isLoading = true;
            this.$forceUpdate()
            axios.post('https://thecashier.ga/api/client/nouveau', this.client).then(response => {
                
                console.log(response.data);
                this.client.cashier_id = response.data.id
                document.getElementById("cashier_id").value = response.data.id
                this.$forceUpdate()
                document.getElementById('clientForm').submit();
            }).catch(error => {
                alert("Une erreur est survenue. Réessayez ou contactez l'équipe de maintenance")
                window.location.reload()
            });


            

            
        }
    },
    mounted(){

    }
}
</script>