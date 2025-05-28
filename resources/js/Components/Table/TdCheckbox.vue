<template>
    <td class="p-1 w-1.5" @click="onTdClick($event)">
        <x-checkbox @click="onCheck()" :checked="this.$parent.isSelected? true:false"/>
    </td>
</template>
<script>
    import xCheckbox from '@/Components/Checkbox.vue'
    export default{
        components:{xCheckbox},
        props:['item'],
        methods:{
            onCheck(){
                this.$emit('onCheck',this.item);
            },
            onTdClick(event){
                event.stopPropagation();
            }
        }
    }
</script>
