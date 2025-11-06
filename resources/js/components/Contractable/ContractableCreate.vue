<script>
export default {
    data() {
        return {
            form: {
                immatriculation: '',
                marque: '',
                type: '',
                numero_chassis: '',
                annee: '',
                prix: '',
                images: []
            },
            isSubmitting: false,
        };
    },
    methods: {
        addImage(id) {
            const numericId = Number(id);
            if (!Number.isNaN(numericId) && !this.form.images.includes(numericId)) {
                this.form.images.push(numericId);
            }
        },
        removeImage(id) {
            const numericId = Number(id);
            this.form.images = this.form.images.filter(imageId => imageId !== numericId);
        },
        submitForm() {
            if (this.isSubmitting) {
                return;
            }
            this.isSubmitting = true;
            this.$nextTick(() => {
                if (this.$refs.form) {
                    this.$refs.form.submit();
                }
            });
        }
    }
};
</script>
