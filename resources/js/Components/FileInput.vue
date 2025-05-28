<template>
    <div class="relative">
      <input
        type="file"
        ref="input"
        @input="onFileChange"
        class="hidden"
        :accept="accept"
      />
      <div
        class="flex items-center justify-between px-3 py-2 border rounded-md cursor-pointer transition-colors duration-150 ease-in-out bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-slate-600 dark:text-slate-300"
        @click="triggerFileInput"
      >
        <span class="text-sm truncate">{{ fileName || 'Choose a file' }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue'
  
  export default {
    name: 'ThemedFileInput',
    props: {
      accept: {
        type: String,
        default: '*'
      }
    },
    emits: ['file-change'],
    setup(props, { emit }) {
      const input = ref(null)
      const fileName = ref('')
  
      const triggerFileInput = () => {
        input.value.click()
      }
  
      const onFileChange = (event) => {
        const file = event.target.files[0]
        if (file) {
          fileName.value = file.name
          emit('file-change', file)
        }
      }
  
      return {
        input,
        fileName,
        triggerFileInput,
        onFileChange
      }
    }
  }
  </script>
  
  