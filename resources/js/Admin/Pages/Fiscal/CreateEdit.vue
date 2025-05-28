<template>
    <div>
        <Head title="Fiscal Year" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Year Name -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Year Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.year" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Start Date -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Start Date</template>
                            <template #value>
                                <TextInput type="date" v-model="form.start_date" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- End Date -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>End Date</template>
                            <template #value>
                                <TextInput type="date" v-model="form.end_date" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Is Current -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Is Current</template>
                            <template #value>
                                <x-checkbox v-model="form.is_current" />
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
import { ref, reactive, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    ...createEditProps,
});

// Initialize form
const form = reactive({
    uuid: null,
    year: null,
    start_date: null,
    end_date: null,
    is_current: false,
});

// Set data if editing
const setData = () => {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.year = props.cardData.year;
        form.start_date = props.cardData.start_date;
        form.end_date = props.cardData.end_date;
        form.is_current = props.cardData.is_current;
    }
};

// Use the createEdit composable
const {
    submit,
    xGrid,
    xFormGroup,
    xGridCol,
    xSelect,
    xButton,
    TextInput,
    xCreateEditTemplate,
    xCheckbox,
} = useCreateEdit(props, setData, form);
</script>

<style scoped>
/* Add custom styles if needed */
</style>