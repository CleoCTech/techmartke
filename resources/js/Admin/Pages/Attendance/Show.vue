<template>
    <div>
        <Head :title="cardData.title" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Cover Image</template>
                        <template #value>
                            <img :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+cardData.cover_image" class="h-20 w-20">
                        </template>
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
                        <template #label>Status</template>
                        <template #value>
                            <x-badge :class="setup.statuses[setup.statuses.findIndex(x => x.id ===cardData.status)].color">{{setup.statuses[setup.statuses.findIndex(x => x.id ===cardData.status)].caption}}</x-badge>
                        </template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col class="sm:col-span-3">
                    <x-show-group class="sm:grid-cols-1">
                        <template #label>Summary</template>
                        <template #value><span v-html="cardData.summary" class="break-all"></span></template>
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