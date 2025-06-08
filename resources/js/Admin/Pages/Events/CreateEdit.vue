<template>
    <div>
        <Head title="Event" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid class="gap-4">
                   
                    <x-grid-col class="col-span-12 sm:col-span-4">
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <TextInput type="text" v-model="form.title" class="w-full"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <x-grid-col class="col-span-12 sm:col-span-6">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Description</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.description" @ready="cardData != null? form.description = cardData.description :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Event Type</template>
                            <template #value>
                                <x-select v-model="form.event_type" class="w-full">
                                    <option value="">--select--</option>
                                    <option v-for="(type,index) in setup.event_types" :key="index" :value="type.id">{{type.caption}}</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-6">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Content(optional)</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.content" @ready="cardData != null? form.content = cardData.content :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Start Date</template>
                            <template #value><x-input type="date" v-model="form.start_date" class="w-full"/></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>End Date</template>
                            <template #value><x-input type="date" v-model="form.end_date" class="w-full"/></template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Location</template>
                            <template #value><x-input type="text" v-model="form.location" class="w-full"/></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Start Time</template>
                            <template #value><x-input type="time" v-model="form.start_time" class="w-full"/></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>End Time</template>
                            <template #value><x-input type="time" v-model="form.end_time" class="w-full"/></template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Type</template>
                            <template #value>
                                <x-input type="text" v-model="form.type" class="w-full"/>
                            </template>
                        </x-form-group>
                    </x-grid-col> -->

                    <x-grid-col class="col-span-12 sm:col-span-6">
                        <x-form-group>
                            <template #label>Speakers (comma separated)</template>
                            <template #value>
                                <x-input type="text" v-model="form.speakers" class="w-full" placeholder="e.g. John Doe, Jane Smith"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Attendees</template>
                            <template #value>
                                <x-input type="number" v-model="form.attendees" class="w-full"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Price</template>
                            <template #value>
                                <x-input type="text" v-model="form.price" class="w-full"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                </x-grid>

                <x-grid-col class="col-span-12 sm:col-span-3">
                        <x-form-group>
                            <template #label>Cover Image</template>
                            <template #value>
                                <div class="flex flex-col space-y-2">
                                    <img v-if="show_image == '' && form.cover_image != null" 
                                         :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" 
                                         class="h-20 w-20 object-cover rounded">
                                    
                                    <x-input type="file" 
                                             @input="onFileChange($event, 'cover_image', show_image)" 
                                             ref="input" 
                                             class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                    
                                    <img v-if="show_image != ''" 
                                         :src="show_image" 
                                         class="h-20 w-20 object-cover rounded">
                                </div>
                            </template>                        
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

                <div class="flex justify-center py-3 mt-4">
                    <x-button type="submit" class="w-full sm:w-auto px-6 py-2">Submit</x-button>
                </div>
            </form>
        </x-create-edit-template>
    </div>
</template>

<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit'
import { ref, reactive, computed, onMounted, watch } from 'vue'

const props = defineProps({
    ...createEditProps,
    branches: Array,
})

onMounted(() => {
    // console.log(props.setup.bindedPriceIds);
})

const file = ref(null);

const form = reactive({
    uuid: null,
    title: null,
    description: null,
    content: null,
    // date: null,
    start_date: null,
    end_date: null,
    location: null,
    branch_id: null,
    online_link: null,
    is_for_all_branches: null,
    event_type: null,
    cover_image: null,
    status: null,
    publish_time: null,
    // type: null,
    speakers: null,
    attendees: null,
    price: null,
    start_time: null,
    end_time: null,
})
const show_image = ref('')
const test = ref('gggg')

const handleFileChange = (event) => {
    form.cover_image = Array.from(event.target.files);
};

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.branch_id = props.cardData.branch_id;
        form.event_type = props.cardData.event_type;
        form.description = props.cardData.description;
        form.content = props.cardData.content;
        // form.date = props.cardData.date;
        form.date = props.cardData.date;
        form.start_date = props.cardData.start_date;
        form.end_date = props.cardData.end_date;
        form.location = props.cardData.location;
        form.online_link = props.cardData.online_link;
        form.is_for_all_branches = props.cardData.is_for_all_branches === 1; // Convert 1 to true, 0 to false
        form.cover_image = props.cardData.cover_image;
        form.status = props.cardData.status;
        form.publish_time = props.cardData.publish_time2;
        // form.type = props.cardData.type;
        form.speakers = (() => {
            if (typeof props.cardData.speakers === 'string') {
                try {
                    const parsed = JSON.parse(props.cardData.speakers);
                    return Array.isArray(parsed) ? parsed.join(', ') : props.cardData.speakers;
                } catch (e) {
                    return props.cardData.speakers;
                }
            } else if (Array.isArray(props.cardData.speakers)) {
                return props.cardData.speakers.join(', ');
            } else {
                return props.cardData.speakers;
            }
        })();
        form.attendees = props.cardData.attendees;
        form.price = props.cardData.price;
        form.start_time = props.cardData.start_time;
        form.end_time = props.cardData.end_time;
    }
}

const toggleBranchVisibility = () => {
  if (form.is_for_all_branches) {
    form.branch_id = null
  }
}

watch(() => form.is_for_all_branches, (newValue) => {
  if (newValue) {
    form.branch_id = null
  }
})

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

    let submitEvent = submit; // Declare submitEvent (instead of re-declaring submit) and assign from the original submit

    // Override the submit function (using submitEvent) to handle speakers conversion
    const originalSubmit = submitEvent;
    submitEvent = () => {
        // Convert comma-separated speakers string back to array (and then JSON) before submitting
        const formData = { ...form };
        if (formData.speakers) {
            // Ensure speakers is treated as a string before splitting
            formData.speakers = String(formData.speakers).split(',').map(speaker => speaker.trim());
        } else {
            formData.speakers = [];
        }
        // Pass the modified formData (with speakers as a JSON string) to the original submit function
        originalSubmit({ ...formData, speakers: JSON.stringify(formData.speakers) });
    };
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