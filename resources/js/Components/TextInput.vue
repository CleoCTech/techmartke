<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="form-input w-full rounded-md shadow-sm bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 focus:border-ablue dark:focus:border-ablue focus:ring-ablue dark:focus:ring-ablue"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>

