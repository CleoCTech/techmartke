import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
//components
import xGrid from '@/Components/Grid.vue'
import xGridCol from '@/Components/GridCol.vue'
import xLoading from '@/Components/Loading.vue'
import xPanel from '@/Components/Panel.vue'
import xFormGroup from '@/Components/FormGroup.vue'
import xInput from '@/Components/Input.vue'
import xSelect from '@/Components/Select.vue'
import xTextarea from '@/Components/Textarea.vue'
import xCheckbox from '@/Components/Checkbox.vue'
import xButton from '@/Components/Button.vue'
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import AppLayout from '@/System/Layouts/AppLayout.vue'
import xCreateEditTemplate from '@/System/Pages/Templates/CRUD/CreateEdit.vue'

export default {
    components:{xCreateEditTemplate,ckeditor: CKEditor.component,AppLayout,xGrid,xGridCol,xLoading,xPanel,xFormGroup,xInput,xSelect,xTextarea,xCheckbox,Inertia,xButton},
    props:{
        setup:Object,
        cardData:Object,
        selected:{default:[]},
    },
    // layout: (h, page) => { return h(AppLayout, () => page) },
    data(){
        return{
            editor:ClassicEditor,
        }
    },
    mounted(){
        if(this.cardData != null && this.cardData.uuid != null){
            this.setData();
        }
    },
    methods: {
        submit(){
            this.$parent.isLoading = true;
            let formData = new FormData();
            for ( var key in this.form ) {
                formData.append(key, this.form[key]);
            }
            axios.post(this.setup.settings.indexRoute+'/store',formData).then(response => {
                if(response.status == 200){
                    this.$notify({
                        text:response.data.message,
                        type:response.data.type
                    });
                    if(this.form.uuid == null || this.form.uuid == ''){
                        router.visit(this.setup.settings.indexRoute);
                    }
                }
                else{
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                }
                this.$parent.isLoading = false;
            }).catch((error) => {
                if(error.response.status == 422){
                    var errors= [];
                    errors = error.response.data.errors;
                    for (let field of Object.keys(errors)) {
                        this.$notify({
                            text:errors[field][0],
                            type:'error'
                        });
                    }
                }else{
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                }
                this.$parent.isLoading = false;
            })
        },
        onFileChange(event,formVar,previewVar) {
            let files = event.target.files;
            if (files.length)
                this.form[formVar] = files[0];
                if(previewVar != ''){
                    this[previewVar] = URL.createObjectURL(files[0]);
                }
        }
    }

  };
