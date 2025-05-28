<template>
    <div>
      <Head :title="cardData ? 'Edit Department Activity' : 'Create Department Activity'" />
      <x-create-edit-template :setup="setup" :selected="selected">
        <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
          <x-grid>
             <!-- Title -->
             <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>Title</template>
                <template #value>
                  <TextInput type="text" v-model="form.title" />
                </template>
              </x-form-group>
            </x-grid-col>
            <!-- Department -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>Department</template>
                <template #value>
                  <x-select v-model="form.department_id">
                    <option value="">-- Select Department --</option>
                    <option v-for="department in setup.departments" :key="department.id" :value="department.id">
                      {{ department.name }}
                    </option>
                  </x-select>
                </template>
              </x-form-group>
            </x-grid-col>
    
            <!-- Fiscal Period -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>Fiscal Period</template>
                <template #value>
                  <x-select v-model="form.fiscal_period_id">
                    <option value="">-- Select Fiscal Period --</option>
                    <option v-for="period in setup.fiscal_periods" :key="period.id" :value="period.id">
                      {{ period.period_name }}
                    </option>
                  </x-select>
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
    
            <!-- Activity Date -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>Activity Date</template>
                <template #value>
                  <TextInput type="date" v-model="form.activity_date" />
                </template>
              </x-form-group>
            </x-grid-col>
    
            <!-- Status -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>Status</template>
                <template #value>
                  <x-select v-model="form.status">
                    <option value="">-- Select Status --</option>
                    <option value="1">Planned</option>
                    <option value="2">In Progress</option>
                    <option value="3">Completed</option>
                    <option value="4">Cancelled</option>
                  </x-select>
                </template>
              </x-form-group>
            </x-grid-col>
    
            <!-- Remarks -->
            <x-grid-col class="sm:col-span-12">
              <x-form-group>
                <template #label>Remarks</template>
                <template #value>
                  <x-textarea v-model="form.remarks" rows="3" />
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
      fiscal_periods: Array
    });
    
    // Initialize form
    const form = reactive({
      uuid: null,
      department_id: '',
      fiscal_period_id: '',
      title: '',
      description: '',
      activity_date: '',
      status: '',
      remarks: ''
    });
    
    // Set data if editing
    const setData = () => {
      if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.department_id = props.cardData.department_id;
        form.fiscal_period_id = props.cardData.fiscal_period_id;
        form.title = props.cardData.title;
        form.description = props.cardData.description;
        form.activity_date = props.cardData.activity_date;
        form.status = props.cardData.status;
        form.remarks = props.cardData.remarks;
      }
    };
    
    // Use the createEdit composable
    const { 
      editor,
      editorConfig, 
      submit, 
      onFileChange, 
      ckeditor, 
      xGrid,
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
      xCreateEditTemplate, 
      TextInput 
    } = useCreateEdit(props, setData, form);
    </script>
    