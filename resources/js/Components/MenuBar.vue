<template>
    <div>
      <div class="relative mb-8">
        <!-- Bottom border line -->
        <div class="absolute bottom-0 w-full h-px bg-slate-200 dark:bg-slate-700" aria-hidden="true"></div>
        
        <!-- Tab navigation -->
             <!-- Tab navigation -->
      <ul class="relative text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8  no-scrollbar border-b border-slate-200 dark:border-slate-700">
        <!-- Home tab -->
        <li class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
          <button 
            @click="activeTab('home')"
            class="relative py-3 whitespace-nowrap focus:outline-none group"
            :class="[
              active === 'home' 
                ? 'text-indigo-500' 
                : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'
            ]"
          >
            <span>Home</span>
            <span 
              class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
              :class="[
                active === 'home'
                  ? 'bg-indigo-500'
                  : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'
              ]"
            ></span>
          </button>
        </li>

        <!-- More Actions tab -->
        <li class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
          <button 
            @click="activeTab('moreActions')"
            class="relative py-3 whitespace-nowrap focus:outline-none group"
            :class="[
              active === 'moreActions' 
                ? 'text-indigo-500' 
                : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'
            ]"
          >
            <span>More Actions</span>
            <span 
              class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
              :class="[
                active === 'moreActions'
                  ? 'bg-indigo-500'
                  : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'
              ]"
            ></span>
          </button>
        </li>

        <!-- Reports tab -->
        <li class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
          <button 
            @click="activeTab('reports')"
            class="relative py-3 whitespace-nowrap focus:outline-none group"
            :class="[
              active === 'reports' 
                ? 'text-indigo-500' 
                : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'
            ]"
          >
            <span>Reports</span>
            <span 
              class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
              :class="[
                active === 'reports'
                  ? 'bg-indigo-500'
                  : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'
              ]"
            ></span>
          </button>
        </li>

        <!-- Charts tab -->
        <li class="mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
          <button 
            @click="activeTab('charts')"
            class="relative py-3 whitespace-nowrap focus:outline-none group"
            :class="[
              active === 'charts' 
                ? 'text-indigo-500' 
                : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'
            ]"
          >
            <span>Charts</span>
            <span 
              class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
              :class="[
                active === 'charts'
                  ? 'bg-indigo-500'
                  : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'
              ]"
            ></span>
          </button>
        </li>
      </ul>
  
        <!-- Content area -->
        <div class="flex flex-row pr-4 pl-1 pt-4">
          <div v-if="active === 'home'" class="w-full">
            <x-standard-actions 
              v-bind="{ setup, selected }"
              @onMultipleSelect="this.$parent.$parent.isMultipleSelect = !this.$parent.$parent.isMultipleSelect"
              @onAttachments="handleAttachments" 
              @onFilter="onFilter" 
              @onExport="onExport" 
            />
          </div>
  
          <!-- More Actions content -->
          <div v-if="active === 'moreActions'" class="w-full">
            <em v-if="setup.settings.isMoreActions === false" class="text-indigo-500 dark:text-indigo-400">
              No more actions found
            </em>
            <component
              v-if="setup.settings.isMoreActions !== false && setup.settings.xMoreActions != null"
              :is="dynamicComponent" 
              :selected="selected"
            />
          </div>
  
          <!-- Reports content -->
          <div v-if="active === 'reports'" class="w-full">
            <em v-if="setup.settings.isReports === false" class="text-indigo-500 dark:text-indigo-400">
              No reports found
            </em>
            <component 
              v-if="setup.settings.isReports !== false" 
              :is="dynamicComponent"
              :indexRoute="setup.settings.indexRoute"
            />
          </div>
  
          <!-- Charts content -->
          <div v-if="active === 'charts'" class="w-full">
            <em v-if="setup.settings.isCharts === false" class="text-indigo-500 dark:text-indigo-400">
              No charts found
            </em>
            <component 
              v-if="setup.settings.isCharts !== false" 
              :is="dynamicComponent"
              :indexRoute="setup.settings.indexRoute"
            />
          </div>
        </div>
      </div>
    </div>
  </template>
<script>
import xStandardActions from '@/Components/StandardActions.vue'
import { defineAsyncComponent, markRaw } from "vue";

export default {
    components: { xStandardActions },
    props: {
        setup: Object,
        selected: { default: [] },
    },
    inject: ['isMultipleSelect'],
    data() {
        return {
            active: 'home',
            dynamicComponent: { default: '' },
        }
    },
    methods: {
        onFilter(isFilter) {
            this.$parent.$refs.filter.isFilter = isFilter;
        },
        onExport() {
            this.$parent.$refs.exportData.submit();
        },
        handleAttachments(message) {
            this.$emit('onAttachments', message);
        },
        activeTab(action) {
            this.active = action;
            this.dynamicComponent = this.setDynamicComponent();
        },
        setDynamicComponent() {
            if (this.active == "moreActions" && this.setup.settings.isMoreActions != false && this.setup.settings.xMoreActions != null) {
                // return markRaw(defineAsyncComponent(() => import.meta.glob(`./${this.setup.settings.xMoreActions}.vue`) ))
                return markRaw(defineAsyncComponent(() => import(`@/${this.setup.settings.xMoreActions}.vue`)))
            }
            // else if(this.active == "reports" && this.setup.settings.isReports != false){
            //     return markRaw(defineAsyncComponent(() => import(`@/Admin/Reports/${/[^/]*$/.exec(this.setup.settings.xFolder)[0]+'/ReportsTab'}.vue`)))
            // }
            // else if(this.active == "charts" && this.setup.settings.isCharts != false){
            //     return markRaw(defineAsyncComponent(() => import(`@/Admin/Charts/${/[^/]*$/.exec(this.setup.settings.xFolder)[0]+'/ChartsTab'}.vue`)))
            // }
        },
        onMultipleSelect() {
            this.isMultipleSelect.value = !this.isMultipleSelect.value
        }
    },
}
</script>
