<template>
    <div>
        <Head :title="cardData.name" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
               
                <x-grid-col>
                    <x-show-group>
                        <template #label>Name</template>
                        <template #value>{{cardData.name}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Title</template>
                        <template #value>{{cardData.title}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Email</template>
                        <template #value>{{cardData.email}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Phone No.</template>
                        <template #value>{{cardData.phone_no}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>X Link</template>
                        <template #value>{{cardData.x_link}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                    <template #label>Photo</template>
                    <template #value>
                        <img :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath + cardData.cover_image" class="h-28 w-28" />
                    </template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Status</template>
                        <template #value>
                            <x-badge :class="setup.statuses[setup.statuses.findIndex(x => x.id ===cardData.status)].color">{{setup.statuses[setup.statuses.findIndex(x => x.id ===cardData.status)].caption}}</x-badge>
                        </template>
                    </x-show-group>
                </x-grid-col>
               
                <x-grid-col v-if="cardData.status == 3">
                    <x-show-group>
                        <template #label>Publish Time</template>
                        <template #value>{{cardData.publish_time}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Date Created</template>
                        <template #value>{{cardData.created_at}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Created By</template>
                        <template #value>{{cardData.created_by}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Last Updated</template>
                        <template #value>{{cardData.updated_at}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Last Updated By</template>
                        <template #value>{{cardData.updated_by}}</template>
                    </x-show-group>
                </x-grid-col>
                <!-- <x-grid-col>
                    <x-show-group>
                        <template #label>Position</template>
                        <template #value>{{ cardData.position }}</template>
                    </x-show-group>
                </x-grid-col> -->
                <x-grid-col v-if="cardData.expertise && cardData.expertise.length">
                    <x-show-group>
                        <template #label>Expertise</template>
                        <template #value>
                            <div class="flex flex-wrap gap-2">
                                <span v-for="(expertise, index) in cardData.expertise" 
                                      :key="index"
                                      class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ expertise }}
                                </span>
                            </div>
                        </template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col v-if="cardData.experience">
                    <x-show-group>
                        <template #label>Experience</template>
                        <template #value>{{ cardData.experience }}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col v-if="cardData.education">
                    <x-show-group>
                        <template #label>Education</template>
                        <template #value>{{ cardData.education }}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col v-if="cardData.achievements">
                    <x-show-group>
                        <template #label>Achievements</template>
                        <template #value>{{ cardData.achievements }}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col v-if="cardData.quote">
                    <x-show-group>
                        <template #label>Quote</template>
                        <template #value>
                            <div class="italic text-gray-600">
                                "{{ cardData.quote }}"
                            </div>
                        </template>
                    </x-show-group>
                </x-grid-col>
            </x-grid>
        </x-show-template>
    </div>
</template>
<script setup>
    import { showProps, useShow } from '@/Composables/useShow.js'
    const props = defineProps(showProps)
    const {    
        xGrid,
        xGridCol,
        xLoading,
        xPanel,
        xShowGroup,
        xBadge,
        xShowTemplate
    } = useShow(props)
</script>