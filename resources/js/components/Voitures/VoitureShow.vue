
<script>
export default {
	props: ['voiture'],
    data(){
    return {

    }
  },
  methods:{
    receptionnerVoiture(){
		axios.get('/voiture/' + this.voiture.id + '/reception').then( response => {

		}).catch(error => {

		});
	},
	envoyerEnMaintenance(){
		axios.get('/voiture/' + this.voiture.id + '/maintenance').then(response => {

		}).catch(error => {
			console.log(error);
		});
	}
  },
  mounted(){

  }
}
</script>