<template>
    <GDialog v-model="value">
        <div v-if="type == 'confirm'">
            <div class="border-b px-1 py-1 flex justify-between items-center">
                Confirm
            </div>
            <div class="px-2 py-2">
                {{message}}
            </div>
            <div class="flex gap-2 justify-between border-t">
                <x-button  class="text-sm bg-green-500">Confirm</x-button>
                <x-button class="text-sm bg-red-500">Cancel</x-button>
            </div>
        </div>
        <!-- <div v-if="type == 'alert'"></div>
        <div v-if="type == 'info'"></div> -->
    </GDialog>
</template>

<script>
    // InfoDialog.vue
    import { GDialog } from 'gitart-vue-dialog'
    import xButton from '@/Components/Button.vue'
    export default {
        name: 'BaseDialog',
        components: {
            GDialog,
            xButton
        },
        props: {
            modelValue: {
                type: Boolean,
                default: false,
            },
            message: {
                type: String,
                required: true,
            },
            type: {
                type: String,
                required: true,
            },
        },

        computed: {
            value: {
            get() {
                return this.modelValue
            },
            set(val) {
                this.$emit('update:modelValue', val)
            },
            },
        },
    }
</script>
