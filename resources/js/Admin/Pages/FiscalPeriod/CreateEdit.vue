<template>
    <div>
        <Head title="Create Fiscal Period" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Fiscal Year -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Fiscal Year</template>
                            <template #value>
                                <x-select v-model="form.fiscal_year_id">
                                    <option value="">-- Select Fiscal Year --</option>
                                    <option v-for="fiscalYear in fiscalYears" :key="fiscalYear.id" :value="fiscalYear.id">
                                        {{ fiscalYear.year }}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Period Name -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Period Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.period_name" />
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
    fiscalYears: Array, // Fiscal years passed from the backend
});

// Initialize form
const form = reactive({
    uuid: null,
    fiscal_year_id: null,
    period_name: null,
    start_date: null,
    end_date: null,
});

// Set data if editing
const setData = () => {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.fiscal_year_id = props.cardData.fiscal_year_id;
        form.period_name = props.cardData.period_name;
        form.start_date = props.cardData.start_date;
        form.end_date = props.cardData.end_date;
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
} = useCreateEdit(props, setData, form);
</script>

<style scoped>
/* Add custom styles if needed */
</style>