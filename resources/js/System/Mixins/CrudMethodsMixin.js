import { usePage } from '@inertiajs/vue3'
export default{
    data(){
        return{
            isLoading:false,
        }
    },
    methods:{
        fnPost(url,form,parentIndex){
            let formData = new FormData();
            for ( var key in form) {
                formData.append(key, form[key]);
            };
            this.isLoading = true;
            axios.post(url,formData).then(response => {
                if(response.status == 200){
                    this.$notify({
                        text:usePage().props.value.flash.success,
                        type:'success'
                    });
                }
                else{
                    this.$notify({
                        text:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                }
                this.isLoading = false;
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
                        text:usePage().props.value.flash.error != null? usePage().props.value.flash.error:usePage().props.value.defaultErrors.default,
                        type:'error'
                    });
                }
                this.isLoading = false;
            })
        },
        fnGetData(url){
            this.isLoading = true;
            axios.get(url).then(response => {
                this.isLoading = false;
            }).catch((error) => {
                this.$notify({
                    text:usePage().props.value.flash.error != null? usePage().props.value.flash.error:usePage().props.value.defaultErrors.default,
                    type:'error'
                });
                this.isLoading = false;
            })
        }

    }
}
