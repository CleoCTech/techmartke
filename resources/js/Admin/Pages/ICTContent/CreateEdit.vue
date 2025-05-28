<template>
    <div>
        <Head title="Gallery" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data">
                <x-grid>
                   
                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Title</template>
                            <template #value>
                                <x-input type="text" v-model="form.title"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Description</template>
                            <template #value>
                                <x-input type="text" v-model="form.description"/>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <!-- <x-grid-col class="sm:col-span-4">
                        <x-form-group>
                            <template #label>Sequence</template>
                            <template #value>
                                <x-input type="number" v-model="form.sequence"/>
                            </template>
                        </x-form-group>
                    </x-grid-col> -->

                    <x-grid-col class="col-span-3">
                        <x-form-group>
                            <template #label>Cover Image</template>
                            <template #value>
                                <img v-if="show_image == '' && form.cover_image != null" :src="$page.props.storagePaths[setup.settings.storageName].cover_images.readPath+form.cover_image" class="h-20 w-20">
                                <x-input type="file" @input="onFileChange($event, 'cover_image', show_image)" ref="input" class="shadow appearance-none border w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline disabled:opacity-40"/>
                                <img v-if="show_image != ''" :src="show_image" class="h-20 w-20">
                            </template>                        
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col class="col-span-3">
                        <x-form-group>
                            <template #label>Status</template>
                            <template #value>
                                <x-select v-model="form.status">
                                    <option value="">--select--</option>
                                    <option v-for="(status,index) in setup.statuses" :key="index" :value="status.id">{{status.caption}}</option>
                                </x-select>
                            </template>
                        </x-form-group>
                    </x-grid-col>

                    <x-grid-col v-if="form.status == 3">
                        <x-form-group>
                            <template #label>Publish Time</template>
                            <template #value><x-input type="datetime-local" v-model="form.publish_time"/></template>
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

    // console.log(props.setup.bindedPriceIds);
    // chartData.value = setChartData();
    // chartOptions.value = setChartOptions();
})


const form = reactive({
    uuid: null,
    title: null,
    description: null,
    cover_image: null,
    status: null,
    publish_time: null,
})


function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid;
        form.title = props.cardData.title;
        form.description = props.cardData.description;
        form.cover_image = props.cardData.cover_image;
        form.status = props.cardData.status;
        form.publish_time = props.cardData.publish_time2;
    }
    
}

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
input[type="file"] {
  /* display: none; */
}

.custom-file-input {
  background-color: blue;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}
/* 
label {
  background-color: blue;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

label::before {
  content: "Choose file";
} */

/* label::after {
  content: "📷";
} */

</style>