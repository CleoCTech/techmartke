<template>
    <div ref="panelContent"
        class="absolute inset-0 sm:left-auto z-50 shadow-xl transition-transform duration-200 ease-in-out"
        :class="createEditPanelOpen ? 'translate-x-0' : 'translate-x-full'">


        <div class="sticky top-16 bg-slate-50 dark:bg-gradient-to-b dark:from-slate-800 dark:to-slate-900 overflow-x-hidden overflow-y-auto no-scrollbar shrink-0 border-l border-slate-200 dark:border-slate-700 w-full sm:w-[590px] h-[calc(100dvh-64px)]">

            <button ref="closeBtn" @click.stop="$emit('close-transactionpanel')"
                class="absolute top-0 right-0 mt-6 mr-6 group p-2">
                <svg class="w-4 h-4 fill-slate-400 group-hover:fill-slate-600 pointer-events-none" viewBox="0 0 16 16"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="m7.95 6.536 4.242-4.243a1 1 0 1 1 1.415 1.414L9.364 7.95l4.243 4.242a1 1 0 1 1-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 0 1-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 0 1 1.414-1.414L7.95 6.536Z" />
                </svg>
            </button>

            <div class="py-8 px-4 lg:px-8">
                <div class="max-w-sm mx-auto lg:max-w-none">
                    <form class="form" v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">

                    <div class="text-slate-800 dark:text-slate-100 font-semibold text-center mb-1">Unit Details</div>
                    <!-- <div class="text-sm text-center italic">22/01/2022, 8:56 PM</div> -->

                    <!-- Details -->
                    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
                        <x-panel>
                            <template #title>Edit</template>
                            <template #body>
                                    <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-5">
                                        <div class="sm:w-1/3">
                                            <label class="block text-sm font-medium mb-1" for="name">Unit Name</label>
                                            <TextInput type="text" v-model="form.unitName"/>
                                        </div>
                                        <div class="sm:w-1/3">
                                            <label class="block text-sm font-medium mb-1" for="business-id">Bedrooms</label>
                                            <TextInput type="text" v-model="form.bedrooms"/>
                                        </div>
                                        <div class="sm:w-1/3">
                                            <label class="block text-sm font-medium mb-1" for="location">Bathrooms</label>
                                            <TextInput type="text" v-model="form.bathrooms"/>
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <div class="text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">Appliances</div>
                                        <div>
                                            <label class="sr-only" for="notes">Appliances</label>
                                            <textarea id="notes" v-model="form.appliances" class="form-textarea w-full focus:border-slate-300" rows="4"
                                                placeholder="Write a note…"></textarea>
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <div class="text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">Description</div>
                                        <div>
                                            <label class="sr-only" for="notes">Description</label>
                                            <textarea id="notes" v-model="form.description" class="form-textarea w-full focus:border-slate-300" rows="4"
                                                placeholder="Write a note…"></textarea>
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <div class="text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">Other Info</div>
                                        <div>
                                            <label class="sr-only" for="notes">Description</label>
                                            <textarea id="notes" v-model="form.other" class="form-textarea w-full focus:border-slate-300" rows="4"
                                                placeholder="Write a note…"></textarea>
                                        </div>
                                    </div>
                                    <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-5">
                                        <div class="w-2/2">
                                            <label class="block text-sm font-medium mb-1" for="name">Square Unit Size</label>
                                            <TextInput type="text" v-model="form.squareUnitSize"/>
                                        </div>
                                    </div>
                                    <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-5">
                                        <div class="sm:w-1/3">
                                            <label class="block text-sm font-medium mb-1" for="name">Occupied</label>
                                            <x-select v-model="form.isOccupied">
                                                <option value="">--select--</option>
                                                <option v-for="(occupied,index) in transactionData.data.setup.occupied" :key="index" :value="occupied.id">{{occupied.caption}}</option>
                                            </x-select>
                                        </div>
                                        <div class="sm:w-1/3">
                                            <label class="block text-sm font-medium mb-1" for="name">Furnished</label>
                                            <x-select v-model="form.furnished">
                                                <option value="">--select--</option>
                                                <option v-for="(furnished,index) in transactionData.data.setup.furnished" :key="index" :value="furnished.id">{{furnished.caption}}</option>
                                            </x-select>
                                        </div>
                                        <div class="sm:w-1/3">
                                            <label class="block text-sm font-medium mb-1" for="name">Status</label>
                                            <x-select v-model="form.status">
                                                <option value="">--select--</option>
                                                <option v-for="(status,index) in transactionData.data.setup.statuses" :key="index" :value="status.id">{{status.caption}}</option>
                                            </x-select>
                                        </div>
                                    </div>
                                
                            </template>
                        </x-panel>
                        
                    </div> 
                    

                    <!-- Download / Report -->
                    <div class="flex items-center space-x-3 mt-6">
                        <div class="w-1/2">
                            
                            <button type="submit"
                                class="btn w-full dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-emerald-500 dark:text-emerald-300">
                                <svg class="w-3 h-3 shrink-0 fill-current text-emerald-500 mr-2" viewBox="0 0 12 12">
                                <path d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                                </svg>
                                <span class="ml-2">Submit</span>
                            </button>
                        </div>
                        <div class="w-1/2">
                            <button ref="closeBtn" @click.stop="$emit('close-transactionpanel')"
                                class="btn w-full dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-rose-500">
                                <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 16 16">
                                    <path
                                        d="M7.001 3h2v4h-2V3Zm1 7a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM15 16a1 1 0 0 1-.6-.2L10.667 13H1a1 1 0 0 1-1-1V1a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1ZM2 11h9a1 1 0 0 1 .6.2L14 13V2H2v9Z" />
                                </svg>
                                <span class="ml-2">Close</span>
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
// import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit2';
import { ref, onMounted, onUnmounted, reactive } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import xGrid from '@/Components/Grid.vue'
import xGridCol from '@/Components/GridCol.vue'
import xLoading from '@/Components/Loading.vue'
import xPanel from '@/Components/Panel.vue'
import xFormGroup from '@/Components/FormGroup.vue'
import xInput from '@/Components/TextInput.vue'
import xSelect from '@/Components/Select.vue'
import xTextarea from '@/Components/Textarea.vue'
import xCheckbox from '@/Components/Checkbox.vue'
import AppLayout from '@/System/Layouts/AppLayout.vue'
import xCreateEditTemplate from '@/System/Pages/Templates/CRUD/CreateEdit.vue'
import xButton from '@/Components/Button.vue'
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import { provide, getCurrentInstance } from 'vue'
import {useNotify} from "@/Composables/useNotify";

let {notification} = useNotify()

// const props = defineProps({...createEditProps, createEditPanelOpen:Boolean, transactionData:Object})
const props = defineProps({
    createEditPanelOpen:Boolean,
    transactionData:Object,
    setup:Object,
    selected:{default:[]},
    cardData:Object,
})

const ckeditor = CKEditor.component;

const form = reactive({
    uuid: null,
    unitName: null,
    propertyId: null,
    description: null,
    isOccupied: null,
    furnished: null,
    other: null,
    bedrooms: null,
    bathrooms: null,
    squareUnitSize: null,
    appliances: null,
    createdBy: null,
    status: null,
})

let setData = () => {
    if (props.transactionData.data.cardData != null && props.transactionData.data.cardData.uuid != null) {
        form.uuid = props.transactionData.data.cardData.uuid
        form.propertyId = props.transactionData.data.cardData.property_id
        form.unitName = props.transactionData.data.cardData.unit_name
        form.description = props.transactionData.data.cardData.description
        form.other = props.transactionData.data.cardData.other
        form.isOccupied = props.transactionData.data.cardData.is_occupied
        form.furnished = props.transactionData.data.cardData.furnished
        form.bedrooms = props.transactionData.data.cardData.bedrooms
        form.squareUnitSize = props.transactionData.data.cardData.square_unit_size
        form.appliances = props.transactionData.data.cardData.appliances
        form.status = props.transactionData.data.cardData.status
        form.createdBy = props.transactionData.data.cardData.created_by
    }
}

const panelContent = ref('');
const closeBtn = ref('');

// close on click outside
const clickHandler = ({ target }) => {
    if (
        !props.createEditPanelOpen.value ||
        panelContent.value.contains(target) ||
        closeBtn.value.contains(target)
    ) return
    emit('close-transactionpanel')
}

// close if the esc key is pressed
const keyHandler = ({ keyCode }) => {
    if (!props.createEditPanelOpen.value || keyCode !== 27) return
    emit('close-transactionpanel')
}

onMounted(() => {
    console.log(props.transactionData.data);
    setData()
    document.addEventListener('click', clickHandler)
    document.addEventListener('keydown', keyHandler)
})

onUnmounted(() => {
    document.removeEventListener('click', clickHandler)
    document.removeEventListener('keydown', keyHandler)
})


// const { editor,editorConfig, submit, onFileChange, ckeditor, xGrid,
//         xFormGroup,
//         xGridCol,
//         xLoading,
//         xPanel,
//         xInput,
//         xSelect,
//         xTextarea,
//         xCheckbox,
//         xButton,
//         AppLayout,
//         xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form )


</script>

<style scoped>
input[type="file"] {
}

.custom-file-input {
  background-color: blue;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}
/* 
label {
  background-color: blue;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

label::before {
  content: "Choose file";
}

label::after {
  content: "📁";
} */
</style>