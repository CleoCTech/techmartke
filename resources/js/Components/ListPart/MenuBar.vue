<template>
    <div>
        <div class="relative mb-8">
            <div class="absolute bottom-0 w-full h-px bg-slate-200 dark:bg-slate-700" aria-hidden="true"></div>
            
            <!-- Desktop Menu -->
            <ul id="tabs" class="relative text-sm font-medium hidden sm:flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8 overflow-x-scroll no-scrollbar">
                <li @click="activeTab('home')" class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                    <a href="#" 
                    class="block pb-3 whitespace-nowrap " 
                    :class="active == 'home'? 
                    'text-indigo-500 border-b-2 border-indigo-500' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'" >Home
                    </a>
                </li>
                <li @click="activeTab('moreActions')" class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                    <a href="#" 
                    class="block pb-3 whitespace-nowrap " 
                    :class="active == 'moreActions'? 
                    'text-indigo-500 border-b-2 border-indigo-500' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'" >More Actions
                    </a>
                </li>
                <li @click="activeTab('reports')" class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                    <a href="#" 
                    class="block pb-3 whitespace-nowrap " 
                    :class="active == 'reports'? 
                    'text-indigo-500 border-b-2 border-indigo-500' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'" >Reports
                    </a>
                </li>
                <li @click="activeTab('charts')" class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                    <a href="#" 
                    class="block pb-3 whitespace-nowrap " 
                    :class="active == 'charts'? 
                    'text-indigo-500 border-b-2 border-indigo-500' :
                    'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'" >Charts
                    </a>
                </li>
            </ul>

            <!-- Mobile Menu -->
            <div class="sm:hidden">
                <select v-model="active" @change="activeTab(active)" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="home">Home</option>
                    <option value="moreActions">More Actions</option>
                    <option value="reports">Reports</option>
                    <option value="charts">Charts</option>
                </select>
            </div>

            <div class="flex flex-col sm:flex-row pr-4 pl-1 pt-2">
                <div v-if="active == 'home'" class="w-full">
                    TGUH*UHUHUHUHU
                    <x-standard-actions v-bind="{setup,selected}"
                        @onMultipleSelect="isMultipleSelect = !isMultipleSelect"
                        @onAttachments="$parent.isAttachments = !$parent.isAttachments"
                        @onFilter="onFilter"
                        @onExport="onExport"
                        @showCreateEditPanel="showCreateEditPanel"
                    />
                </div>
                <div v-if="active == 'moreActions'" class="w-full">
                    <em v-if="setup.settings.isMoreActions == false" class="text-blue-500">No more actions found</em>
                    <component v-if="setup.settings.isMoreActions != false && setup.settings.xMoreActions != null" :is="dynamicComponent" :selected="selected"></component>
                </div>
                <div v-if="active == 'reports'" class="w-full">
                    <em v-if="setup.settings.isReports == false"  class="text-blue-500">No reports found</em>
                    <component v-if="setup.settings.isReports != false" :is="dynamicComponent" :indexRoute="setup.settings.indexRoute" ></component>
                </div>
                <div v-if="active == 'charts'" class="w-full">
                    <em v-if="setup.settings.isCharts == false"  class="text-blue-500">No charts found</em>
                    <component v-if="setup.settings.isCharts != false" :is="dynamicComponent" :indexRoute="setup.settings.indexRoute" ></component>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, inject, defineEmits, watch } from 'vue';
import xStandardActions from '@/Components/ListPart/StandardActions.vue';

const props = defineProps({
    setup: Object,
    selected: {
        type: Array,
        default: () => []
    },
});

const isMultipleSelect = inject('isMultipleSelect', ref(false));

const active = ref('home');
const dynamicComponent = ref(null);

const emits = defineEmits(['showCreateEditPanel']);

const showCreateEditPanel = (value) => {
    emits('showCreateEditPanel', value);
};

const activeTab = (tab) => {
    active.value = tab;
    loadDynamicComponent();
};

const loadDynamicComponent = () => {
    if (active.value === 'moreActions' && props.setup.settings.isMoreActions && props.setup.settings.xMoreActions) {
        dynamicComponent.value = defineAsyncComponent(() => import(`@/${props.setup.settings.xMoreActions}.vue`));
    } else if (active.value === 'reports' && props.setup.settings.isReports) {
        dynamicComponent.value = defineAsyncComponent(() => import('@/Components/Reports/DefaultReport.vue'));
    } else if (active.value === 'charts' && props.setup.settings.isCharts) {
        dynamicComponent.value = defineAsyncComponent(() => import('@/Components/Charts/DefaultChart.vue'));
    } else {
        dynamicComponent.value = null;
    }
};

const onFilter = () => {
    // Implement filter logic
};

const onExport = () => {
    // Implement export logic
};

watch(() => props.setup, () => {
    loadDynamicComponent();
}, { deep: true });

// Initial load of dynamic component
loadDynamicComponent();
</script>

<style scoped>
/* Add any scoped styles here */
</style>

