<template>
    <div>
        <Head title="Department Head" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form @submit.prevent="submit">
                <x-grid>
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>User</template>
                            <template #value>
                                <x-select v-model="form.user_id">
                                    <option value="">--select--</option>
                                    <option v-for="(user, index) in setup.users" 
                                            :key="index" 
                                            :value="user.id">
                                        {{user.name}}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Department</template>
                            <template #value>
                                <x-select v-model="form.department_id">
                                    <option value="">--select--</option>
                                    <option v-for="(department, index) in setup.departments" 
                                            :key="index" 
                                            :value="department.id">
                                        {{department.name}}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Designation</template>
                            <template #value>
                                <x-select v-model="form.designation_id">
                                    <option value="">--select--</option>
                                    <option v-for="(designation, index) in setup.designations" 
                                            :key="index" 
                                            :value="designation.id">
                                        {{designation.name}}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Fiscal Period</template>
                            <template #value>
                                <x-select v-model="form.fiscal_period_id">
                                    <option value="">--select--</option>
                                    <option v-for="(period, index) in setup.fiscal_periods" 
                                            :key="index" 
                                            :value="period.id">
                                        {{period.period_name}}
                                    </option>
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

const props = defineProps({ ...createEditProps });

const form = reactive({
    uuid: null,
    user_id: '',
    department_id: '',
    designation_id: '',
    fiscal_period_id: ''
});
const setData = () => {
    if (props.cardData?.uuid) {
        form.uuid = props.cardData.uuid;
        form.user_id = props.cardData.user_id;
        form.department_id = props.cardData.department_id;
        form.designation_id = props.cardData.designation_id;
        form.fiscal_period_id = props.cardData.fiscal_period_id;
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