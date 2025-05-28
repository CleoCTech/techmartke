<template>
    <div>
        <Head :title="cardData.name" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <!-- Role Name -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Name</template>
                        <template #value>{{ cardData.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Display Name -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Display Name</template>
                        <template #value>{{ cardData.display_name }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Description -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Description</template>
                        <template #value>{{ cardData.description }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Date Created -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Date Created</template>
                        <template #value>{{ cardData.created_at }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Last Updated -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Last Updated</template>
                        <template #value>{{ cardData.updated_at }}</template>
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
    xShowGroup,
    xShowTemplate
} = useShow(props);
</script>

<style scoped>
/* Add custom styles if needed */
</style>