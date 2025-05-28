<template>
    <div>
        <Head :title="cardData.name" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <!-- Name -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Name</template>
                        <template #value>{{ cardData.name }}</template>
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

                <!-- Created By -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Created By</template>
                        <template #value>{{ cardData.created_by }}</template>
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