<template>
  <div v-if="data && data.links && data.links.length > 3" class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
    <!-- Left side -->
    <div class="text-sm text-slate-500 dark:text-slate-400">
      Showing <span class="font-medium text-slate-600 dark:text-slate-300">{{ data.from }}</span> to 
      <span class="font-medium text-slate-600 dark:text-slate-300">{{ data.to }}</span> of 
      <span class="font-medium text-slate-600 dark:text-slate-300">{{ data.total }}</span> 
      <span class="text-slate-400 dark:text-slate-500">results</span>
    </div>

    <!-- Right side -->
    <div class="flex gap-2">
      <Link 
        v-if="data.prev_page_url"
        :href="data.prev_page_url"
        class="px-3 py-2 rounded-md border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600 transition-colors duration-150"
        preserve-scroll
      >
        ← Previous
      </Link>
      <button 
        v-else
        disabled
        class="px-3 py-2 rounded-md border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed"
      >
        ← Previous
      </button>
      
      <Link 
        v-if="data.next_page_url"
        :href="data.next_page_url"
        class="px-3 py-2 rounded-md border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-indigo-500 hover:border-slate-300 dark:hover:border-slate-600 transition-colors duration-150"
        preserve-scroll
      >
        Next →
      </Link>
      <button 
        v-else
        disabled
        class="px-3 py-2 rounded-md border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 cursor-not-allowed"
      >
        Next →
      </button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed,onMounted  } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  links: {
    type: Array,
    default: () => []
  },
  data: {
    type: Object,
    required: true,
    default: () => ({
      current_page: 1,
      data: [],
      first_page_url: null,
      from: 0,
      last_page: 0,
      links: [],
      next_page_url: null,
      path: '',
      per_page: 15,
      prev_page_url: null,
      to: 0,
      total: 0
    })
  },
  // meta: {
  //   type: Object,
  //   default: () => ({
  //     current_page: 1,
  //     from: 1,
  //     last_page: 1,
  //     links: [],
  //     path: '',
  //     per_page: 15,
  //     to: 1,
  //     total: 0
  //   })
  // }
});
onMounted(() => {
  // console.log(props.data);

})
// Find the previous page URL
const previousPageUrl = computed(() => {
  const prevLink = props.links.find(link => link.label === '&laquo; Previous');
  return prevLink ? prevLink.url : null;
});

// Find the next page URL
const nextPageUrl = computed(() => {
  const nextLink = props.links.find(link => link.label === 'Next &raquo;');
  return nextLink ? nextLink.url : null;
});

// Check if there's a previous page
const hasPreviousPage = computed(() => {
  return previousPageUrl.value !== null;
});

// Check if there's a next page
const hasNextPage = computed(() => {
  return nextPageUrl.value !== null;
});
</script>