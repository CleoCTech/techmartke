<template>
    <div>
        <Head title="Album Collections" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submitAlbumCollection" action="#" method="POST" enctype="multipart/form-data">
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
                                <img v-if="show_cover_image == '' && form.cover_image != null" :src="getImageUrl(form.cover_image)" class="h-20 w-20">
                                <x-input type="file" @input="handleCoverImageChange" ref="coverImageInput" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
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
                                    <option value="true">Published</option>
                                    <option value="false">Draft</option>
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
                                    <option value="false">Regular</option>
                                    <option value="true">Featured</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Images -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Album Images</template>
                            <template #value>
                                <input type="file" @change="handleImagesChange" multiple ref="imagesInput" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                <p class="text-sm text-gray-400 mt-1">Select multiple images to add to the album</p>
                                <button type="button" @click="testImagesFunction" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded text-sm">Test Images Function</button>
                                <button type="button" @click="testUpload" class="mt-2 ml-2 px-3 py-1 bg-green-500 text-white rounded text-sm">Test Upload</button>
                            </template>
                        </x-form-group>
                    </x-grid-col>
                </x-grid>

                <div class="flex justify-center py-3">
                    <x-button type="submit" :disabled="isLoading">
                        <span v-if="isLoading">Submitting...</span>
                        <span v-else>Submit</span>
                    </x-button>
                </div>
            </form>
        </x-create-edit-template>
    </div>
</template>

<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit'
import { ref, reactive, onMounted, getCurrentInstance } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { useNotify } from '@/Composables/useNotify'

const props = defineProps({
    ...createEditProps,
})

const { notification } = useNotify()

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

onMounted(() => {
    setData();
    console.log('=== COMPONENT MOUNTED ===');
    console.log('handleImagesChange function:', typeof handleImagesChange);
    console.log('imagesInput ref:', imagesInput.value);
    
    // Make submit function globally accessible for testing
    window.testSubmit = submitAlbumCollection;
    console.log('submitAlbumCollection function:', typeof submitAlbumCollection);
    
    // Test if the function is accessible
    console.log('Form submit handler:', document.querySelector('form')?.onsubmit);
})

const show_cover_image = ref('')
const imagePreviews = ref([])
const coverImageInput = ref(null)
const imagesInput = ref(null)
const isLoading = ref(false)

// Handle cover image change
const handleCoverImageChange = (event) => {
    if (event.target.files.length > 0) {
        form.cover_image = event.target.files[0];
        show_cover_image.value = URL.createObjectURL(event.target.files[0]);
    }
};

// Handle multiple images change  
const handleImagesChange = (event) => {
    console.log('=== IMAGES CHANGE EVENT TRIGGERED ===');
    console.log('Files selected:', event.target.files.length);
    console.log('Files:', event.target.files);
    console.log('Event target:', event.target);
    
    if (event.target.files.length > 0) {
        // Store files in a way that the standard submit function can handle
        // The standard function handles arrays by appending as key[index], but for files we need key[]
        // So we'll store them in a special property that we'll handle in the submit function
        form.images = Array.from(event.target.files);
        console.log('✅ Set form.images to:', form.images.length, 'files');
        console.log('File names:', form.images.map(f => f.name));
        console.log('Form.images after setting:', form.images);
    } else {
        form.images = [];
        console.log('❌ Cleared form.images');
    }
};

// Test function to check if images function works
const testImagesFunction = () => {
    console.log('=== TEST BUTTON CLICKED ===');
    console.log('imagesInput.value:', imagesInput.value);
    console.log('imagesInput.value.files:', imagesInput.value?.files);
    console.log('form.images:', form.images);
    
    if (imagesInput.value && imagesInput.value.files.length > 0) {
        console.log('Files found in input:', imagesInput.value.files.length);
        handleImagesChange({ target: imagesInput.value });
    } else {
        console.log('No files selected in input');
    }
    
    // Test FormData construction
    let testFormData = new FormData();
    testFormData.append('test', 'value');
    if (form.images && form.images.length > 0) {
        form.images.forEach((file, index) => {
            testFormData.append(`test_images[${index}]`, file);
        });
        console.log('Test FormData with images:', testFormData);
    }
};

// Test upload function
const testUpload = () => {
    console.log('=== TEST UPLOAD CLICKED ===');
    
    let testFormData = new FormData();
    testFormData.append('test', 'value');
    
    // Try to get files from input
    if (imagesInput.value && imagesInput.value.files && imagesInput.value.files.length > 0) {
        const files = Array.from(imagesInput.value.files);
        console.log('Files found for test upload:', files.length);
        files.forEach((file, index) => {
            testFormData.append('images[]', file);
            console.log(`Added file ${index}:`, file.name);
        });
    } else {
        console.log('No files found for test upload');
    }
    
    // Log FormData contents
    console.log('Test FormData contents:');
    for (let [key, value] of testFormData.entries()) {
        console.log(`${key}:`, value);
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    axios.post('/admin/album-collections/test-upload', testFormData, {
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    }).then(response => {
        console.log('Test upload response:', response.data);
        notification('Test upload successful!', 'success');
    }).catch(error => {
        console.error('Test upload error:', error);
        notification('Test upload failed!', 'error');
    });
};

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.description = props.cardData.description;
        form.category = props.cardData.category;
        form.cover_image = props.cardData.cover_image;
        // Convert boolean values to strings for the select components
        form.is_published = props.cardData.is_published ? 'true' : 'false';
        form.is_featured = props.cardData.is_featured ? 'true' : 'false';
        form.sort_order = props.cardData.sort_order;
        form.metadata = props.cardData.metadata;
    }
}

// Helper function to get correct image URL
function getImageUrl(imagePath) {
    if (!imagePath) return '';
    
    // If it's already a full URL, return as is
    if (imagePath.startsWith('http')) {
        return imagePath;
    }
    
    // If it's a relative path, construct the full URL
    if (imagePath.startsWith('album_covers/') || imagePath.startsWith('album_images/')) {
        return `/storage/${imagePath}`;
    }
    
    // Default fallback
    return `/storage/${imagePath}`;
}

const { onFileChange, xGrid,
        xFormGroup,
        xGridCol,
        xInput,
        xSelect,
        xTextarea,
        xButton,
        xCreateEditTemplate, 
        TextInput } = useCreateEdit(props, setData, form )

// Custom submit function for album collections
const submitAlbumCollection = function() {
    console.log('=== CUSTOM SUBMIT FUNCTION CALLED ===');
    alert('Custom submit function is working!');
    console.log('Form data:', form);
    console.log('Form images:', form.images);
    
    // Use the standard submit function but modify FormData for images
    isLoading.value = true;
    
    let formData = new FormData();
    
     // Handle all form fields except images
     for (let key in form) {
         if (key === 'images') {
             // Skip images for now, we'll handle them specially
             continue;
         }
         
         if (Array.isArray(form[key])) {
             if (form[key].length === 0) {
                 formData.append(`${key}[]`, '');
             } else {
                 form[key].forEach((item, index) => {
                     formData.append(`${key}[${index}]`, item);
                 });
             }
         } else {
             // Handle boolean fields specially
             if (key === 'is_published' || key === 'is_featured') {
                 formData.append(key, (form[key] === true || form[key] === 'true') ? '1' : '0');
             } else {
                 formData.append(key, form[key]);
             }
         }
     }
    
     // Handle images - use the same logic as Test Upload
     if (imagesInput.value && imagesInput.value.files && imagesInput.value.files.length > 0) {
         const files = Array.from(imagesInput.value.files);
         console.log('Adding images to FormData:', files.length, 'files');
         files.forEach((file, index) => {
             console.log(`Adding image ${index}:`, file.name, file.size, file.type);
             formData.append('images[]', file);
         });
     } else {
         console.log('No images found in input');
     }
    
    // Debug FormData contents
    console.log('FormData contents:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}:`, value);
    }
    
    // Determine endpoint and method
    const isEdit = form.uuid && form.uuid !== null && form.uuid !== '';
    const endpoint = isEdit 
        ? `${props.setup.settings.indexRoute}/${form.uuid}` 
        : `${props.setup.settings.indexRoute}/store`;
    const method = isEdit ? 'PUT' : 'POST';
    
    // Add _method for PUT requests
    if (isEdit) {
        formData.append('_method', 'PUT');
    }
    
    axios({
        method: method,
        url: endpoint,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    }).then(response => {
        console.log('✅ SUCCESS RESPONSE:', response);
        if (response.status === 200) {
            notification(response.data.message, response.data.type);
            if (!isEdit) {
                router.visit(props.setup.settings.indexRoute);
            }
        } else {
            notification(usePage().props.defaultErrors.default, 'error');
        }
        isLoading.value = false;
    }).catch((error) => {
        console.log('❌ ERROR RESPONSE:', error);
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            for (let field of Object.keys(errors)) {
                notification(errors[field][0], 'error');
            }
        } else {
            notification(usePage().props.defaultErrors.default, 'error');
        }
        isLoading.value = false;
    });
};
</script>
