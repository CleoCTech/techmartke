<template>
    <div>
        <Head title="Church Information" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                   
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.name"/>
                                <!-- <x-input type="text" v-model="form.name"/> -->
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Short Name</template>
                            <template #value><x-input type="text" v-model="form.shortName"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Establishment Date</template>
                            <template #value><x-input type="date" v-model="form.establishmentDate"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Vision</template>
                            <template #value><x-input type="text" v-model="form.vision"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Mission</template>
                            <template #value><x-input type="text" v-model="form.mission"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Location</template>
                            <template #value><x-input type="text" v-model="form.location"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Emails</template>
                            <template #value><x-input type="text" v-model="form.emails"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Phone Numbers</template>
                            <template #value><x-input type="text" v-model="form.phoneNumbers"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Address</template>
                            <template #value><x-input type="text" v-model="form.address"/></template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>About Short</template>
                            <template #value><x-input type="text" v-model="form.about_short"/></template>
                        </x-form-group>
                    </x-grid-col>
                    
                    
                    <x-grid-col class="sm:col-span-3">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>History</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.history" @ready="cardData != null? form.history = cardData.history :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="sm:col-span-3">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>About</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.about" @ready="cardData != null? form.about = cardData.about :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="sm:col-span-3">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Core Values</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.core_values" @ready="cardData != null? form.core_values = cardData.core_values :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                   
                    <x-grid-col class="sm:col-span-2">
                        <x-form-group>
                            <template #label>Logo</template>
                            <template #value>
                                <img v-if="show_image == '' && form.logo != null" :src="$page.props.storagePaths[setup.settings.storageName].images.readPath+form.logo" class="h-20 w-20">
                                <x-input type="file" @input="onFileChange($event, form.logo, show_image)" ref="input" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                <img v-if="show_image != ''" :src="show_image" class="h-20 w-20">
                            </template>
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

const file = ref(null);

const form = reactive({
    uuid: null,
    name: null,
    shortName: null,
    establishmentDate: null,
    vision: null,
    mission: null,
    quality: null,
    history: null,
    location: null,
    about: null,
    about_short: null,
    about_newsletter: null,
    total_people_helped: null,
    manager_name: null,
    manager_custom_title: null,
    manager_image: null,
    welcome_message: null,
    emails: null,
    phoneNumbers: null,
    address: null,
    logo: null,
    total_students: null,
    teachers: null,
    schools: null,
    graduate_students: null,
    motto: null,
    core_values: null,
    fee_naration: null,
})

const show_image = ref('')
const show_image1 = ref('')

const handleFileChange = (event) => {
    // form.exam_path = event.target.files[0];
    // form.exam_path = Array.from(event.target.files);
    form.cover_image = Array.from(event.target.files);
};

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid
        form.name = props.cardData.company_name
        form.shortName = props.cardData.short_name
        form.establishmentDate = props.cardData.establishment_date
        form.vision = props.cardData.vision
        form.motto = props.cardData.motto
        form.core_values = props.cardData.core_values
        form.mission = props.cardData.mission
        form.quality = props.cardData.quality
        form.about_newsletter = props.cardData.about_newsletter
        form.total_people_helped = props.cardData.total_people_helped
        form.manager_name = props.cardData.manager_name
        form.manager_custom_title = props.cardData.manager_custom_title
        form.manager_image = props.cardData.manager_image
        form.about_short = props.cardData.about_short
        form.about = props.cardData.about
        form.history = props.cardData.history
        form.welcome_message = props.cardData.welcome_message
        form.location = props.cardData.location
        form.whatWeDo = props.cardData.what_we_do
        form.emails = props.cardData.emails
        form.phoneNumbers = props.cardData.phone_numbers
        form.address = props.cardData.address
        form.logo = props.cardData.logo
        form.total_students = props.cardData.total_students
        form.teachers = props.cardData.teachers
        form.graduate_students = props.cardData.graduate_students
        form.schools = props.cardData.schools
        form.fee_naration = props.cardData.fee_naration
    }
    
    
    
}

// function convertToUppercase() {
//     form.code = form.code.toUpperCase();
// }

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