<template>
    <div>
        <Head title="Branch" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Name -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.name" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Location -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Location</template>
                            <template #value>
                                <TextInput type="text" v-model="form.location" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Contact Person -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Headed By</template>
                            <template #value>
                                <TextInput type="text" v-model="form.contact_person" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Contact Phone -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Contact Phone(optional)</template>
                            <template #value>
                                <TextInput type="text" v-model="form.contact_phone" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Contact Email -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Contact Email(optional)</template>
                            <template #value>
                                <TextInput type="email" v-model="form.contact_email" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Description -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Description (optional)</template>
                            <template #value>
                                <TextInput type="text" v-model="form.description" />
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

onMounted(() => {
})

const file = ref(null);

const form = reactive({
    uuid: null,
    name: null,
    location: null,
    contact_person: null,
    contact_phone: null,
    contact_email: null,
    description: null,
    status: null,
})


function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.name = props.cardData.name;
        form.location = props.cardData.location;
        form.contact_person = props.cardData.contact_person;
        form.contact_phone = props.cardData.contact_phone;
        form.contact_email = props.cardData.contact_email;
        form.status = props.cardData.status;
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