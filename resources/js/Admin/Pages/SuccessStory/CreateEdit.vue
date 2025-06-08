<template>
    <div>
        <Head :title="setup.editMode ? 'Edit Success Story' : 'Create Success Story'" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                    <!-- Title -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <TextInput type="text" v-model="form.title" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Student Name -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Student Name</template>
                            <template #value>
                                <TextInput type="text" v-model="form.student_name" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Course -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Course</template>
                            <template #value>
                                <TextInput type="text" v-model="form.course" required />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Graduation Year -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Graduation Year</template>
                            <template #value>
                                <TextInput type="number" v-model="form.graduation_year" required 
                                    :min="1900" :max="new Date().getFullYear() + 1" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Description -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Description</template>
                            <template #value>
                                <x-textarea v-model="form.description" rows="4" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Achievement -->
                    <x-grid-col class="sm:col-span-12">
                        <x-form-group>
                            <template #label>Achievement</template>
                            <template #value>
                                <x-textarea v-model="form.achievement" rows="4" placeholder="Enter student's achievements, awards, or notable accomplishments" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Cover Image Senior Software Engineer | MS 365 Consultant -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Cover Image</template>
                            <template #value>
                                <img v-if="show_image == '' && form.cover_image != null" :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" class="h-20 w-20">
                                <x-input type="file" @input="onFileChange($event, 'cover_image', show_image)" ref="input" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                <img v-if="show_image != ''" :src="show_image" class="h-20 w-20">
                            </template>                        
                        </x-form-group>
                    </x-grid-col>

                    <!-- Sequence -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Sequence</template>
                            <template #value>
                                <TextInput type="number" v-model="form.sequence" />
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Status -->
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status" required>
                                    <option value="">--select--</option>
                                    <option v-for="(status,index) in setup.statuses" :key="index" :value="status.id">
                                        {{status.caption}}
                                    </option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- Publish Time (only shown when status is published) -->
                    <x-grid-col v-if="form.status == 3" class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Publish Time</template>
                            <template #value>
                                <TextInput type="datetime-local" v-model="form.publish_time" required />
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
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit';
import { ref, reactive, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    ...createEditProps,
    maxSequence: {
        type: Number,
        default: 0
    }
});

onMounted(() => {
    // Set default sequence to next available number for new records
    if (!props.cardData?.uuid && !form.sequence) {
        form.sequence = props.maxSequence + 1;
    }
});

const show_image = ref('');

const form = reactive({
    uuid: null,
    title: '',
    student_name: '',
    course: '',
    graduation_year: null,
    description: '',
    achievement: '',
    cover_image: null,
    sequence: null,
    status: null,
    publish_time: null
});

const setData = () => {
    if (props.cardData?.uuid) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.student_name = props.cardData.student_name;
        form.course = props.cardData.course;
        form.graduation_year = props.cardData.graduation_year;
        form.description = props.cardData.description;
        form.achievement = props.cardData.achievement;
        form.sequence = props.cardData.sequence;
        form.status = props.cardData.status;
        form.publish_time = props.cardData.publish_time2;
        
        if (props.cardData.cover_image) {
            show_image.value = props.cardData.cover_image;
        }
    }
};

const { editor,editorConfig, submit, onFileChange, ckeditor, xGrid,
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
        xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form )

</script> 

<style scoped>



</style>