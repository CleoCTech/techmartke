<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all dark:bg-slate-800">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                Create Department Request
                            </DialogTitle>
                            <div class="mt-2">
                                <form @submit.prevent="submitForm">
                                    <div class="space-y-4">
                                        <div>
                                            <label for="fromDepartment"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">From
                                                Department</label>
                                            <select id="fromDepartment" v-model="form.from_department_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                                <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{
                                                    dept.name }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="toDepartment"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">To
                                                Department</label>
                                            <select id="toDepartment" v-model="form.to_department_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                                <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{
                                                    dept.name }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="type"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Request
                                                Type</label>
                                            <select id="type" v-model="form.type"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                                <option value="resource">Resource</option>
                                                <option value="collaboration">Collaboration</option>
                                                <option value="budget">Budget</option>
                                                <option value="approval">Approval</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="title"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                            <input type="text" id="title" v-model="form.title"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                                                required>
                                        </div>
                                       <!-- Description with CKEditor -->
                                        <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                        <div class="mt-1 [&_.ck-editor]:dark:bg-slate-800 [&_.ck-editor__main]:dark:bg-slate-800 [&_.ck-editor__editable]:dark:bg-slate-800 [&_.ck-editor__editable]:dark:text-white [&_.ck-toolbar]:dark:bg-slate-700 [&_.ck-toolbar__items]:dark:text-white [&_.ck]:dark:border-slate-600 [&_.ck-toolbar]:dark:border-slate-600 [&_.ck-content]:dark:border-slate-600">
                                            <ckeditor v-cloak :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end space-x-2">
                                        <button type="button" @click="closeModal"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
                                            Cancel
                                        </button>
                                        <button type="submit" :disabled="isSubmitting" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                                            {{ isSubmitting ? 'Submitting...' : 'Submit Request' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { useToast } from '@/Composables/useToast'
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { router } from '@inertiajs/vue3'
import Toast from '@/Components/Toast.vue';
import axios from 'axios'
import {useNotify} from "@/Composables/useNotify";

const props = defineProps({
    isOpen: Boolean,
})

const emit = defineEmits(['close', 'submit'])

const { toast, showToast } = useToast();
let {notification} = useNotify();

const form = reactive({
    from_department_id: '',
    to_department_id: '',
    type: '',
    title: '',
    description: '',
    source: 'modal',
})
const isSubmitting = ref(false);
const editor = ClassicEditor;
const ckeditor = CKEditor.component;

const editorConfig = {
  editorConfig: {
    stylesSet: [
      { name: 'Dark Theme', type: 'widget', widget: 'customStyle' }
    ],
    // Add content styles for dark mode
    content: {
      styles: [
        `.ck-content { color: white; background: rgb(30 41 59); }`,
        `.ck-content h1, .ck-content h2, .ck-content h3, .ck-content h4, .ck-content h5, .ck-content h6 { color: white; }`,
        `.ck-content p { color: white; }`,
        `.ck-content li { color: white; }`,
      ]
    }
  }
};

const departments = ref([])
const isLoading = ref(true)
const error = ref(null)

console.log('Component script is running')

onMounted(() => {
    console.log('Component mounted')
    // notification('Component mounted', 'success');
    fetchDepartments()
})

watch(() => props.isOpen, (newValue) => {
    console.log('Modal open state changed:', newValue)
    if (newValue) {
        console.log('Modal opened, fetching departments')
        fetchDepartments()
    }
})

const fetchDepartments = async () => {
    console.log('Fetching departments')
    isLoading.value = true
    error.value = null

    try {
        const response = await axios.get(route('admin.departments-for-modal'))
        console.log('Departments fetched:', response.data)
        departments.value = response.data
        isLoading.value = false
    } catch (err) {
        console.error('Error fetching departments:', err)
        error.value = 'Failed to load departments. Please try again.'
        isLoading.value = false
        showToast('Failed to load departments', 'error')
    }
}

const closeModal = () => {
  form.from_department_id = '';
  form.to_department_id = '';
  form.type = '';
  form.title = '';
  form.description = '';
  emit('close');
};


const validateForm = () => {
  if (form.from_department_id === form.to_department_id) {
    notification('From and To departments cannot be the same.', 'error');
    showToast({
      message: 'From and To departments cannot be the same.',
      type: 'error'
    });
    return false;
  }
  return true;
};

const submitForm = () => {
    if (!validateForm()) {
        return;
    }
  isSubmitting.value = true;
  
  axios.post(route('admin.department-requests.store'), form)
    .then(response => {
        notification('Request submitted successfully', 'success');
        showToast({
        message: 'Request submitted successfully',
        type: 'success'
      });
      emit('submit', form);
      closeModal();
    })
    .catch(error => {
      if (error.response?.status === 422) {
        const errors = error.response.data.errors;
        Object.keys(errors).forEach(key => {
            notification(errors[key][0], 'error');
            showToast({
            message: errors[key][0],
            type: 'error'
          });
        });
      } else {
        notification('Failed to submit request. Please try again.', 'error');
        showToast({
          message: 'Failed to submit request. Please try again.',
          type: 'error'
        });
      }
      console.error('Error submitting form:', error);
    })
    .finally(() => {
      isSubmitting.value = false;
    });
};


</script>
<style>
/* Additional global styles for CKEditor dark mode */
.dark .ck.ck-button:not(.ck-disabled):hover,
.dark .ck.ck-button:not(.ck-disabled):active {
  background: rgb(51 65 85) !important;
}

.dark .ck.ck-button.ck-on {
  background: rgb(51 65 85) !important;
  color: white !important;
}

.dark .ck.ck-button .ck-button__label {
  color: white !important;
}

.dark .ck.ck-icon :not(svg) {
  color: white !important;
}

.dark .ck.ck-dropdown__panel {
  background: rgb(30 41 59) !important;
  border-color: rgb(71 85 105) !important;
}

.dark .ck.ck-list__item .ck-button:hover:not(.ck-disabled) {
  background: rgb(51 65 85) !important;
}

.dark .ck.ck-list__item .ck-button {
  color: white !important;
}

.dark .ck.ck-toolbar {
  background: rgb(51 65 85) !important;
  border-color: rgb(71 85 105) !important;
}

.dark .ck.ck-editor__main > .ck-editor__editable:not(.ck-focused) {
  border-color: rgb(71 85 105) !important;
}

.dark .ck.ck-editor__main > .ck-editor__editable.ck-focused {
  border-color: rgb(99 102 241) !important;
}

.dark .ck.ck-dropdown .ck-dropdown__panel .ck-list__item {
  color: white !important;
}

.dark .ck.ck-dropdown .ck-dropdown__panel .ck-list__item:hover {
  background: rgb(51 65 85) !important;
}

.dark .ck.ck-dropdown .ck-dropdown__panel .ck-list__item .ck-button:hover {
  background: rgb(51 65 85) !important;
}

.dark .ck.ck-dropdown .ck-dropdown__panel .ck-list__item .ck-button__label {
  color: white !important;
}

.dark .ck.ck-dropdown.ck-heading-dropdown .ck-dropdown__panel .ck-list__item .ck-button .ck-button__label {
  color: white !important;
}
</style>