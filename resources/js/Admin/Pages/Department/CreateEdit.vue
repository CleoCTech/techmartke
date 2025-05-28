<template>
    <div>
        <Head :title="setup.editMode ? 'Edit Department' : 'Create Department'" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST">
                <x-grid>
                    <!-- Name -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.name" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Description -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Description(optional)</template>
                            <template #value>
                                <TextInput type="text" v-model="form.description" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-3">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status">
                                    <option value="">--select--</option>
                                    <option v-for="(status,index) in setup.statuses" :key="index" :value="status.id">{{status.caption}}</option>
                                </x-select>
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

const form = reactive({
    uuid: null,
    name: null,
    description: null,
    status: null,
});

const setData = () => {
    if (props.cardData?.uuid) {
        form.uuid = props.cardData.uuid;
        form.name = props.cardData.name;
        form.description = props.cardData.description;
        form.status = props.cardData.status;
    }
};

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