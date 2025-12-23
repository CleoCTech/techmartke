<template>
    <div>

        <Head title="User" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Name</template>
                            <template #value><x-input type="text" v-model="form.name" /></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Email</template>
                            <template #value><x-input type="text" v-model="form.email" /></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status">
                                    <option value="">--select--</option>
                                    <option v-for="(status, index) in setup.statuses" :key="index" :value="status.id">
                                        {{ status.caption }}</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                    </x-grid-col>
                </x-grid>

                <!-- User Roles Section -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">User Roles</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div v-for="role in roles" :key="role.id" class="flex items-center">
                            <input type="checkbox" :id="'role_' + role.id" v-model="form.roles" :value="role.id"
                                class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label :for="'role_' + role.id" class="ml-2 text-sm text-slate-600 dark:text-slate-400">
                                {{ role.display_name }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- User Permissions Section -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">User Permissions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div v-for="permission in permissions" :key="permission.id" class="flex items-center">
                            <input type="checkbox" :id="'permission_' + permission.id" v-model="form.permissions"
                                :value="permission.id"
                                class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label :for="'permission_' + permission.id"
                                class="ml-2 text-sm text-slate-600 dark:text-slate-400">
                                {{ permission.display_name }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center py-3">
                    <x-button type="submit">Submit</x-button>
                </div>

            </form>

        </x-create-edit-template>


    </div>
</template>
<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit'

import { ref, reactive, computed, onMounted } from 'vue'

const props = defineProps({
    ...createEditProps,
    roles: Array,
    permissions: Array,
})


onMounted(() => {

    // console.log(props.setup.bindedPriceIds);
    // chartData.value = setChartData();
    // chartOptions.value = setChartOptions();
})


const form = reactive({
    uuid: null,
    name: null,
    email: null,
    status: null,
    user_category: null,
    roles: [], // Array of role IDs
    permissions: [] // Array of permission IDs
})


function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.name = props.cardData.name;
        form.email = props.cardData.email;
        form.status = props.cardData.status;

        // Set roles and permissions from the user's existing assignments
        form.roles = props.cardData.roles.map(role => role.id);
        form.permissions = props.cardData.permissions.map(permission => permission.id);
    }

}

const { editor, editorConfig, submit, onFileChange, ckeditor, xGrid,
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
    xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form)


</script>
<style scoped>
input[type="file"] {
    /* display: none; */
}

.custom-file-input {
    background-color: blue;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

/* 
label {
  background-color: blue;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

label::before {
  content: "Choose file";
} */

/* label::after {
  content: "📷";
} */
</style>