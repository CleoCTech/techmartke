<template>
  <div class="tag-input-container">
    <div v-for="(tag, index) in tags" :key="index" class="tag">
      {{ tag }}
      <button @click="removeTag(index)" class="tag-remove">&times;</button>
    </div>
    <input
      type="text"
      ref="inputRef"
      class="tag-input"
      @keydown="handleKeydown"
      @paste="handlePaste"
      v-model="newTag"
      :placeholder="placeholder"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  modelValue: { // Expecting an array of strings for v-model
    type: Array,
    default: () => []
  },
  placeholder: { // Placeholder for the input field
    type: String,
    default: 'Add tags...'
  }
});

const emit = defineEmits(['update:modelValue']);

const tags = ref([...props.modelValue]); // Internal state for tags
const newTag = ref(''); // Internal state for the new tag input
const inputRef = ref(null); // Ref for the input element

// Watch for external changes to modelValue and update internal tags
watch(() => props.modelValue, (newValue) => {
  tags.value = [...newValue];
});

const addTag = (tagContent) => {
  const trimmedTag = tagContent.trim();
  if (trimmedTag && !tags.value.includes(trimmedTag)) {
    tags.value.push(trimmedTag);
    emit('update:modelValue', tags.value); // Emit updated array
  }
  newTag.value = ''; // Clear input after adding
};

const removeTag = (index) => {
  tags.value.splice(index, 1);
  emit('update:modelValue', tags.value); // Emit updated array
};

const handleKeydown = (event) => {
  // Add tag on Enter key
  if (event.key === 'Enter' || event.key === ',') {
    event.preventDefault(); // Prevent form submission or comma in input
    addTag(newTag.value);
  }
};

const handlePaste = (event) => {
  event.preventDefault();
  const paste = (event.clipboardData || window.clipboardData).getData('text');
  // Split by comma, semicolon, or newline and add each as a tag
  paste.split(/[;,\n]/).forEach(tag => addTag(tag));
};

// Optional: Expose focus similar to TextInput if needed
// defineExpose({ focus: () => inputRef.value.focus() });

</script>

<style scoped>
.tag-input-container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  border: 1px solid #ccc; /* Example border, match your design system */
  border-radius: 4px;
  padding: 4px;
  gap: 5px; /* Space between tags and input */
}

.tag {
  display: flex;
  align-items: center;
  background-color: #e0e0e0; /* Example tag background */
  border-radius: 3px;
  padding: 2px 5px;
  font-size: 0.9em;
}

.tag-remove {
  background: none;
  border: none;
  cursor: pointer;
  margin-left: 5px;
  padding: 0;
  font-size: 1em; /* Adjust size as needed */
}

.tag-input {
  flex-grow: 1; /* Allow input to take available space */
  border: none; /* Remove default input border */
  outline: none; /* Remove input outline on focus */
  padding: 5px; /* Match tag padding roughly */
  min-width: 100px; /* Ensure input is visible */
}

/* Basic styling to match the theme if needed */
.tag-input-container {
    background-color: #1a202c; /* dark:bg-slate-800 */
    border-color: #4a5568; /* dark:border-slate-700 */
    color: #e2e8f0; /* dark:text-slate-100 */
}

.tag {
    background-color: #2d3748; /* A darker background for tags in dark theme */
    color: #e2e8f0;
}

.tag-remove {
    color: #cbd5e0; /* Lighter color for remove button */
}

.tag-input {
    background-color: #1a202c; /* dark:bg-slate-800 */
    color: #e2e8f0; /* dark:text-slate-100 */
}

.tag-input::placeholder {
    color: #a0aec0; /* dark placeholder color */
}
</style> 