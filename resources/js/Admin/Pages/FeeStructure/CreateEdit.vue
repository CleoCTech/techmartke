<template>
    <div>
        <Head :title="setup.editMode ? 'Edit Fee Structure' : 'Create Fee Structure'" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Training Module -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Training Module</template>
                            <template #value>
                                <x-select v-model="form.training_module_id" required>
                                    <option value="">--select--</option>
                                    <option v-for="module in trainingModules" :key="module.id" :value="module.id">
                                        {{ module.title }}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Course Type -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Course Type</template>
                            <template #value>
                                <x-select v-model="form.course_type_id" required>
                                    <option value="">--select--</option>
                                    <option v-for="courseType in courseTypes" :key="courseType.id" :value="courseType.id">
                                        {{ courseType.name }}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Fee -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Fee (Sh.)</template>
                            <template #value>
                                <TextInput type="number" v-model="form.fee" required min="0" step="0.01" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Duration -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Duration</template>
                            <template #value>
                                <TextInput type="text" v-model="form.duration" required placeholder="e.g., 3 months" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Status -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.is_active" required>
                                    <option value="">--select--</option>
                                    <option :value="true">Active</option>
                                    <option :value="false">Inactive</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Description -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Description</template>
                            <template #value>
                                <x-textarea v-model="form.description" rows="4" placeholder="Enter fee structure description" />
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
    trainingModules: {
        type: Array,
        default: () => []
    },
    courseTypes: {
        type: Array,
        default: () => []
    }
});

const form = reactive({
    uuid: null,
    training_module_id: '',
    course_type_id: '',
    fee: 0,
    duration: '',
    description: '',
    is_active: true
});

const setData = () => {
    if (props.cardData?.uuid) {
        form.uuid = props.cardData.uuid;
        form.training_module_id = props.cardData.training_module_id;
        form.course_type_id = props.cardData.course_type_id;
        form.fee = props.cardData.fee;
        form.duration = props.cardData.duration;
        form.description = props.cardData.description;
        form.is_active = props.cardData.is_active;
    }
};

const { submit, xGrid, xFormGroup, xGridCol, xSelect, xTextarea, xButton, 
        xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form);
</script>
