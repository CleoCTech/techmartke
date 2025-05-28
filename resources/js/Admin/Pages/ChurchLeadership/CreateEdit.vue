<template>
    <div>
      <Head :title="cardData ? 'Edit Church Leadership' : 'Create Church Leadership'" />
      <x-create-edit-template :setup="setup" :selected="selected">
        <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
          <x-grid>
            <!-- User -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>User</template>
                <template #value>
                  <x-select v-model="form.user_id">
                    <option value="">-- Select User --</option>
                    <option v-for="user in setup.users" :key="user.id" :value="user.id">
                      {{ user.name }}
                    </option>
                  </x-select>
                </template>
              </x-form-group>
            </x-grid-col>
    
            <!-- Designation -->
            <x-grid-col class="sm:col-span-4">
              <x-form-group>
                <template #label>Designation</template>
                <template #value>
                  <x-select v-model="form.designation_id">
                    <option value="">-- Select Designation --</option>
                    <option v-for="designation in setup.designations" :key="designation.id" :value="designation.id">
                      {{ designation.name }}
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
                    <option v-for="fiscalPeriod in setup.fiscal_periods" :key="fiscalPeriod.id" :value="fiscalPeriod.id">
                      {{ fiscalPeriod.period_name }}
                    </option>
                  </x-select>
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
                    <option v-for="status in statuses" :key="status.id" :value="status.id">
                      {{ status.caption }}
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
    import { ref, reactive, onMounted } from 'vue';
    import { Head } from '@inertiajs/vue3';
    
    const props = defineProps({
      ...createEditProps,
      users: Array,
      designations: Array,
      fiscalPeriods: Array,
    });
    
    // Initialize form
    const form = reactive({
      uuid: null,
      user_id: null,
      designation_id: null,
      fiscal_period_id: null,
      status: null,
    });
    
    // Status options
    const statuses = ref([
      { id: 1, caption: 'Inactive', color: 'bg-gray-400' },
      { id: 2, caption: 'Active', color: 'bg-green-500' },
    ]);
    
    // Set data if editing
    const setData = () => {
      if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.user_id = props.cardData.user_id;
        form.designation_id = props.cardData.designation_id;
        form.fiscal_period_id = props.cardData.fiscal_period_id;
        form.status = props.cardData.status;
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
      xSelect,
      xButton,
      TextInput,
      xCreateEditTemplate,
    } = useCreateEdit(props, setData, form);
    </script>