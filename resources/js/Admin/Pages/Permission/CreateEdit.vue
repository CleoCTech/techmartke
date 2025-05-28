<template>
    <div>
        <Head title="Permission" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST">
                <x-grid>
                    <!-- Permission Name -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.name" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Display Name -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Display Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.display_name" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Description -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Description</template>
                            <template #value>
                                <TextInput type="text" v-model="form.description" />
                            </template>
                        </x-form-group>
                    </x-grid-col>
                </x-grid>

                <div class="flex justify-center py-3">
                    <x-button type="submit">Submit</x-button>
                </div>
            </form>
        </x-create-edit-template>
    </div>
</template>

<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit';
import { reactive } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    ...createEditProps,
});

// Initialize form
const form = reactive({
    uuid: null,
    name: '',
    display_name: '',
    description: '',
});
function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.name = props.cardData.name;
        form.display_name = props.cardData.display_name;
        form.description = props.cardData.description;
    }
    
}
// Use the createEdit composable
const { editor,editorConfig, submit, onFileChange, ckeditor, xGrid,
        xFormGroup,
        xGridCol,
        xLoading,
        xPanel,
        xInput,
        xSelect,
        xTextarea,
        xCheckbox,
        Checkbox,
        xButton,
        AppLayout,
        xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form )
</script>

<style scoped>
/* Add custom styles if needed */
</style>