<template>
    <div>
        <loading v-model:active="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
        <div class="flex gap-2">
            <x-menu-bar-item :type="'button'" @click="onRequestApproval()" :isDisabled="this.selected.length == 1? false:true"  :iconName="'bars'">Request Approval</x-menu-bar-item>
        </div>
    </div>
</template>
<script>
    import MenuBarTabsMixin from '@/System/Mixins/MenuBarTabsMixin.js'
    import { usePage } from '@inertiajs/vue3'
    import xLoading from '@/Components/Loading.vue'
   import { router } from '@inertiajs/vue3'
    export default{
        components:{xLoading},
        mixins:[MenuBarTabsMixin],
        props:{selected:{default:[]}},
        data(){
            return{
                form:{
                    uuid:null,
                    documentType:1,
                },
                isLoading:false,
           }
        },
        methods:{
            onRequestApproval() {
                if(confirm('Are you sure you want to send this item for approval?')){
                    this.form.uuid = this.selected[0].split('#')[0];
                    this.isLoading = true;
                    axios.post('/system/approval/document/approval-request',this.form).then(response => {
                        this.$notify({
                            text:response.data.message,
                            type:response.data.type
                        });
                        this.isLoading = false;
                        Inertia.visit(this.setup.settings.indexRoute);
                    }).catch((error) => {
                        if(error.response.data.message != undefined){
                            this.$notify({
                                text:error.response.data.message,
                                type:'error'
                            });
                        }else{
                            this.$notify({
                                text:usePage().props.value.defaultErrors.default,
                                type:'error'
                            });
                        }
                        this.isLoading = false;
                    })
                }
            },
        }
    }
</script>
