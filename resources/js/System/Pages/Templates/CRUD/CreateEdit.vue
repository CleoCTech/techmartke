<template>
    <div>
        <x-menu-bar v-bind="{ setup, selected }" @onAttachments="handleAttachments" class="w-full"></x-menu-bar>
        <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
            <!-- Main Content Area - Flex column on mobile, row on desktop -->
            <div class="flex flex-col md:flex-row">
                <!-- Left Column -->
                <div class="flex-grow w-full md:w-auto">
                    <div class="mb-8">
                        <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold">{{ setup.pageTitle }}</h1>
                    </div>

                    <x-panel>
                        <template #title>{{ setup.pageTitle }}</template>
                        <template #body>
                            <slot></slot>
                        </template>
                    </x-panel>

                    <div class="mb-8 border-t border-slate-200 dark:border-slate-700">
                        <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold mt-5">Lines</h1>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-700"></div>
                </div>

                <!-- Right Column (x-attachment) - Full width on mobile, fixed width on desktop -->
                <div v-if="isAttachments && setup.pageType == 'edit'" class="w-full md:w-auto md:flex-none md:pl-4 mt-8 md:mt-0">
                    <x-attachment class="w-full md:max-w-xs" :setup="setup" :selected="selected" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import xPanel from '@/Components/Panel.vue'
import xButton from '@/Components/Button.vue'
import xMenuBar from '@/Components/MenuBar.vue'
import xAttachment from '@/Components/Attachment.vue'
import ListPart from '@/System/Pages/Templates/CRUD/ListPart.vue'

import { onMounted, ref } from 'vue';

const props = defineProps({
    setup: Object,
    lines: Object,
    listPageTitle: String,
    selected: {
        type: Array,
        default: null,
    },
});

const isAttachments = ref(false);

onMounted(() => {
    if (props.selected) {
        isAttachments.value = true;
    }
})

const handleAttachments = (message) => {
    console.log(message);
    isAttachments.value = message;
}


</script>
<style scoped>
/* Make CKEditor responsive */
:deep(.ck-editor__editable) {
  min-height: 200px;
  max-height: 400px;
}

/* Responsive adjustments for mobile */
@media (max-width: 640px) {
  :deep(.ck-editor__editable) {
    min-height: 150px;
  }
  
  :deep(.ck-toolbar) {
    flex-wrap: wrap;
  }
}
</style>