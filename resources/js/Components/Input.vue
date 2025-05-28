<template>
    <input 
        :value="modelValue" 
        @input="$emit('update:modelValue', $event.target.value)" 
        :type="type" 
        ref="input" 
        class="w-full px-3 py-2 text-sm transition-colors duration-150 ease-in-out
               bg-white dark:bg-slate-800 
               border border-slate-200 dark:border-slate-700
               text-slate-800 dark:text-slate-100
               rounded-md
               placeholder-slate-400 dark:placeholder-slate-500
               focus:outline-none 
               focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-opacity-50
               focus:border-indigo-500 dark:focus:border-indigo-400
               disabled:opacity-40 disabled:cursor-not-allowed
               hover:border-slate-300 dark:hover:border-slate-600"
    />
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        modelValue: {
            type: [String, Number],
            default: ''
        },
        type: {
            type: String,
            default: 'text'
        }
    },

    emits: ['update:modelValue'],

    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
})
</script>

