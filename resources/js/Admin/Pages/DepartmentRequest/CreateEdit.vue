<template>
    <div>
      <Head :title="cardData ? 'Edit Department Request' : 'Create Department Request'" />
      <x-create-edit-template :setup="setup" :selected="selected">
        <form @submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
          <x-grid>
            <!-- From Department -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>From Department</template>
                <template #value>
                  <x-select v-model="form.from_department_id">
                    <option value="">-- Select Department --</option>
                    <option v-for="department in departments" :key="department.id" :value="department.id">
                      {{ department.name }}
                    </option>
                  </x-select>
                </template>
              </x-form-group>
            </x-grid-col>
  
            <!-- To Department -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>To Department</template>
                <template #value>
                  <x-select v-model="form.to_department_id">
                    <option value="">-- Select Department --</option>
                    <option v-for="department in departments" :key="department.id" :value="department.id">
                      {{ department.name }}
                    </option>
                  </x-select>
                </template>
              </x-form-group>
            </x-grid-col>
  
            <!-- Request Type -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>Request Type</template>
                <template #value>
                  <x-select v-model="form.type">
                    <option value="">-- Select Type --</option>
                    <option value="resource">Resource</option>
                    <option value="collaboration">Collaboration</option>
                    <option value="budget">Budget</option>
                    <option value="approval">Approval</option>
                  </x-select>
                </template>
              </x-form-group>
            </x-grid-col>
  
            <!-- Title -->
            <x-grid-col class="sm:col-span-6">
              <x-form-group>
                <template #label>Title</template>
                <template #value>
                  <TextInput type="text" v-model="form.title" />
                </template>
              </x-form-group>
            </x-grid-col>
  
            <!-- Description -->

            <x-grid-col class="sm:col-span-12">
                <x-form-group class="sm:grid-cols-1">
                    <template #label>Description</template>
                    <template #value>
                        <ckeditor v-cloak :editor="editor" v-model="form.description" @ready="cardData != null? form.description = cardData.description :''"></ckeditor>
                    </template>
                </x-form-group>
            </x-grid-col>
  
            <!-- Status (only for edit) -->
            <x-grid-col v-if="cardData" class="sm:col-span-4">
              <x-form-group>
                <template #label>Status</template>
                <template #value>
                  <x-select v-model="form.status">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
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
  import { ref, reactive, onMounted } from 'vue';
  import { Head } from '@inertiajs/vue3';

  
  const props = defineProps({
    ...createEditProps,
    departments: Array,
  });
  
  // Initialize form
  const form = reactive({
    uuid: null,
    from_department_id: '',
    to_department_id: '',
    type: '',
    title: '',
    description: '',
    status: 'pending',
  });
  
  // Set data if editing
  const setData = () => {
    if (props.cardData != null && props.cardData.uuid != null) {
      form.uuid = props.cardData.uuid;
      form.from_department_id = props.cardData.from_department_id;
      form.to_department_id = props.cardData.to_department_id;
      form.type = props.cardData.type;
      form.title = props.cardData.title;
      form.description = props.cardData.description;
      form.status = props.cardData.status;
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
  
  