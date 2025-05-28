<template>
    <div>
        <Head title="Department Goal" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form @submit.prevent="submit">
                <x-grid>
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Department</template>
                            <template #value>
                                <x-select v-model="form.department_id" :options="setup.departments" option-label="name" option-value="id" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Fiscal Period</template>
                            <template #value>
                                <x-select v-model="form.fiscal_period_id" :options="setup.fiscal_periods" option-label="year" option-value="id" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <input type="text" v-model="form.title" class="input" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-12">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Description</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.description" @ready="cardData != null? form.description = cardData.description :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Target Date</template>
                            <template #value>
                                <input type="date" v-model="form.target_date" class="input" />
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

const props = defineProps({ ...createEditProps });

const form = reactive({
    uuid: null,
    department_id: '',
    fiscal_period_id: '',
    title: '',
    description: '',
    target_date: ''
});

const setData = () => {
    if (props.cardData?.uuid) {
        form.uuid = props.cardData.uuid;
        form.department_id = props.cardData.department_id;
        form.fiscal_period_id = props.cardData.fiscal_period_id;
        form.title = props.cardData.title;
        form.description = props.cardData.description;
        form.target_date = props.cardData.target_date;
    }
};

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