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

                <!-- Slug -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Slug</template>
                        <template #value>{{ cardData.slug }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Color Class -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Color Class</template>
                        <template #value>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 rounded-full" :class="cardData.color_class"></div>
                                <span>{{ cardData.color_class }}</span>
                            </div>
                        </template>
                    </x-show-group>
                </x-grid-col>

                <!-- Description -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Description</template>
                        <template #value>{{ cardData.description || 'No description provided' }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Sort Order -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Sort Order</template>
                        <template #value>{{ cardData.sort_order }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Status -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Status</template>
                        <template #value>
                            <x-badge :status="cardData.is_active" :statuses="setup.statuses" />
                        </template>
                    </x-show-group>
                </x-grid-col>

                <!-- Date Created -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Date Created</template>
                        <template #value>{{ cardData.created_at }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Date Updated -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Date Updated</template>
                        <template #value>{{ cardData.updated_at }}</template>
                    </x-show-group>
                </x-grid-col>
            </x-grid>
        </x-show-template>
    </div>
</template>

<script setup>
import { showProps, useShow } from '@/Composables/useShow';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    ...showProps
});

const { xShowTemplate, xGrid, xGridCol, xShowGroup, xBadge } = useShow(props);
</script>
