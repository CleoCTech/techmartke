import { usePage } from '@inertiajs/vue3'
// import { router } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { onMounted, computed, defineEmits  } from 'vue';

//components
import xGrid from '@/Components/Grid.vue'
import xGridCol from '@/Components/GridCol.vue'
import xLoading from '@/Components/Loading.vue'
import xPanel from '@/Components/Panel.vue'
import xFormGroup from '@/Components/FormGroup.vue'
import xInput from '@/Components/TextInput.vue'
// import xInput from '@/Components/Input.vue'
import xSelect from '@/Components/Select.vue'
import xTextarea from '@/Components/Textarea.vue'
import xCheckbox from '@/Components/Checkbox.vue'
import xButton from '@/Components/Button.vue'
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import AppLayout from '@/System/Layouts/AppLayout.vue'
import xCreateEditTemplate from '@/System/Pages/Templates/CRUD/CreateEdit.vue'
import Table from '@/Components/Mosaic/Table/Table.vue'

import TextInput from '@/Components/TextInput.vue';

import { provide, getCurrentInstance } from 'vue'
import {useNotify} from "@/Composables/useNotify";

let {notification} = useNotify();
// just import your $notify
// sample
// import { $notify as $$notify } from '$notify.js'


const ckeditor = CKEditor.component;

export const createEditProps = {
    setup:Object,
    cardData:Object,
    relationships:[],
    selected:{default:[]},
    
}


export const useCreateEdit = (props, setData, form) => {
    onMounted(() => {
        setData();
        // console.log(props.relationships);
    })


    const context = getCurrentInstance()?.appContext.config.globalProperties;
    function submit(){
        // you shouldn't be mutating parent props
        // you can use props down, event up instead
        // ie. let's say you have an isLoading props, you can use
        // vue 3 v-model:isLoading="someBoolProp" in the parent
        // then emit on child

        //send page loader true; 
         // isLoading = true;
        context.$showLoading();
        let formData = new FormData();
        for ( var key in form ) {
            formData.append(key, form[key]);
        }
        axios.post(props.setup.settings.indexRoute+'/store',formData).then(response => {
            if(response.status == 200){
                notification(response.data.message, response.data.type);
                if(form.uuid == null || form.uuid == ''){
                    router.visit(props.setup.settings.indexRoute);
                }
            }
            else{
                notification(usePage().props.defaultErrors.default, 'error');
            }
            // isLoading = false;
            context.$hideLoading()
        }).catch((error) => {
            if(error.response.status == 422){
                var errors= [];
                errors = error.response.data.errors;
                for (let field of Object.keys(errors)) {
                    notification(errors[field][0], 'error');
                }
            }else{
                notification(usePage().props.defaultErrors.default, 'error');
            }            
            // isLoading = false;
            context.$hideLoading()
        })
    }

    function onFileChange(event,formVar,previewVar) {
        let files = event.target.files;
        if (files.length)
            // console.log(formVar);
            form[formVar] = files[0];
            //  console.log(form[formVar]);
            if(previewVar != ''){            
                // i don't know what this is need more context    
                // i assume you have set some data in consumer
                // you can try getCurrentInstance
                previewVar = URL.createObjectURL(files[0]);
            }
    }
    
    return {
        editor: ClassicEditor,
        editorConfig: {
            // The configuration of the editor.
            editorConfig: {
                stylesSet: [
                    { name: 'Dark Theme', type: 'widget', widget: 'customStyle' }
                ]
            }
        },
        submit,
        onFileChange,
        ckeditor,
        xGrid,
        xFormGroup,
        xGridCol,
        xLoading,
        xPanel,
        xInput,
        xSelect,
        xTextarea,
        xCheckbox,
        xButton,
        AppLayout,
        xCreateEditTemplate,
        TextInput,
        Table
        // ListPart
    }
}
