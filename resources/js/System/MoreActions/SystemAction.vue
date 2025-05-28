<template>
    <div>
        <div class="flex gap-2">
            <x-menu-bar-item :type="'button'" @click="updateActions"  :iconName="'bars'" target="_blank">Update Actions</x-menu-bar-item>
        </div>
    </div>
</template>
<script>
    import MenuBarTabsMixin from '@/System/Mixins/MenuBarTabsMixin.js'
    import CrudMethodsMixin from '@/System/Mixins/CrudMethodsMixin.js'
    import VueLoadingMixin from '@/System/Mixins/VueLoadingMixin.js'
    export default{
        mixins:[MenuBarTabsMixin,CrudMethodsMixin,VueLoadingMixin],
        props:{selected:{default:[]}},
        data(){
            return{
                //
            }
        },
        methods:{
            updateActions(){
                this.loadingShowFullPage();
                axios.get('/system/system-action/store').then(response => {
                    this.$notify({
                        text:response.data.message,
                        type:response.data.type
                    });
                    this.loadingHideFullPage();
                    router.visit('/system/system-action');
                }).catch((error) => {
                    this.$notify({
                        text:response.data.message,
                        type:response.data.type
                    });
                    this.loadingHideFullPage();
                })
            },
        }
    }
</script>
