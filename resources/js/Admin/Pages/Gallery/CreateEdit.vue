<template>
    <div>
        <Head title="Gallery" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                   
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <x-input type="text" v-model="form.title"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Category</template>
                            <template #value>
                                <x-input type="text" v-model="form.category"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- <x-grid-col class="sm:col-span-6">
                        <x-form-group class="sm:grid-cols-1">
                        <template #label>Is For All Branches?</template>
                        <template #value>
                            <input type="checkbox" v-model="form.is_global" @change="toggleBranchVisibility">
                        </template>
                        </x-form-group>
                    </x-grid-col> -->

                    <!-- <x-grid-col class="sm:col-span-4" v-if="!form.is_global">
                        <x-form-group>
                        <template #label>Branch</template>
                        <template #value>
                            <x-select v-model="form.branch_id">
                            <option value="">-- Select Branch --</option>
                            <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                                {{ branch.name }}
                            </option>
                            </x-select>
                        </template>
                        </x-form-group>
                    </x-grid-col> -->

                    <x-grid-col class="sm:col-span-6">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Description</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.description" @ready="cardData != null? form.description = cardData.description :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Sequence</template>
                            <template #value>
                                <x-input 
                                    type="number" 
                                    v-model="form.sequence"
                                    :placeholder="'Next available sequence: ' + (maxSequence + 1)"
                                />
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">
                                    Next available sequence: {{ maxSequence + 1 }}
                                </span>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-3">
                        <x-form-group>
                            <template #label>Cover Image</template>
                            <template #value>
                                <img v-if="show_image == '' && form.cover_image != null" :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" class="h-20 w-20">
                                <x-input type="file" @input="onFileChange($event, 'cover_image', show_image)" ref="input" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                <img v-if="show_image != ''" :src="show_image" class="h-20 w-20">
                            </template>                        
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-3">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status">
                                    <option value="">--select--</option>
                                    <option v-for="(status,index) in setup.statuses" :key="index" :value="status.id">{{status.caption}}</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col v-if="form.status == 3">
                        <x-form-group>
                            <template #label>Publish Time</template>
                            <template #value><x-input type="datetime-local" v-model="form.publish_time"/></template>
                        </x-form-group>
                    </x-grid-col>

                </x-grid>
                 

                <div class="flex justify-center py-3">
                    <x-button type="submit">Submit</x-button>
                </div>

            </form>

        </x-create-edit-template>

        
    </div>
</template>
<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit'

import { ref, reactive, computed, onMounted, watch   } from 'vue'

const props = defineProps({
    ...createEditProps,
    maxSequence: {
        type: Number,
        default: 0
    }
})


onMounted(() => {
    // Set default sequence to next available number for new records
    if (!props.cardData?.uuid && !form.sequence) {
        form.sequence = props.maxSequence + 1
    }
})


const form = reactive({
    uuid: null,
    title: null,
    category: null,
    description: null,
    sequence: null,
    is_global: null,
    cover_image: null,
    status: null,
    publish_time: null,
})


function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.category = props.cardData.category;
        form.description = props.cardData.description;
        form.is_global = props.cardData.is_global === 1; // Convert 1 to true, 0 to false;
        form.sequence = props.cardData.sequence;
        form.cover_image = props.cardData.cover_image;
        form.status = props.cardData.status;
        form.publish_time = props.cardData.publish_time2;
    }
    
}

// const toggleBranchVisibility = () => {
//   if (form.is_global) {
//     form.branch_id = null
//   }
// }

// watch(() => form.is_global, (newValue) => {
//   if (newValue) {
//     form.branch_id = null
//   }
// })
const { editor,editorConfig, submit, onFileChange, ckeditor, xGrid,
        xFormGroup,
        xGridCol,
        xLoading,
        xPanel,
        xInput,
        xSelect,
        xTextarea,
        xCheckbox,
        Checkbox,
        xButton,
        AppLayout,
        xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form )


</script>
<style scoped>
input[type="file"] {
  /* display: none; */
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
} */

/* label::after {
  content: "📷";
} */

</style>