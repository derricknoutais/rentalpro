<template>
    <div>
        <div class="border-top border-bottom">
            <vue-signature-pad width="100%" height="100%" ref="signaturePad" />
        </div>
        <div class="flex w-full">
            <button class="w-1/2 border bg-gray-300" type="button" @click="save">

                Save
                <span v-if="isLoading" class="fa fa-spinner fa-spin"></span>
            </button>
            <button class="w-1/2 border bg-gray-300" type="button" @click="undo">Undo</button>
        </div>
    </div>
</template>
<script>

export default {
    props: ['contrat_id'],
    name: 'my-signature-pad',
    data() {
        return {
            isLoading: false,
        };
    },

    methods: {
        undo() {
            this.$refs.signaturePad.undoSignature();
        },
        save() {
            const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
            console.log(isEmpty);
            console.log(data);
            this.isLoading = true;
            axios.post('/contrat/' + this.contrat_id + '/store-signature', {
                signature: data,
                contrat_id: this.contrat_id
            }).then(response => {
                console.log(response.data);
                this.isLoading = false;
                this.$forceUpdate();
                if (response.data.status === 'success') {
                    window.location.href = '/contrat/' + this.contrat_id + '/print';
                } else {
                    alert('Error: ' + response.data.message);
                }
            }).catch(error => {
                console.error('Error during signature save:', error);
                this.isLoading = false;
                alert('An error occurred while saving the signature.');
            });
        }
    }
};
</script>