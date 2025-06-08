<template>
    <div>
        <Head :title="cardData.title" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <!-- Title -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Title</template>
                        <template #value>{{ cardData.title }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Student Name -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Student Name</template>
                        <template #value>{{ cardData.student_name }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Course -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Course</template>
                        <template #value>{{ cardData.course }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Graduation Year -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Graduation Year</template>
                        <template #value>{{ cardData.graduation_year }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Description -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Description</template>
                        <template #value>{{ cardData.description }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Achievement -->
                <x-grid-col v-if="cardData.achievement">
                    <x-show-group>
                        <template #label>Achievement</template>
                        <template #value>{{ cardData.achievement }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Cover Image -->
                <x-grid-col v-if="cardData.cover_image">
                    <x-show-group>
                        <template #label>Cover Image</template>
                        <template #value>
                            <img :src="cardData.cover_image" class="h-48 w-48 object-cover rounded-lg" />
                        </template>
                    </x-show-group>
                </x-grid-col>

                <!-- Status -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Status</template>
                        <template #value>
                            <x-badge :status="cardData.status" :statuses="setup.statuses" />
                        </template>
                    </x-show-group>
                </x-grid-col>

                <!-- Sequence -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Sequence</template>
                        <template #value>{{ cardData.sequence }}</template>
                    </x-show-group>
                </x-grid-col>

                <!-- Publish Time -->
                <x-grid-col v-if="cardData.publish_time">
                    <x-show-group>
                        <template #label>Publish Time</template>
                        <template #value>{{ cardData.publish_time }}</template>
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

                <!-- Updated By -->
                <x-grid-col v-if="cardData.updated_by">
                    <x-show-group>
                        <template #label>Updated By</template>
                        <template #value>{{ cardData.updated_by }}</template>
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
    xShowTemplate,
    xBadge
} = useShow(props);
</script> 