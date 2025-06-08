<template>
    <div>
        <Head title="View Course" />

         <x-create-edit-template :setup="setup" :selected="selected">
             <template #content>
                 <x-panel>
                    <h2>Course Details</h2>
                    <div><strong>Title:</strong> {{ cardData.title }}</div>
                    <div><strong>Duration:</strong> {{ cardData.duration }}</div>
                    <div><strong>Level:</strong> {{ cardData.level }}</div>
                    <div><strong>Status:</strong> {{ cardData.status }}</div>
                     <div><strong>Projects:</strong> {{ cardData.projects }}</div>
                    <div><strong>Students:</strong> {{ cardData.students }}</div>
                     <div><strong>Technologies:</strong> {{ Array.isArray(cardData.technologies) ? cardData.technologies.join(', ') : 'N/A' }}</div>
                    <div><strong>Description:</strong></div>
                    <div v-html="cardData.description"></div>

                    <div v-if="cardData.image">
                        <strong>Image:</strong><br>
                         <img :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+cardData.image" alt="Course Image" class="h-40 w-auto">
                    </div>

                     <!-- Add more fields as needed -->

                 </x-panel>
             </template>
         </x-create-edit-template>
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