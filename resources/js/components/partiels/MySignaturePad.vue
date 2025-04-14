<template>
    <div id="app">
        <vue-signature-pad width="500px" height="500px" ref="signaturePad" />
        <div>
            <button @click="save">Save</button>
            <button @click="undo">Undo</button>
        </div>
    </div>
</template>
<script>

export default {
    name: 'my-signature-pad',

    methods: {
        undo() {
            this.$refs.signaturePad.undoSignature();
        },
        save() {
            const { isEmpty, data } = this.$refs.signaturePad.saveSignature();
            console.log(isEmpty);
            console.log(data);
        }
    }
};
</script>