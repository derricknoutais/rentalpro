
<script>
export default {
props: ["contrats"],
data() {
	return {
		fcEvents: [],
		today: new Date().toISOString().substr(0, 10),
		weekDays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		week: []
	};
	
},
computed: {
	week(){
		todayDay = this.today.getDay()
		
	}
},
mounted() {
	if (this.contrats) {
	this.contrats.forEach(contrat => {
		this.fcEvents.push({
			title: contrat.voiture.immatriculation,
			start: contrat.check_out.slice(0,10),
			end: contrat.check_in.slice(0,10),
			cssClass: "bg-warning"
		});
	});
	}
}
};
</script>
