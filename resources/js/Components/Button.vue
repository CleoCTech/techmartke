<template>
    <button 
      :type="type" 
      :class="[
        'btn px-3 py-2 rounded-md font-medium transition-colors duration-150 ease-in-out',
        'border dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600',
        themeClasses[theme]
      ]"
    >
      <slot></slot>
    </button>
  </template>
  
  <script>
  export default {
    name: 'ThemedButton',
    props: {
      type: {
        type: String,
        default: 'button'
      },
      theme: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'success', 'error', 'info', 'warning'].includes(value)
      }
    },
    setup() {
      const themeClasses = {
        primary: 'bg-indigo-500 hover:bg-indigo-600 text-white dark:bg-indigo-600 dark:hover:bg-indigo-700',
        success: 'bg-emerald-500 hover:bg-emerald-600 text-white dark:bg-emerald-600 dark:hover:bg-emerald-700',
        error: 'bg-rose-500 hover:bg-rose-600 text-white dark:bg-rose-600 dark:hover:bg-rose-700',
        info: 'bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-700',
        warning: 'bg-amber-500 hover:bg-amber-600 text-white dark:bg-amber-600 dark:hover:bg-amber-700'
      }
  
      return {
        themeClasses
      }
    }
  }
  </script>
  
  