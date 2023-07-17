<template>
    <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['display_prop'],
    data() {
        return {

        }
    },
    computed: {
        display_local() {
            return this.display_prop
        }
    },
    methods: {
        hide() {
            console.log('Hello Im closing')
            this.$emit('hideModal')
            this.$forceUpdate()
        }

    }

}
</script>

<style></style>
