<script setup>
import { ref, defineProps } from 'vue'

const props = defineProps({
  activeCondition: Boolean,
  autoExpand: {
    type: Boolean,
    default: true // Default behavior: auto-expand when active
  }
})

// Start expanded if activeCondition is true AND autoExpand is true
// Otherwise start collapsed
const expanded = ref(props.activeCondition && props.autoExpand)

const handleClick = () => {
  expanded.value = !expanded.value
}
</script>
<template>
    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0" :class="activeCondition && 'bg-slate-900'">
        <slot :handleClick="handleClick" :expanded="expanded" />
    </li>
</template>


