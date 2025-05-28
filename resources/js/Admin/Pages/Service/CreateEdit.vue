<template>
    <div>
        <Head title="Service" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid class="gap-4">
                   
                    <x-grid-col class="col-span-12 sm:col-span-4">
                        <x-form-group>
                            <template #label>Service Title</template>
                            <template #value>
                                <TextInput type="text" v-model="form.title" required/>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-4">
                        <x-form-group>
                            <template #label>Presiding Minister/Leader</template>
                            <template #value>
                                <TextInput type="text" v-model="form.presiding_minister"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <x-grid-col class="col-span-12">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Summary</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.summary" @ready="cardData != null? form.summary = cardData.summary :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="col-span-12">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Content (optional)</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.content" @ready="cardData != null? form.content = cardData.content :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Service Date</template>
                            <template #value><x-input type="date" v-model="form.service_date" class="w-full"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status" class="w-full">
                                    <option value="">--select--</option>
                                    <option v-for="(status,index) in setup.statuses" :key="index" :value="status.id">{{status.caption}}</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col v-if="form.status == 3" class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Publish Time</template>
                            <template #value><x-input type="datetime-local" v-model="form.publish_time" class="w-full"/></template>
                        </x-form-group>
                    </x-grid-col>

                  
                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Cover Image</template>
                            <template #value>
                                <div class="flex flex-col space-y-2">
                                    <img v-if="show_image == '' && form.cover_image != null" 
                                         :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" 
                                         class="h-20 w-20 object-cover rounded">
                                   
                                    <TextInput type="file" @input="onFileChange($event,'cover_image','show_image')" 
                                              ref="input" 
                                              class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                    
                                    <img v-if="show_image != ''" 
                                         :src="show_image" 
                                         class="h-20 w-20 object-cover rounded">
                                </div>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                </x-grid>
                 
                <div class="flex justify-center py-3 mt-4">
                    <x-button type="submit" class="w-full sm:w-auto px-6 py-2">Submit</x-button>
                </div>
            </form>
        </x-create-edit-template>
    </div>
</template>

<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit'
import { ref, reactive, computed, onMounted } from 'vue'

const props = defineProps({
    ...createEditProps,
})

const value = ref([
    { label: 'Parameter A', color: '#34d399', value: 24 },
    { label: 'Parameter B', color: '#fbbf24', value: 16 },
    { label: 'Parameter C', color: '#60a5fa', value: 24 },
    { label: 'Parameter D', color: '#c084fc', value: 12 }
]);

onMounted(() => {
    // chartData.value = setChartData();
    // chartOptions.value = setChartOptions();
})

const file = ref(null);

const form = reactive({
    uuid: null,
    title: null,
    service_date: null,
    presiding_minister: null,
    summary: null,
    cover_image: null,
    svg: null,
    content: null,
    status: null,
    publish_time: null,
})
const show_image = ref('')

const handleFileChange = (event) => {
    form.cover_image = Array.from(event.target.files);
};

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.summary = props.cardData.summary;
        form.presiding_minister = props.cardData.presiding_minister;
        form.service_date = props.cardData.service_date;
        form.content = props.cardData.content;
        form.cover_image = props.cardData.cover_image;
        form.svg = props.cardData.svg;
        form.status = props.cardData.status;
        form.publish_time = props.cardData.publish_time2;
    }
}

const { editor, editorConfig, submit, onFileChange, ckeditor, xGrid,
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
        xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form)
</script>

<style scoped>
input[type="file"] {
  width: 100%;
  max-width: 100%;
}

.custom-file-input {
  background-color: blue;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

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