<template>
    <label class="rounded-md px-1 py-0 text-xs text-white">
        <slot></slot>
    </label>
</template>
<script>
    export default{
        props:{bgColor:{default:'bg-gray-300'}}
    }
</script>
