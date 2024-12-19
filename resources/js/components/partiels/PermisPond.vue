<template>
    <file-pond class="" name="clientId" ref="clientId" :label-idle="label" captureMethod="camera"
        allowImagePreview="true" accepted-file-types="image/*" @init="filepondInitialized" allowImageCrop="true"
        @processfile="handleProcessedFile"></file-pond>
</template>
<script>
import vueFilePond, { setOptions } from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import { ref } from 'vue';


setOptions({
    server: {
        process: {

            url: '../../image',
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        }
    }
})

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview, FilePondPluginImageCrop);

export default {
    props: ["ref_prop", "client_name", 'label'],

    data() {
        return {
            image_id: null
        }
    },

    components: {
        FilePond
    },

    methods: {

        filepondInitialized() {
            console.log(this.$refs.createPond)
        },
        handleProcessedFile(error, file) {
            if (error) {
                console.log(error)
                return;
            }
            console.log(file)
            this.$emit('file-processed', file.serverId)
        }
    },

    mounted() {
        this.image_id = this.ref_prop
    }

}

</script>