import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
//components
import xGrid from '@/Components/Grid.vue'
import xGridCol from '@/Components/GridCol.vue'
import xLoading from '@/Components/Loading.vue'
import xPanel from '@/Components/Panel.vue'
import xShowGroup from '@/Components/ShowGroup.vue'
import xBadge from '@/Components/Badge.vue'
import xShowTemplate from '@/System/Pages/Templates/CRUD/Show.vue'
export default {
    components:{xShowTemplate,xShowGroup,xPanel,xLoading,xGridCol,xGrid,xBadge},
    props:{
        setup:Object,
        cardData:Object,
        selected:{default:[]},
    },
    // layout: (h, page) => { return h(AppLayout, () => page) },
  };
