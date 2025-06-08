<template>
    <div>
        <Head title="Staff" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Row 1: Name, Title, Email -->
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>Name</template>
                            <template #value>
                                <x-input type="text" v-model="form.name"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <x-input type="text" v-model="form.title"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>Email</template>
                            <template #value>
                                <x-input type="email" v-model="form.email"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Row 2: Phone No., Sequence, Status -->
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>Phone No.</template>
                            <template #value>
                                <x-input type="text" v-model="form.phone_no"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                     <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>Sequence</template>
                            <template #value><x-input type="text" v-model="form.sequence"/></template>
                        </x-form-group>
                    </x-grid-col>
                   <x-grid-col class="md:col-span-4">
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

                    <!-- Row 3: Social Media Handles (Facebook, LinkedIn, X) -->
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>Facebook Handle</template>
                            <template #value><x-input type="text" v-model="form.facebook_link"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>LinkedIn Handle</template>
                            <template #value><x-input type="text" v-model="form.linkedin_link"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>X Handle</template>
                            <template #value><x-input type="text" v-model="form.x_link"/></template>
                        </x-form-group>
                    </x-grid-col>

                     <!-- Row 4: Social Media Handles (WhatsApp, Youtube), Photo -->
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>WhatsApp Handle</template>
                            <template #value><x-input type="text" v-model="form.whatsapp_link"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>Youtube Handle</template>
                            <template #value><x-input type="text" v-model="form.youtube_link"/></template>
                        </x-form-group>
                    </x-grid-col>
                     <x-grid-col class="md:col-span-4">
                        <x-form-group>
                            <template #label>Photo</template>
                            <template #value>
                                <img v-if="show_image == '' && form.cover_image != null" :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" class="h-20 w-20">
                                <x-input type="file"  @input="onFileChange($event, 'cover_image', show_image)" ref="input" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                <img v-if="show_image != ''" :src="show_image" class="h-20 w-20">
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Row 5 (Full Width) -->
                    <x-grid-col class="md:col-span-12">
                        <x-form-group>
                            <template #label>Expertise</template>
                            <template #value>
                                <TagInput
                                    v-model="form.expertise"
                                    placeholder="Add expertise areas (press Enter to add)"
                                    :suggestions="['Artificial Intelligence', 'Machine Learning', 'Data Science', 'Web Development', 'Mobile Development', 'Cloud Computing', 'DevOps', 'Cybersecurity']"
                                />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Row 6 -->
                    <x-grid-col class="md:col-span-6">
                        <x-form-group>
                            <template #label>Experience</template>
                            <template #value>
                                <x-input v-model="form.experience" placeholder="e.g., 15 years" />
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="md:col-span-6">
                        <x-form-group>
                            <template #label>Education</template>
                            <template #value>
                                <x-input v-model="form.education" placeholder="e.g., MS Software Engineering - Stanford" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Row 7 (Full Width) -->
                    <x-grid-col class="md:col-span-12">
                        <x-form-group>
                            <template #label>Achievements</template>
                            <template #value>
                                <x-textarea 
                                    v-model="form.achievements" 
                                    rows="4" 
                                    placeholder="e.g., Former Lead Engineer at Microsoft, Built systems serving 100M+ users"
                                />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Row 8 (Full Width) -->
                    <x-grid-col class="md:col-span-12">
                        <x-form-group>
                            <template #label>Quote</template>
                            <template #value>
                                <x-textarea 
                                    v-model="form.quote" 
                                    rows="3" 
                                    placeholder="e.g., Great software is built by great teams, and great teams start with great education."
                                />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Row 9 (Conditional) -->
                    <x-grid-col v-if="form.status == 3" class="md:col-span-4">
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

import { ref, reactive, computed, onMounted  } from 'vue'

const props = defineProps({
    ...createEditProps,
})


onMounted(() => {

    // console.log(props.setup.bindedPriceIds);
    // chartData.value = setChartData();
    // chartOptions.value = setChartOptions();
})

const show_image = ref('');

const handleFileChange = (event) => {
    // form.exam_path = event.target.files[0];
    // form.exam_path = Array.from(event.target.files);
    form.cover_image = Array.from(event.target.files);
};
const form = reactive({
    uuid: null,
    name: '',
    title: '',
    email: '',
    phone_no: '',
    facebook_link: '',
    whatsapp_link: '',
    youtube_link: '',
    linkedin_link: '',
    x_link: '',
    expertise: [],
    experience: '',
    education: '',
    achievements: '',
    quote: '',
    image: null,
    sequence: null,
    status: null,
    publish_time: null
});


function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.name = props.cardData.name;
        form.phone_no = props.cardData.phone_no;
        form.email = props.cardData.email;
        form.title = props.cardData.title;
        form.facebook_link = props.cardData.facebook_link;
        form.whatsapp_link = props.cardData.whatsapp_link;
        form.x_link = props.cardData.x_link;
        form.linkedin_link = props.cardData.linkedin_link;
        form.youtube_link = props.cardData.youtube_link;
        form.cover_image = props.cardData.cover_image;
        form.status = props.cardData.status;
        form.sequence = props.cardData.sequence;
        form.publish_time = props.cardData.publish_time2;
        form.position = props.cardData.position;
        form.expertise = props.cardData.expertise || [];
        form.experience = props.cardData.experience;
        form.education = props.cardData.education;
        form.achievements = props.cardData.achievements;
        form.quote = props.cardData.quote;
        
        if (props.cardData.image) {
            show_image.value = props.cardData.image;
        }
    }
    
}

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
        xCreateEditTemplate, TextInput, TagInput } = useCreateEdit(props, setData, form )


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