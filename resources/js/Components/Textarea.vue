<template>
    <textarea 
        :value="modelValue" 
        @input="$emit('update:modelValue', $event.target.value)" 
        ref="textarea" 
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:opacity-50 dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:border-indigo-600 dark:focus:ring-indigo-600 dark:placeholder-slate-400"
    >
        <slot></slot>
    </textarea>
</template>

<script setup>
import { ref, defineProps, defineEmits, defineExpose } from 'vue';
const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    }
});

defineEmits(['update:modelValue']);

const textarea = ref(null);

// Expose focus method to parent components
defineExpose({
    focus: () => textarea.value?.focus()
});
</script>