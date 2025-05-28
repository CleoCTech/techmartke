<template>
    <div>
        <Head :title="form.first_name" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                   
                    <x-grid-col>
                        <x-form-group>
                            <template #label>First Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.first_name"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Last Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.last_name"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <x-grid-col>
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
// import Chart from 'primevue/chart';
// import MeterGroup from 'primevue/metergroup';
// import 'primevue/resources/themes/aura-dark-blue/theme.css'

const chartData = ref();
const chartOptions = ref();

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

    // console.log(props.setup.bindedPriceIds);
    // chartData.value = setChartData();
    // chartOptions.value = setChartOptions();
})

const file = ref(null);

const form = reactive({
    uuid: null,
    first_name: '',
    last_name: '',
    email: '',
    grade_level: '', 
    mother_name: '',
    father_name: '',
    contact_number: '',
    present_address: '',
    date_of_birth: '',  // Ensure this is compatible with date input formats
    birth_cert_no: '',
    gender: '',  
    status: null,       // This could be a select dropdown as well
    current_school: '',
    student_photo: null,    // File object for student's photo
    birth_document: null 
})


const show_image = ref('')
const show_image1 = ref('')

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.first_name = props.cardData.first_name;
        form.last_name = props.cardData.last_name;
        form.status = props.cardData.status;
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