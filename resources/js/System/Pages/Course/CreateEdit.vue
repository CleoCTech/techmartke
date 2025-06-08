<template>
    <div>
        <Head title="Create/Edit Course" />

        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>

                    <x-grid-col>
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value><x-input type="text" v-model="form.title" /></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col>
                        <x-form-group>
                            <template #label>Duration</template>
                            <template #value><x-input type="text" v-model="form.duration" /></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col>
                        <x-form-group>
                            <template #label>Level</template>
                            <template #value><x-input type="text" v-model="form.level" /></template>
                        </x-form-group>
                    </x-grid-col>

                     <x-grid-col>
                        <x-form-group>
                            <template #label>Projects</template>
                            <template #value><x-input type="number" v-model="form.projects" /></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col>
                        <x-form-group>
                            <template #label>Students</template>
                            <template #value><x-input type="number" v-model="form.students" /></template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col>
                         <x-form-group>
                             <template #label>Status</template>
                             <template #value>
                                  <x-select v-model="form.status">
                                     <option v-for="status in setup.statuses" :key="status.id" :value="status.id">{{ status.caption }}</option>
                                  </x-select>
                             </template>
                         </x-form-group>
                     </x-grid-col>

                     <x-grid-col class="sm:col-span-6">
                        <x-form-group class="sm:grid-cols-1">
                            <template #label>Description</template>
                            <template #value>
                                <ckeditor v-cloak :editor="editor" v-model="form.description" @ready="cardData != null? form.description = cardData.description :''"></ckeditor>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-6">
                         <x-form-group class="sm:grid-cols-1">
                            <template #label>Technologies (comma-separated)</template>
                            <template #value>
                                <x-input type="text" v-model="form.technologies"/>
                            </template>
                         </x-form-group>
                    </x-grid-col>

                     <x-grid-col class="sm:col-span-2">
                        <x-form-group>
                            <template #label>Cover Image</template>
                            <template #value>
                                <div class="flex flex-col space-y-2">
                                    <img v-if="show_image == '' && form.image != null" 
                                         :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" 
                                         class="h-20 w-20 object-cover rounded">
                                   
                                    <TextInput type="file" @input="onFileChange($event,'image','show_image')" 
                                              ref="input" 
                                              class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                    
                                    <img v-if="show_image != ''" 
                                         :src="show_image" 
                                         class="h-20 w-20 object-cover rounded">
                                </div>
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
    import { ref, reactive, computed, onMounted  } from 'vue'

    const props = defineProps({
        ...createEditProps,
    })

    onMounted(() => {
        // Any on mount logic specific to Course
    })

    const form = reactive({
        uuid: null,
        title: null,
        image: null,
        duration: null,
        level: null,
        description: null,
        projects: null,
        students: null,
        technologies: null, // Will be handled as comma-separated string initially
        status: null,
    })

    const show_image = ref('')

    function setData() {
        if (props.cardData != null && props.cardData.uuid != null) {
            form.uuid = props.cardData.uuid
            form.title = props.cardData.title
            form.image = props.cardData.image
            form.duration = props.cardData.duration
            form.level = props.cardData.level
            form.description = props.cardData.description
            form.projects = props.cardData.projects
            form.students = props.cardData.students
            // Convert array of technologies to comma-separated string for the input field
            form.technologies = Array.isArray(props.cardData.technologies) ? props.cardData.technologies.join(', ') : '';
            form.status = props.cardData.status
        }
    }

    const result = useCreateEdit(props, setData, form ); // Get the result object

    const { editor,editorConfig, onFileChange, ckeditor, xGrid,
            xFormGroup,
            xGridCol,
            xLoading,
            xPanel,
            xInput,
            xSelect,
            xTextarea,
            xCheckbox,
            Checkbox,
            xButton,
            AppLayout,
            xCreateEditTemplate, TextInput
            } = result; // Destructure constants from result

    let submit = result.submit; // Declare submit with let and assign from result

    // Override the submit function to handle technologies conversion
    const originalSubmit = submit;
    submit = () => {
        // Convert comma-separated technologies string back to array before submitting
        const formData = { ...form };
        if (formData.technologies) {
            // Ensure technologies is treated as a string before splitting
            formData.technologies = String(formData.technologies).split(',').map(tech => tech.trim());
        } else {
            formData.technologies = [];
        }
         // Pass the modified formData to the original submit function
        originalSubmit({...formData, technologies: JSON.stringify(formData.technologies)}); // Stringify technologies
    };

</script>

<style scoped>
/* Your styles here */
</style> 