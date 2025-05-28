<script setup>
import { ref, defineProps, defineEmits, defineExpose } from 'vue'

const props = defineProps({
  modelValue: [String, Number]
})

const emit = defineEmits(['update:modelValue'])

const select = ref(null)

const focus = () => {
  select.value?.focus()
}

defineExpose({ focus })
</script>

<template>
  <select
    :value="modelValue"
    @input="$event => emit('update:modelValue', $event.target.value)"
    ref="select"
    class="cursor-pointer w-full rounded-md shadow-sm transition-colors duration-150 ease-in-out
           bg-white dark:bg-slate-800 
           text-slate-800 dark:text-slate-100
           border border-slate-200 dark:border-slate-700
           focus:border-indigo-500 dark:focus:border-indigo-400 
           focus:ring focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-opacity-50
           disabled:opacity-40"
  >
    <slot></slot>
  </select>
</template>

<style>
.themed-select {
  color-scheme: light dark;
}

.themed-select option {
  background-color: white;
  color: #1e293b; /* slate-800 */
}

@media (prefers-color-scheme: dark) {
  .themed-select option {
    background-color: #1e293b; /* slate-800 */
    color: #f1f5f9; /* slate-100 */
  }
}

/* For browsers that support :has() selector */
:root:has(.dark) .themed-select option {
  background-color: #1e293b; /* slate-800 */
  color: #f1f5f9; /* slate-100 */
}
</style>
