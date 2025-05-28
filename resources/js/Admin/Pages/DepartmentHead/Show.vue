<template>
    <div>
        <Head :title="cardData.user.name" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Name</template>
                        <template #value>{{ cardData.user.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Department</template>
                        <template #value>{{ cardData.department.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Designation</template>
                        <template #value>{{ cardData.designation.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Fiscal Period</template>
                        <template #value>{{ cardData.fiscal_period.year }}</template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Date Created</template>
                        <template #value>{{ cardData.created_at }}</template>
                    </x-show-group>
                </x-grid-col>
            </x-grid>
        </x-show-template>
    </div>
</template>

<script setup>
import { showProps, useShow } from '@/Composables/useShow';
import { Head } from '@inertiajs/vue3';

const props = defineProps(showProps);
const {    
    xGrid,
    xGridCol,
    xShowGroup,
    xShowTemplate
} = useShow(props);
</script>