<template>
    <file-pond class=""
        :name="fieldName"
        ref="pond"
        :label-idle="label"
        captureMethod="camera"
        :allow-image-preview="true"
        accepted-file-types="image/*"
        :allow-image-crop="true"
        :allow-multiple="multiple"
        :server="serverOptions"
        @init="filepondInitialized"
        @processfile="handleProcessedFile"
        @removefile="handleRemovedFile"></file-pond>
</template>
<script>
import vueFilePond, { setOptions } from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';

setOptions({
    credits: false,
});

const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview, FilePondPluginImageCrop);

export default {
    props: {
        ref_prop: {
            type: [Number, String],
            default: null,
        },
        client_name: {
            type: String,
            default: null,
        },
        label: {
            type: String,
            default: 'Glissez-déposez vos fichiers ou cliquez',
        },
        folder: {
            type: String,
            default: 'permis',
        },
        multiple: {
            type: Boolean,
            default: false,
        },
        uploadUrl: {
            type: String,
            default: '/image',
        },
        fieldName: {
            type: String,
            default: 'clientId',
        },
    },

    components: {
        FilePond
    },

    data() {
        return {
            processedFiles: {},
        };
    },

    computed: {
        serverOptions() {
            return {
                process: {
                    url: this.uploadUrl,
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                    },
                    ondata: (formData) => {
                        if (this.folder) {
                            formData.append('folder', this.folder);
                        }
                        return formData;
                    }
                }
            };
        }
    },

    methods: {

        filepondInitialized() {},
        handleProcessedFile(error, file) {
            if (error) {
                console.log(error);
                return;
            }
            this.processedFiles[file.id] = file.serverId;
            this.$emit('file-processed', file.serverId);
        },
        handleRemovedFile(error, file) {
            if (error) {
                console.log(error);
                return;
            }
            const serverId = this.processedFiles[file.id] || file.serverId;
            if (serverId) {
                this.$emit('file-removed', serverId);
                delete this.processedFiles[file.id];
            }
        }
    }

}

</script>
