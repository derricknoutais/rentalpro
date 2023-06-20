<template>
    <div class="relative z-10 " aria-labelledby="modal-title" role="dialog" aria-modal="true" v-show="display_prop">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8">
                    <div>
                        <!-- Icon -->
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                            <slot name="icon"></slot>
                        </div>
                        <!-- Main Display -->
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                <slot name="title"></slot>
                            </h3>
                            <div class="mt-2">
                                <slot name="body"></slot>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <slot name="footer"></slot>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : ['display_prop'],
        data(){
            return  {
            }
        },
        computed : {
            display_local(){
                return this.display_prop
            }
        },
        methods : {
            hide(){
                console.log('Hello Im closing')
                this.$emit('hideModal')
                this.$forceUpdate()
            }

        }

    }
</script>

<style>

</style>
