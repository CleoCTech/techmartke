import { usePage } from '@inertiajs/vue3'
//import { router } from '@inertiajs/vue3'
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
import TextInput from '@/Components/TextInput.vue';
import FileInput from '@/Components/FileInput.vue';
import TagInput from '@/Components/TagInput.vue'

import { provide, getCurrentInstance } from 'vue'
import {useNotify} from "@/Composables/useNotify";

let {notification} = useNotify();
const ckeditor = CKEditor.component;

export const createEditProps = {
    setup:Object,
    cardData:Object,
    selected:{default:[]},
}
export const useCreateEdit = (props, setData, form) => {
    onMounted(() => {
        setData();
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
        // for ( var key in form ) {
        //     // console.log('appending ' + key + ':' +form[key]);
            
        //     formData.append(key, form[key]);
        // }
        // Iterate over the form object
        for (let key in form) {
            if (Array.isArray(form[key])) {
                // If the value is an array, append each item individually
                if (form[key].length === 0) {
                    // Send an empty array if no items are selected
                    formData.append(`${key}[]`, ''); // Append an empty value to indicate an empty array
                } else {
                    form[key].forEach((item, index) => {
                        formData.append(`${key}[${index}]`, item);
                    });
                }
            } else {
                // Otherwise, append the value as usual
                formData.append(key, form[key]);
            }
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
            console.log(error);
            
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
        // Define allowed MIME types for various file categories
        const allowedTypes = [
            // Image types
            'image/jpeg', 'image/png', 'image/gif',
            // Video types
            'video/mp4', 'video/webm', 'video/ogg',
            // Document types
            'application/pdf', // PDF
            'application/msword', // Word 97-2003
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // Word 2007+
        ];
        for (let i = 0; i < files.length; i++) {
            // console.log(files[i].type);
            
            if (!allowedTypes.includes(files[i].type)) {
                console.error("Invalid file type: " + files[i].type);
                notification('Invalid file type. Please upload a file in one of the supported formats (JPEG, PNG, GIF, MP4, WebM, OGG, PDF, DOC, DOCX).', 'error');
               // alert("Invalid file type. Please upload a file in one of the supported formats (JPEG, PNG, GIF, MP4, WebM, OGG, PDF, DOC, DOCX).");
                return; // Exit the function if the file type is invalid
            }
            
        }
       
        if (files.length) {
            
            console.log(files[0]);
            console.log(formVar);
            
            form[formVar] = files[0];
            console.log(form);
            
            console.log(previewVar);
            if(previewVar != ''){    
                console.log('not empty');
                        
                // i don't know what this is need more context    
                // i assume you have set some data in consumer
                // you can try getCurrentInstance
                previewVar = URL.createObjectURL(files[0]);
            } else {
                console.log('empty path');
                
            }
        } else {
            console.log("No file ")
        }
    }
    
    // function onFileChange(event, formVar, previewVar) {
    //     let files = event.target.files;
    //     if (files.length) {
    //         const formData = new FormData();
    //         formData.append(formVar, files[0]);
            
    //         // Optionally set the image preview
    //         if (previewVar && typeof previewVar.value !== 'undefined') {
    //             previewVar.value = URL.createObjectURL(files[0]);
    //         }
    
    //         // Log or use formData to send the file to the server
    //         console.log(formData.get(formVar)); // This should log the File object
    
    //         // Optionally, you can send formData to the server here or in another function
    //         // axios.post('your-upload-url', formData, {
    //         //     headers: {
    //         //         'Content-Type': 'multipart/form-data'
    //         //     }
    //         // }).then(response => {
    //         //     console.log('File uploaded successfully');
    //         // }).catch(error => {
    //         //     console.error('Error uploading file:', error);
    //         // });
    //     }
    // }
    
    
    
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
        FileInput,TagInput
    }
}

