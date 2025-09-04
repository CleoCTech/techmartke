<template>
    <div>
        <Head :title="setup.editMode ? 'Edit Scholarship Application' : 'Create Scholarship Application'" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Type -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Scholarship Type</template>
                            <template #value>
                                <x-select v-model="form.type" required>
                                    <option value="">--select--</option>
                                    <option value="student">Student Scholarship</option>
                                    <option value="professional">Professional Scholarship</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Name -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Full Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.name" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Email -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Email Address</template>
                            <template #value>
                                <TextInput type="email" v-model="form.email" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Phone -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Phone Number</template>
                            <template #value>
                                <TextInput type="tel" v-model="form.phone" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Status -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status" required>
                                    <option value="">--select--</option>
                                    <option :value="1">New</option>
                                    <option :value="2">Pending Approval</option>
                                    <option :value="3">Approved</option>
                                    <option :value="4">Rejected</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Notes -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Additional Notes</template>
                            <template #value>
                                <x-textarea v-model="form.notes" rows="4" placeholder="Any additional notes about this application..." />
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
    type: '',
    name: '',
    email: '',
    phone: '',
    notes: '',
    status: 1
});

const setData = () => {
    if (props.cardData?.uuid) {
        form.uuid = props.cardData.uuid;
        form.type = props.cardData.type;
        form.name = props.cardData.name;
        form.email = props.cardData.email;
        form.phone = props.cardData.phone;
        form.notes = props.cardData.notes;
        form.status = props.cardData.status;
    }
};

const { submit, xGrid, xFormGroup, xGridCol, xSelect, xTextarea, xButton, 
        xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form);
</script>
