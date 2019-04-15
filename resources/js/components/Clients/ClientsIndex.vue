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
            }
        }
    },
    methods:{
        relocateTo(location){
            window.location = location
        },
        enregistreClientDansCashier(){
            axios.post('https://thecashier.ga/api/client/nouveau', this.client).then(response => {
                console.log(response.data);
                this.client.cashier_id = response.data.id
                document.getElementById("cashier_id").value = response.data.id
                this.$forceUpdate()
                document.getElementById('clientForm').submit();
            }).catch(error => {
                console.log(error);
            });


            

            
        }
    },
    mounted(){

    }
}
</script>