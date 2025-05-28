<template>
    <div>
        <Head :title="cardData.title" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Title</template>
                        <template #value>{{ cardData.title }}</template>
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
                        <template #label>Fiscal Period</template>
                        <template #value>{{ cardData.fiscal_period.year }}</template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Description</template>
                        <template #value v-html="cardData.description"></template>
                    </x-show-group>
                </x-grid-col>

                <x-grid-col>
                    <x-show-group>
                        <template #label>Target Date</template>
                        <template #value>{{ cardData.target_date }}</template>
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