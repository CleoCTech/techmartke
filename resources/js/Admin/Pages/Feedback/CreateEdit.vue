<template>
    <div>
        <Head title="Feedback" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                   
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.name"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Email</template>
                            <template #value>
                                <TextInput type="text" v-model="form.email"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <!-- <x-grid-col>
                        <x-form-group>
                            <template #label>Rating</template>
                            <template #value>
                                <TextInput type="text" v-model="form.rating"/>
                            </template>
                        </x-form-group>
                    </x-grid-col> -->
                    <x-grid-col >
                        <x-form-group>
                            <template #label>Rating</template>
                            <template #value>
                                <x-select v-model="form.rating">
                                    <option value="">--select--</option>
                                    <option v-for="rating in ratingMax" :key="rating.value" :value="rating.value">
                                    {{ rating.label }}
                                    </option>
                                </x-select>
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
                    <x-grid-col v-if="form.status == 3">
                        <x-form-group>
                            <template #label>Publish Time</template>
                            <template #value><x-input type="datetime-local" v-model="form.publish_time"/></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-12">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Feedback</template>
                            <template #value>
                                <x-textarea v-model="form.feedback"></x-textarea>
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
const ratingMax = [
  { value: 1, label: '1' },
  { value: 2, label: '2' },
  { value: 3, label: '3' },
  { value: 4, label: '4' },
  { value: 5, label: '5' }
];
onMounted(() => {

    // console.log(props.setup.bindedPriceIds);
    // chartData.value = setChartData();
    // chartOptions.value = setChartOptions();
})

const file = ref(null);

const form = reactive({
    uuid: null,
    name: null,
    email: null,
    feedback: null,
    rating: null,
    status: null,
    publish_time: null,
})

const show_image = ref('')


function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.name = props.cardData.name;
        form.feedback = props.cardData.feedback;
        form.rating = props.cardData.rating;
        form.email = props.cardData.email;
        form.status = props.cardData.status;
        // this.form.category_id = this.cardData.category_id;
        form.publish_time = props.cardData.publish_time2;
    }
    
    
    
}

// function convertToUppercase() {
//     form.code = form.code.toUpperCase();
// }
const onCheckChange = (event) => {
    console.log(form.holiday);
    if (event.target.checked) {
        form.holiday.push(event.target.value);
        console.log(form.holiday);
    } else {
        console.log("Value", +event.target.value);
        var value = event.target.value;
        var holidayIndex = form.holiday.indexOf(value);//get  id index
        console.log("Index Value: " + holidayIndex);
        if (holidayIndex !== -1) {
            form.holiday.splice(holidayIndex, 1);
        }
    }
}
const onPriceChange = (event) => {
    if (form.price2.hasOwnProperty(event.target.id)) {
        form.price2[event.target.id] = event.target.value;
        form.price = JSON.stringify(form.price2);
        console.log(form.price);
    } else {
        console.log(event.target.id);
        form.price2 = [event.target.id, event.target.value];
        form.price = JSON.stringify(form.price2);
        console.log(form.price);
    }
}
const onStatusChange = (event) => {
    if (form.priceStatus.hasOwnProperty(event.target.id)) {
        form.priceStatus2[event.target.id] = event.target.value;
        form.priceStatus = JSON.stringify(form.priceStatus2);
        console.log(form.priceStatus);
    } else {
        form.priceStatus2 = [event.target.id, event.target.value];
        form.priceStatus = JSON.stringify(form.priceStatus2);
        console.log(form.priceStatus);
    }
}

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
            {
                type: 'line',
                label: 'Dataset 1',
                borderColor: documentStyle.getPropertyValue('--orange-500'),
                borderWidth: 2,
                fill: false,
                tension: 0.4,
                data: [50, 25, 12, 48, 56, 76, 42]
            },
            {
                type: 'bar',
                label: 'Dataset 2',
                backgroundColor: documentStyle.getPropertyValue('--gray-500'),
                data: [21, 84, 24, 75, 37, 65, 34],
                borderColor: 'white',
                borderWidth: 2
            },
            {
                type: 'bar',
                label: 'Dataset 3',
                backgroundColor: documentStyle.getPropertyValue('--cyan-500'),
                data: [41, 52, 24, 74, 23, 21, 32]
            }
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
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