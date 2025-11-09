<template>
    <div class="fixed inset-0 z-40 flex items-center justify-center px-4 py-2" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="hide"></div>

        <div class="relative z-50 w-full max-w-xl rounded-2xl bg-white shadow-2xl ring-1 ring-black/5">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-2">
                <div>
                    <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide" v-if="$slots.title">
                        <slot name="title"></slot>
                    </p>
                </div>
                <button type="button"
                    class="inline-flex rounded-full bg-gray-100 p-1 text-gray-500 hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    @click="hide">
                    <span class="sr-only">Fermer</span>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="max-h-[80vh] overflow-y-auto px-6 mb-5 text-sm text-gray-700">
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
