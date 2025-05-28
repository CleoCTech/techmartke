<template>
    <div>
        <Head title="Branch Leadership" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Branch -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Branch</template>
                            <template #value>
                                <x-select v-model="form.branch_id">
                                    <option value="">--select--</option>
                                    <option v-for="(branch, index) in setup.branches" 
                                            :key="index" 
                                            :value="branch.id">
                                        {{branch.name}}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Leader -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Leader</template>
                            <template #value>
                                <x-select v-model="form.user_id">
                                    <option value="">--select--</option>
                                    <option v-for="(user, index) in setup.users" 
                                            :key="index" 
                                            :value="user.id">
                                        {{user.name}}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Designation -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Designation</template>
                            <template #value>
                                <x-select v-model="form.designation_id">
                                    <option value="">--select--</option>
                                    <option v-for="(designation, index) in setup.designations" 
                                            :key="index" 
                                            :value="designation.id">
                                        {{designation.name}}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Fiscal Period -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Fiscal Period</template>
                            <template #value>
                                <x-select v-model="form.fiscal_period_id">
                                    <option value="">--select--</option>
                                    <option v-for="(period, index) in setup.fiscal_periods" 
                                            :key="index" 
                                            :value="period.id">
                                        {{period.period_name}}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Status -->
                    <x-grid-col>
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status">
                                    <option value="">--select--</option>
                                    <option v-for="(status,index) in setup.statuses" 
                                            :key="index" 
                                            :value="status.id">
                                        {{status.caption}}
                                    </option>
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
import { ref, reactive, computed, onMounted } from 'vue'

const props = defineProps({
    ...createEditProps,
})

const form = reactive({
    uuid: null,
    branch_id: null,
    user_id: null,
    designation_id: null,
    fiscal_period_id: null,
    status: null
})

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.branch_id = props.cardData.branch_id;
        form.user_id = props.cardData.user_id;
        form.designation_id = props.cardData.designation_id;
        form.fiscal_period_id = props.cardData.fiscal_period_id;
        form.status = props.cardData.status;
    }
}

const { submit, xGrid, xFormGroup, xGridCol, xSelect, xButton, xCreateEditTemplate } = useCreateEdit(props, setData, form)
</script>

