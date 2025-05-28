<template>
    <div>
        <Head title="Branch Leadership" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <!-- Branch -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Branch</template>
                        <template #value>{{ cardData.branch?.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Leader -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Leader</template>
                        <template #value>{{ cardData.user?.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Designation -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Designation</template>
                        <template #value>{{ cardData.designation?.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Fiscal Period -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Fiscal Period</template>
                        <template #value>{{ cardData.fiscal_period?.name }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Status -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Status</template>
                        <template #value>
                            <Badge :class="getStatusClass(cardData.status)">
                                {{ getStatusCaption(cardData.status) }}
                            </Badge>
                        </template>
                    </x-show-group>
                </x-grid-col>

                <!-- Created At -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Created At</template>
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
import { showProps } from '@/Composables/useShow'
import Badge from '@/Components/Mosaic/Badge.vue'
import { ref } from 'vue'

const props = defineProps({
    ...showProps
})

// Status mapping
const statuses = ref([
    { id: 'active', color: 'bg-green-500 text-white', caption: 'Active' },
    { id: 'inactive', color: 'bg-red-500 text-white', caption: 'Inactive' }
]);

function getStatusClass(status) {
    const statusObj = statuses.value.find((s) => s.id === status);
    return statusObj ? statusObj.color : 'bg-gray-300 text-black';
}

function getStatusCaption(status) {
    const statusObj = statuses.value.find((s) => s.id === status);
    return statusObj ? statusObj.caption : 'Unknown';
}

const { cardData } = props
</script>

