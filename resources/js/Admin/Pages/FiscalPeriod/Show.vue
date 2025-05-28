<template>
    <div>
        <Head :title="cardData.period_name" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <!-- Fiscal Year -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Fiscal Year</template>
                        <template #value>{{ cardData.fiscal_year?.year }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Period Name -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Period Name</template>
                        <template #value>{{ cardData.period_name }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Start Date -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Start Date</template>
                        <template #value>{{ cardData.start_date }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- End Date -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>End Date</template>
                        <template #value>{{ cardData.end_date }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Date Created -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Date Created</template>
                        <template #value>{{ cardData.created_at }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Created By -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Created By</template>
                        <template #value>{{ cardData.created_by }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Last Updated -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Last Updated</template>
                        <template #value>{{ cardData.updated_at }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Last Updated By -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Last Updated By</template>
                        <template #value>{{ cardData.updated_by }}</template>
                    </x-show-group>
                </x-grid-col>
            </x-grid>
        </x-show-template>
    </div>
</template>

<script setup>
import { showProps, useShow } from '@/Composables/useShow.js';
import { Head } from '@inertiajs/vue3';

// Define props
const props = defineProps(showProps);

// Use the show composable
const {    
    xGrid,
    xGridCol,
    xLoading,
    xPanel,
    xShowGroup,
    xBadge,
    xShowTemplate
} = useShow(props);
</script>

<style scoped>
/* Add custom styles if needed */
</style>