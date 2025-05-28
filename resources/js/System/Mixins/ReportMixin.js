import { ref } from 'vue'
import "gitart-vue-dialog/dist/style.css";
import { GDialog} from "gitart-vue-dialog";
import xInput from '@/Components/Input.vue'
import xSelect from '@/Components/Select.vue'
import xButton from '@/Components/Button.vue'

export default{

    components: {
        GDialog,xInput,xSelect,xButton
    }
}
