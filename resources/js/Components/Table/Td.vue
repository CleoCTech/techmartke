<template>
    <td class="px-1 py-1 sm:py-2">
        <slot></slot>
    </td>
</template>
<script>
    export default{
        props:{colspan:{default:'2'}}
    }
</script>
