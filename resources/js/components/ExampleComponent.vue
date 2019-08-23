
<script>
export default {
props: ["contrats"],
data() {
	return {
	fcEvents: []
	};
},
mounted() {
	if (this.contrats) {
	this.contrats.forEach(contrat => {
		this.fcEvents.push({
		title: contrat.voiture.immatriculation,
		start: contrat.check_out,
		end: contrat.check_in,
		cssClass: "bg-warning"
		});
	});
	}
}
};
</script>
