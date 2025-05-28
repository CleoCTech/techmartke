<template>
    <div>

        <Head title="Change Password" />


        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Success Message -->
                        <div v-if="status" class="mb-4 font-medium text-sm text-green-500">
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <!-- Current Password -->
                                <div>
                                    <InputLabel for="current_password" value="Current Password" class="text-gray-300" />
                                    <TextInput id="current_password" ref="currentPasswordInput"
                                        v-model="form.current_password" type="password"
                                        class="mt-1 block w-full bg-slate-700 border-slate-600 text-gray-200"
                                        autocomplete="current-password" />
                                    <InputError :message="form.errors.current_password" class="mt-2" />
                                </div>

                                <!-- New Password -->
                                <div>
                                    <InputLabel for="password" value="New Password" class="text-gray-300" />
                                    <TextInput id="password" v-model="form.password" type="password"
                                        class="mt-1 block w-full bg-slate-700 border-slate-600 text-gray-200"
                                        autocomplete="new-password" />
                                    <InputError :message="form.errors.password" class="mt-2" />
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <InputLabel for="password_confirmation" value="Confirm Password"
                                        class="text-gray-300" />
                                    <TextInput id="password_confirmation" v-model="form.password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full bg-slate-700 border-slate-600 text-gray-200"
                                        autocomplete="new-password" />
                                    <InputError :message="form.errors.password_confirmation" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end">
                                    <PrimaryButton :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing" >
                                        Change Password
                                    </PrimaryButton>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { ref, onMounted, watch } from 'vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/System/Layouts/AppLayout.vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useNotify } from "@/Composables/useNotify";

let { notification } = useNotify();
const status = usePage().props.success;

// const props = defineProps({
//     status: String, // Ensure status is defined as a prop
// });

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const currentPasswordInput = ref(null);

watch(() => status, (newStatus) => {
    if (newStatus) {
        console.log('Status:', newStatus); 
        notification(newStatus, 'success');
    }
});

// Watch for changes in the `status` prop
// watch(() => props.status, (newStatus) => {
//     if (newStatus) {
//         console.log('Status:', newStatus); // Log the status
//         notification(newStatus, 'success'); // Display notification
//         router.replace({ data: { status: null } }); // Clear status
//     }
// });

onMounted(() => {
    if (currentPasswordInput.value) {
        currentPasswordInput.value.focus();
    }

});


const submit = () => {
    form.put(route('password.change'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>