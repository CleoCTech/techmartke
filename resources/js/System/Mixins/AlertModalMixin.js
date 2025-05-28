import {defineAsyncComponent} from "vue";
import { dialogInjectionKey } from 'gitart-vue-dialog'
const AlertModal = defineAsyncComponent(() => import('@/Components/AlertModal'))

export default{
    mixins:[MenuBarTabsMixin],
    props:{selected:{default:[]}},
    setup(){
        var isLoading = false
        const {
            addDialog,
        } = inject(dialogInjectionKey)

        function onRequestApproval() {
            addDialog({
                component: AlertModal,
                props: {
                    message: 'Haiyee',
                    type: 'confirm',
                },
            })
        }
        return {
            addDialog,onRequestApproval,isLoading
        }
    }
}
