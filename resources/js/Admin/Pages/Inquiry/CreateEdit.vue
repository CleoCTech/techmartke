<template>
    <div>
        <Head title="Package" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                   
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <TextInput type="text" v-model="form.title"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col >
                        <x-form-group>
                            <template #label>Category</template>
                            <template #value>
                                <x-select v-model="form.category_id">
                                    <option value="">--select--</option>
                                    <option v-for="(category,index) in setup.categories" v-bind:key="index" :value="category.id" :selected="category.id == form.category_id">{{category.name}}</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Validity</template>
                            <template #value>
                                <TextInput type="text" v-model="form.validity" aria-placeholder="7 days"/>
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
                            <template #label>Description</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.content" @ready="cardData != null? form.content = cardData.content :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <input
                    class="shadow appearance-none border w-full py-2 px-2 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"
                    type="hidden" v-model="form.price" />
                    <input
                    class="shadow appearance-none border w-full py-2 px-2 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"
                    type="hidden" v-model="form.priceStatus" />

                    <x-grid-col class="sm:col-span-6 mt-5">
                        <div v-for="(priceCategory, index) in setup.priceCategories" :key="index" >
                            <x-form-group>
                                <template #label>{{priceCategory.name}} :</template>
                                <template #value>
                                    <TextInput type="text" :id="priceCategory.id" v-model="priceCategory.price" @change="onPriceChange($event)"/>
                                </template>
                            </x-form-group>

                            <x-form-group>
                                <template #label>Status:</template>
                                <template #value>
                                    <x-select :id="priceCategory.id" v-model="priceCategory.status"  @change="onStatusChange($event)">
                                        <option value="">--select--</option>
                                        <option v-for="(status,index) in setup.priceStatuses" v-bind:key="index" :value="status.id" >{{status.caption}}</option>
                                    </x-select>
                                </template>
                            </x-form-group>
                            
                        </div>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-12">
                        <div class="inline-block p-2" id="checkboxes" v-for="(holiday, index) in setup.holidays" :key="index" >
                            <input type="hidden" name="" v-model="form.holiday">
                            <input type="checkbox" :value="holiday.id" v-model="holiday.checked" @click="onCheckChange($event)"
                            class="rounded p-1 border-gray-300 text-indigo-600 shadow-sm
                            focus:border-indigo-300 focus:ring
                            focus:ring-indigo-200 focus:ring-opacity-50">
                            <label>{{holiday.name}}</label>
                        </div>
                    </x-grid-col>
                   
                    <x-grid-col class="sm:col-span-3">
                        <x-form-group>
                            <template #label>Cover Image</template>
                            <template #value>
                                <img v-if="show_image == '' && form.cover_image != null" :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" class="h-20 w-20">
                                <input type="file" @input="onFileChange($event,'cover_image','show_image')" ref="input" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                <img v-if="show_image != ''" :src="show_image" class="h-20 w-20">
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <!-- <x-grid-col v-if="!form.uuid" class="sm:col-span-2">
                        <x-form-group>
                            <template #label>Image</template>
                            <template #value>
                                <TextInput type="file" id="file" ref="fileInput" @change="handleFileChange" />
                            </template>
                        </x-form-group>
                        
                    </x-grid-col> -->
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
    title: null,
    category_id: null,
    content: null,
    extra_info: null,
    cover_image: null,
    validity: null,
    status: null,
    publish_time: null,
    holiday: [],
    price: [],
    price2: [],
    bindedHolidays: [],
    priceStatus: [],
    priceStatus2:[],
    exam_path: Array,
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
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.content = props.cardData.content;
        form.cover_image = props.cardData.cover_image;
        form.validity = props.cardData.validity;
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