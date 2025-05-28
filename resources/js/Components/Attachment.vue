<template>
    <div class="px-1">
      <!-- Header section with improved styling -->
      <div class="flex items-center justify-between mb-4">
        <!-- <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Attachments</h2> -->
        <div class="flex items-center">
          <button 
            @click="isNew = !isNew" 
            class="inline-flex items-center bg-slate-800 dark:bg-slate-700 text-slate-200 px-3 py-1.5 rounded relative group"
          >
            <span class="mr-2">Attachments</span>
            <div class="flex items-center bg-indigo-500 text-white text-sm px-2 py-0.5 rounded">
              <svg class="w-3 h-3 mr-1" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              New
            </div>
          </button>
        </div>
      </div>
  
      <div v-if="!isLoading">
        <!-- New Attachment Form -->
        <form v-if="isNew" v-on:submit.prevent="submitAttachment" class="mb-4">
          <div class="space-y-3">
            <input 
              v-model="form.description" 
              class="form-input w-full px-2 py-1 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100" 
              type="text" 
              placeholder="Description" 
              required
            />
            <input 
              type="file" 
              @input="onFileChange($event)" 
              ref="input" 
              class="form-input w-full px-2 py-1 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100" 
              required
            />
            <div class="flex justify-end">
              <button 
                type="submit" 
                class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-colors duration-150"
              >
                Upload
              </button>
            </div>
          </div>
        </form>
  
        <!-- Attachments Table -->
        <div class="bg-white dark:bg-slate-800 shadow-lg rounded-sm border border-slate-200 dark:border-slate-700">
          <div class="overflow-x-auto">
            <table class="table-auto w-full">
              <thead class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/20 border-b border-slate-200 dark:border-slate-700">
                <tr>
                  <th class="px-4 py-3 whitespace-nowrap">Description</th>
                  <th class="px-4 py-3 whitespace-nowrap">Action</th>
                </tr>
              </thead>
              <tbody class="text-sm divide-y divide-slate-200 dark:divide-slate-700">
                <tr v-for="(attachment, index) in attachments" :key="index" class="hover:bg-slate-50 dark:hover:bg-slate-700/20">
                  <td class="px-4 py-3 whitespace-nowrap text-slate-600 dark:text-slate-300">
                    {{ attachment.description }}
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="flex items-center space-x-4">
                      <a 
                        :href="`/system/attachment/show/${attachment.uuid}`" 
                        class="text-sm flex items-center text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400" 
                        target="_blank"
                      >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View
                      </a>
                      <button 
                        @click="deleteAttachment(attachment.uuid)" 
                        class="text-sm flex items-center text-rose-500 hover:text-rose-600 dark:hover:text-rose-400"
                      >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  
        <!-- Empty State -->
        <div v-if="attachments.length === 0" class="text-sm text-slate-500 dark:text-slate-400 italic mt-2">
          No attachments found
        </div>
      </div>
  
      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center py-4">
        <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>
  </template>
  
  <script>
  import { usePage } from '@inertiajs/vue3'
  
  export default {
    props: {
      record: { default: '' },
      setup: Object,
      selected: { default: [] },
    },
    data() {
      return {
        attachments: [],
        form: {
          description: '',
          attachment: '',
          tableName: '',
          recordId: '',
        },
        isNew: false,
        isLoading: true,
      }
    },
    mounted() {
      this.form.tableName = this.setup.tableName;
      this.form.recordId = this.selected[0].split('#')[0];
      this.getAttachments();
    },
    methods: {
      getAttachments() {
        this.isLoading = true;
        axios.get(`/system/attachment/index/${this.form.tableName}/${this.form.recordId}`)
          .then(response => {
            this.attachments = response.data;
            this.isLoading = false;
          })
          .catch(error => {
            console.error('Error fetching attachments:', error);
            this.isLoading = false;
          });
      },
      submitAttachment() {
        this.isLoading = true;
        let formData = new FormData();
        for (let key in this.form) {
          formData.append(key, this.form[key]);
        }
        axios.post('/system/attachment/store', formData)
          .then(response => {
            if (response.data.status === 200) {
              this.$notify({
                text: response.data.message,
                type: 'success'
              });
            } else {
              this.$notify({
                text: response.data.message,
                type: 'error'
              });
            }
            this.getAttachments();
            this.resetData();
          })
          .catch(error => {
            if (error.response && error.response.status === 422) {
              const errors = error.response.data.errors;
              for (let field of Object.keys(errors)) {
                this.$notify({
                  text: errors[field][0],
                  type: 'error'
                });
              }
            } else {
              this.$notify({
                text: usePage().props.value.def_errors.default,
                type: 'error'
              });
            }
            this.isLoading = false;
          });
      },
      onFileChange(event) {
        const files = event.target.files;
        if (files.length) this.form.attachment = files[0];
      },
      resetData() {
        this.form.description = '';
        this.form.attachment = '';
        this.isNew = false;
      },
      deleteAttachment(uuid) {
        if (confirm("Do you really want to delete this attachment?")) {
          this.isLoading = true;
          axios.delete(`/system/attachment/delete/${uuid}`)
            .then(() => {
              this.$notify({
                text: 'Deleted Successfully',
                type: 'success'
              });
              this.getAttachments();
            })
            .catch(error => {
              console.error('Error deleting attachment:', error);
              this.isLoading = false;
            });
        }
      },
    },
  }
  </script>
  
  