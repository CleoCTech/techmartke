<template>
    <div>

        <Head title="Fee Structure" />
        <!-- <x-loading :show="loading" /> -->
        <div v-if="!loading">

        
        <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto flex">
            <div class="flex-grow">
                <div class="mb-8">
                    <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold">Fee Structure</h1>
                </div>

                <x-panel>
                    <template #title>Add New Record</template>
                    <template #body>
                        <form @submit.prevent="submitNewFee">
                            <x-grid>
                                <x-grid-col class="">
                                    <x-form-group>
                                        <template #label>Grade<span class="text-rose-500">*</span></template>
                                        <template #value>
                                            <x-input type="text" v-model="newFee.grade"  @input="newFee.grade = newFee.grade.toUpperCase()" />
                                        </template>
                                    </x-form-group>
                                </x-grid-col>
                                <x-grid-col class="">
                                    <x-form-group>
                                        <template #label>Term 1<span class="text-rose-500">*</span></template>
                                        <template #value>
                                            <x-input type="number" v-model="newFee.term1" @input="updateNewFeeTotal" required />
                                        </template>
                                    </x-form-group>
                                </x-grid-col>

                                <x-grid-col class="">
                                    <x-form-group>
                                        <template #label>Term 2<span class="text-rose-500">*</span></template>
                                        <template #value>
                                            <x-input type="number" v-model="newFee.term2" @input="updateNewFeeTotal" required />
                                        </template>
                                    </x-form-group>
                                </x-grid-col>
                                <x-grid-col class="">
                                    <x-form-group>
                                        <template #label>Term 3<span class="text-rose-500">*</span></template>
                                        <template #value>
                                            <x-input type="number" v-model="newFee.term3" @input="updateNewFeeTotal" required/>
                                        </template>
                                    </x-form-group>
                                </x-grid-col>
                                <x-grid-col class="">
                                    <x-form-group>
                                        <template #label>Category <span class="text-rose-500">*</span></template>
                                        <template #value>
                                            <x-select v-model="newFee.category" required>
                                                <option value="" select>--select--</option>
                                                <option value="full_boarding">Full Boarding</option>
                                                <option value="flex_boarding">Flex Boarding</option>
                                                <option value="day_school">Day School</option>
                                            </x-select>
                                        </template>
                                    </x-form-group>
                                </x-grid-col>
                                <x-grid-col class="">
                                    <x-form-group>
                                        <template #label>Total</template>
                                        <template #value>
                                            <x-input type="number" :value="newFee.total" disabled />
                                        </template>
                                    </x-form-group>
                                </x-grid-col>

                            </x-grid>
                            <div class="flex justify-center py-3">
                                <x-button type="submit">Add Fee</x-button>
                            </div>
                        </form>
                        <!-- <x-attachment v-if="isAttachments && setup.pageType == 'edit'" class="flex-none pr-2 md:max-w-xs" :setup="setup" :selected="selected"/> -->
                    </template>
                    <!-- <template #footer>Add New Record</template> -->
                </x-panel>
            </div>
            <!-- Right Column (x-attachment) -->
            <div class="flex-none">
                <!-- <x-attachment v-if="isAttachments && setup.pageType == 'edit'" class="pr-2 md:max-w-xs" :setup="setup"
                    :selected="selected" /> -->
            </div>

        </div>

        <x-panel>
            <template #body>
                
            
            <h3 class="text-white">Full Boarding</h3>
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Term 1</th>
                        <th>Term 2</th>
                        <th>Term 3</th>
                        <th>Totals</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="fee in fullBoarding" :key="fee.id">
                        <td>{{ fee.grade }}</td>
                        <td><input type="number" v-model="fee.term1" @input="updateTotal(fee)" /></td>
                        <td><input type="number" v-model="fee.term2" @input="updateTotal(fee)" /></td>
                        <td><input type="number" v-model="fee.term3" @input="updateTotal(fee)" /></td>
                        <td>{{ formatCurrency(fee.total) }}</td>
                        <!-- <td>
                            <x-button  @click="updateFee(fee)">Save</x-button>
                            
                        </td> -->
                        <td><button @click="updateFee(fee)" class="btn dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-emerald-500">Save</button></td>
                    </tr>
                </tbody>
            </table>

            <!-- Flex Boarding Section -->
            <h3 class="text-white">Flex Boarding</h3>
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Term 1</th>
                        <th>Term 2</th>
                        <th>Term 3</th>
                        <th>Totals</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="fee in flexBoarding" :key="fee.id">
                        <td>{{ fee.grade }}</td>
                        <td><input type="number" v-model="fee.term1" @input="updateTotal(fee)" /></td>
                        <td><input type="number" v-model="fee.term2" @input="updateTotal(fee)" /></td>
                        <td><input type="number" v-model="fee.term3" @input="updateTotal(fee)" /></td>
                        <td>{{ formatCurrency(fee.total) }}</td>
                        <td> <button @click="updateFee(fee)" class="btn dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-emerald-500">Save</button></td>
                    </tr>
                </tbody>
            </table>

            <!-- Day School Section -->
            <h3 class="text-white">Day School</h3>
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Term 1</th>
                        <th>Term 2</th>
                        <th>Term 3</th>
                        <th>Totals</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="fee in daySchool" :key="fee.id">
                        <td>{{ fee.grade }}</td>
                        <td><input type="number" v-model="fee.term1" @input="updateTotal(fee)" /></td>
                        <td><input type="number" v-model="fee.term2" @input="updateTotal(fee)" /></td>
                        <td><input type="number" v-model="fee.term3" @input="updateTotal(fee)" /></td>
                        <td>{{ formatCurrency(fee.total) }}</td>
                        <td><button @click="updateFee(fee)" class="btn dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-emerald-500">Save</button></td>
                    </tr>
                </tbody>
            </table>
        </template>
        </x-panel>
    </div>
    </div>
</template>

<script setup>
import { ref,onMounted,getCurrentInstance  } from 'vue';
import { useForm, router } from '@inertiajs/vue3'; // Inertia's Vue 3 form handling
import xPanel from '@/Components/Panel.vue'
import xAttachment from '@/Components/Attachment.vue'
import xGrid from '@/Components/Grid.vue'
import xGridCol from '@/Components/GridCol.vue'
import xLoading from '@/Components/Loading.vue'
import xFormGroup from '@/Components/FormGroup.vue'
import xInput from '@/Components/TextInput.vue'
import xSelect from '@/Components/Select.vue'
import xButton from '@/Components/Button.vue'
import {useNotify} from "@/Composables/useNotify";


// Props passed from the controller
const props = defineProps(['fees']);
let {notification} = useNotify();

const loading = ref(true);
const context = getCurrentInstance()?.appContext.config.globalProperties;
// Simulate the loading process (for example, during the fetching of data)
onMounted( async () => {
    context.$showLoading()
  // Simulate a delay (such as fetching data from the server)
  setTimeout(() => {
    context.$hideLoading()
    loading.value = false; // Hide the loader after the page content has loaded
  }, 1000); // Replace with actual data-fetching logic
});

// Refactor fees based on categories
const fullBoarding = ref(props.fees.filter(fee => fee.category === 'full_boarding'));
const flexBoarding = ref(props.fees.filter(fee => fee.category === 'flex_boarding'));
const daySchool = ref(props.fees.filter(fee => fee.category === 'day_school'));

// New fee data (form for adding a new record)
const newFee = ref({
    grade: '',
    term1: 0,
    term2: 0,
    term3: 0,
    total: 0,
    category: '',
});

// Function to calculate total for the new fee record
const updateNewFeeTotal = () => {
    // newFee.value.total = newFee.value.tuition + (newFee.value.boarding || 0);
    newFee.value.total = parseFloat(newFee.value.term1) + (parseFloat(newFee.value.term2) || 0) +  parseFloat(newFee.value.term3);
};

// Function to add new fee via Inertia post request
const submitNewFee = () => {
    context.$showLoading() // Show loader when the form is submitted
    if (newFee.value.category == '') {
        context.$hideLoading()
        notification('Category cannot be empty!', 'error');
        return;
    }
    const form = useForm({
        grade: newFee.value.grade,
        term1: newFee.value.term1,
        term2: newFee.value.term2,
        term3: newFee.value.term3,
        total: newFee.value.total,
        category: newFee.value.category,
    });

    form.post('/admin/fee-structure', {
        onSuccess: () => {
            //alert('New fee record added successfully!');
            notification('New fee record added successfully!', 'success');
            form.reset(); // Reset the form after successful submission
            // loading.value = false; // Hide loader on success
            context.$hideLoading()
            router.visit('/admin/fee-structure');
        },
        onError: () => {
            // alert('An error occurred while adding the fee.');
            notification('An error occurred while adding the fee.', 'error');
            // loading.value = false; // Hide loader on error
            context.$hideLoading()
        }
    });
};

// Update total dynamically
const updateTotal = (fee) => {
    fee.total = parseFloat(fee.term1) + parseFloat((fee.term2 || 0)) +  parseFloat((fee.term3 || 0));

};

// Format currency
const formatCurrency = (amount) => {
    return `KSH.${amount.toLocaleString()}`;
};

// Function to update fee via Inertia
const updateFee = (fee) => {
    // loading.value = true; // Show loader when updating
    context.$showLoading()
    const form = useForm({
        term1: fee.term1,
        term2: fee.term2,
        term3: fee.term3,
        total: fee.total
    });

    form.put(`/admin/fee-structure/${fee.id}`, {
        onSuccess: () => {
            // alert('Fee updated successfully!');
            notification('Fee updated successfully!', 'success');
            // loading.value = false; // Hide loader on success
            context.$hideLoading()
        },
        onError: () => {
            // alert('An error occurred while updating the fee.');
            notification('An error occurred while updating the fee!', 'error');
            // loading.value = false; // Hide loader on error
            context.$hideLoading()
        }
    });
};
</script>

<style scoped>
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }

    table th,
    table td {
        text-align: center;
        padding: 10px;
    }

    input[type="number"],
    input[type="text"],
    select {
        width: 150px;
        text-align: right;
    }

    .add-fee-form {
        margin-bottom: 20px;
    }

    .add-fee-form div {
        margin-bottom: 10px;
    }
</style>