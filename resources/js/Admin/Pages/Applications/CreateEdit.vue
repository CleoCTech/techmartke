<template>
    <div>
        <Head :title="form.first_name" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Personal Information -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>First Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.first_name" required/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Last Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.last_name" required/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Email</template>
                            <template #value>
                                <TextInput type="email" v-model="form.email" required/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Phone</template>
                            <template #value>
                                <TextInput type="tel" v-model="form.phone" required/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Date of Birth</template>
                            <template #value>
                                <TextInput type="date" v-model="form.date_of_birth" required/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <!-- Program Information -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Program</template>
                            <template #value>
                                <x-select v-model="form.program" required>
                                    <option value="">--select--</option>
                                    <option value="Software Development">Software Development</option>
                                    <option value="Data Science & Analytics">Data Science & Analytics</option>
                                    <option value="Cybersecurity">Cybersecurity</option>
                                    <option value="Mobile App Development">Mobile App Development</option>
                                    <option value="Artificial Intelligence">Artificial Intelligence</option>
                                    <option value="UI/UX Design">UI/UX Design</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Start Date</template>
                            <template #value>
                                <x-select v-model="form.start_date" required>
                                    <option value="">--select--</option>
                                    <option value="fall-2024">Fall 2024 (September)</option>
                                    <option value="spring-2025">Spring 2025 (January)</option>
                                    <option value="summer-2025">Summer 2025 (May)</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Study Format</template>
                            <template #value>
                                <x-select v-model="form.study_format" required>
                                    <option value="">--select--</option>
                                    <option value="in-person">In-Person</option>
                                    <option value="online">Online</option>
                                    <option value="hybrid">Hybrid (Online + In-Person)</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <!-- Additional Information -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Career Goals</template>
                            <template #value>
                                <x-textarea v-model="form.career_goals" rows="4" required/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>How did you hear about us?</template>
                            <template #value>
                                <x-select v-model="form.hear_about_us">
                                    <option value="">--select--</option>
                                    <option value="search-engine">Search Engine</option>
                                    <option value="social-media">Social Media</option>
                                    <option value="friend-referral">Friend/Family Referral</option>
                                    <option value="advertisement">Advertisement</option>
                                    <option value="career-fair">Career Fair</option>
                                    <option value="other">Other</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Additional Information</template>
                            <template #value>
                                <x-textarea v-model="form.additional_info" rows="3"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <!-- Consent -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Terms Accepted</template>
                            <template #value>
                                <x-checkbox v-model="form.terms_accepted" required/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Marketing Consent</template>
                            <template #value>
                                <x-checkbox v-model="form.marketing_consent"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                    
                    <!-- Status -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status" required>
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
    phone: '',
    date_of_birth: '',
    program: '',
    start_date: '',
    study_format: '',
    career_goals: '',
    hear_about_us: '',
    additional_info: '',
    terms_accepted: false,
    marketing_consent: false,
    status: null
})


const show_image = ref('')
const show_image1 = ref('')

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.first_name = props.cardData.first_name;
        form.last_name = props.cardData.last_name;
        form.email = props.cardData.email;
        form.phone = props.cardData.phone;
        form.date_of_birth = props.cardData.date_of_birth;
        form.program = props.cardData.program;
        form.start_date = props.cardData.start_date;
        form.study_format = props.cardData.study_format;
        form.career_goals = props.cardData.career_goals;
        form.hear_about_us = props.cardData.hear_about_us;
        form.additional_info = props.cardData.additional_info;
        form.terms_accepted = props.cardData.terms_accepted;
        form.marketing_consent = props.cardData.marketing_consent;
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