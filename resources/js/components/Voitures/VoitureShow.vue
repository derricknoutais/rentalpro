
<script>
export default {
	props: ["voiture"],
	data() {
		return {
			nombrePannes: 1,
			technicien: null,
			timer : 0,
			interval: null
		};
	},
	methods: {
		incrementNombrePannes(){
			this.nombrePannes += 1
		},
		receptionnerVoiture() {
			axios.get("/voiture/" + this.voiture.id + "/reception").then(response => {
			}).catch(error => {});
		},
		envoyerEnMaintenance() {
			axios.get("/voiture/" + this.voiture.id + "/maintenance").then(response => {

			}).catch(error => {
				console.log(error);
			});
		},
		decrementTimer(){
			this.interval = setInterval(() => {
				if(this.timer > 0){
					this.timer -= 1;
				} else {
					clearInterval(this.interval)
					$('#pasDePannes').modal('hide');
				}
			}, 1000);
		},
		toggleMaintenanceModal(voiture){
			if(voiture.pannes.length === 0){
				$('#pasDePannes').modal('show');
				this.timer = 3; 
				this.decrementTimer();
				$('#panneSignalisation').modal('show');
			} else {
				$('#envoieEnMaintenance').modal('show');
			}
		}
	},
  	mounted() {}
};
</script>