<template>
    <div>
        <Head title="Album Collections" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Title -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Album Title</template>
                            <template #value>
                                <TextInput type="text" v-model="form.title" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Category -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Category</template>
                            <template #value>
                                <x-select v-model="form.category" required>
                                    <option value="">--select--</option>
                                    <option v-for="category in setup.categories" :key="category.id" :value="category.id">
                                        {{ category.caption }}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Description -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Description</template>
                            <template #value>
                                <textarea v-model="form.description" rows="4" placeholder="Enter album description..." class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"></textarea>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Cover Image -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Cover Image</template>
                            <template #value>
                                <img v-if="show_cover_image == '' && form.cover_image != null" :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" class="h-20 w-20">
                                <x-input type="file" @input="onFileChange($event, 'cover_image', show_cover_image)" ref="input" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                <img v-if="show_cover_image != ''" :src="show_cover_image" class="h-20 w-20">
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Sort Order -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Sort Order</template>
                            <template #value>
                                <TextInput type="number" v-model="form.sort_order" min="0" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Status -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.is_published">
                                    <option :value="true">Published</option>
                                    <option :value="false">Draft</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Featured -->
                    <x-grid-col class="sm:col-span-6">
                        <x-form-group>
                            <template #label>Featured</template>
                            <template #value>
                                <x-select v-model="form.is_featured">
                                    <option :value="false">Regular</option>
                                    <option :value="true">Featured</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Images -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Album Images</template>
                            <template #value>
                                <x-input type="file" @input="handleImagesChange" multiple class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                </x-grid>

                <div class="flex justify-center py-3">
                    <x-button type="submit">Submit</x-button>
                </div>
            </form>
        </x-create-edit-template>
    </div>
</template>

<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit'
import { ref, reactive, onMounted } from 'vue'

const props = defineProps({
    ...createEditProps,
})

onMounted(() => {
    // Initialize component
})

const form = reactive({
    uuid: null,
    title: null,
    description: null,
    category: null,
    cover_image: null,
    images: [],
    is_published: true,
    is_featured: false,
    sort_order: 0,
    metadata: []
})

const show_cover_image = ref('')
const imagePreviews = ref([])

// Handle cover image change
const handleCoverImageChange = (event) => {
    form.cover_image = Array.from(event.target.files);
};

// Handle multiple images change  
const handleImagesChange = (event) => {
    form.images = Array.from(event.target.files);
};

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.description = props.cardData.description;
        form.category = props.cardData.category;
        form.cover_image = props.cardData.cover_image;
        form.is_published = props.cardData.is_published;
        form.is_featured = props.cardData.is_featured;
        form.sort_order = props.cardData.sort_order;
        form.metadata = props.cardData.metadata;
    }
}

const { submit, onFileChange, xGrid,
        xFormGroup,
        xGridCol,
        xInput,
        xSelect,
        xTextarea,
        xButton,
        xCreateEditTemplate, 
        TextInput } = useCreateEdit(props, setData, form )
</script>
