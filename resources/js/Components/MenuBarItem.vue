<template>
    <Link v-if="type == 'link'" class="flex items-center gap-1 text-xs" :class="[isDisabled? 'disabled cursor-not-allowed text-gray-300':'text-blue-600']"  :disabled="isDisabled? true:false">
        <unicon :name="iconName" class="icon-xs" :fill="isDisabled? 'gray' : 'blue'"/>
        <span class=""><slot></slot></span>
    </Link>
    <button v-else-if="type == 'button'" class="flex items-center gap-1 text-xs" :class="[isDisabled? 'disabled cursor-not-allowed text-gray-300':'text-blue-600']" :disabled="isDisabled? true:false">
        <unicon :name="iconName" class="icon-xs" :fill="isDisabled? 'gray' : 'blue'"/>
        <span class=""><slot></slot></span>
    </button>
</template>
<script>
    import { Link } from '@inertiajs/vue3'
    export default{
        props:{
            type:{default:'button'},
            isDisabled:{default:false},
            iconName:{default:'bars'},
        },
        components:{Link},
    }
</script>
