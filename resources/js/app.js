
require('./bootstrap');

window.Vue = require('vue');

window.writtenNumber = require('written-number');
writtenNumber.defaults.lang = 'fr';

import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter, {
    symbol : 'F CFA',
    thousandsSeparator: '.',
    fractionCount: 0,
    fractionSeparator: ',',
    symbolPosition: 'back',
    symbolSpacing: true
});

const moment = require('moment')
require('moment/locale/fr')

Vue.use(require('vue-moment'), {
    moment
})

import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect)


const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


const app = new Vue({
    el: '#app',
    data: {
        test: false
    },
    methods: {
        relocateTo(location){
            window.location = location
        },
        envoieACashier(payload){
            console.log(payload)
            var data;
            data = {
                'objet': 'Location ' + payload.voiture.marque + ' ' + payload.voiture.type + ' ' + payload.voiture.immatriculation,
                'échéance' : payload.check_in,
                'quantité' : payload.nombre_jours,
                'description' : 'Jours',
                'prix_unitaire' : payload.prix_journalier,
                'client': payload.client.cashier_id 
            };

            axios.post('https://thecashier.ga/api/facture', data).then(response => {
                var cashier_id = response.data.id
                this.cashier_id = cashier_id       
                if( cashier_id !== null ){
                    axios.post('/contrat/' + payload.id + '/update-cashier-id', {cashier_id: this.cashier_id}).then( response => {
                        window.location.reload()
                    }).catch(error => {
                        console.log(error);
                    });
                }   
            }).catch(error => {
                console.log(error);
            });
        },
    },
    mounted() {
            this.test = true
        
    },
    
});
