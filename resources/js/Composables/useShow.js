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

export const showProps = {
  setup:Object,
  cardData:Object,
  selected:{
    type: Array,
    default: ()=>([])
  },
}

export const useShow = (props) => {
  return {
    xGrid,
    xGridCol,
    xLoading,
    xPanel,
    xShowGroup,
    xBadge,
    xShowTemplate
  }
}