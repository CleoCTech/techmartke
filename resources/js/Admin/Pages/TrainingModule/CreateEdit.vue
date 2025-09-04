<template>
    <div>
        <Head :title="setup.editMode ? 'Edit Training Module' : 'Create Training Module'" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Title -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <TextInput type="text" v-model="form.title" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Sort Order -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Sort Order</template>
                            <template #value>
                                <TextInput type="number" v-model="form.sort_order" required min="0" />
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
                                <x-textarea v-model="form.description" rows="4" placeholder="Enter module description" />
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
    ...createEditProps
});

const form = reactive({
    uuid: null,
    title: '',
    description: '',
    sort_order: 0,
    is_active: true
});

const setData = () => {
    if (props.cardData?.uuid) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.description = props.cardData.description;
        form.sort_order = props.cardData.sort_order;
        form.is_active = props.cardData.is_active;
    }
};

const { submit, xGrid, xFormGroup, xGridCol, xSelect, xTextarea, xButton, 
        xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form);
</script>
