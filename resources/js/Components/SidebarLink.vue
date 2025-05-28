<template>
    <Link class="block py-1 pl-4 -ml-px border-l-2 border-transparent text-sm text-slate-700 hover:border-slate-400 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-300 hs-scrollspy-active:font-medium 
    hs-scrollspy-active:text-blue-600 dark:hs-scrollspy-active:text-blue-400 active" :class="active? 'active':''" 
    :href="href">
    <slot></slot>
    </Link>
    <!-- <Link :href="href" class="pr-2 pl-1 py-2 text-sm leading-5 bg-theme1 border-b border-gray-300 theme1-link flex items-center gap-1" :class="active? 'theme1-link-active':''"><slot></slot></Link> -->
</template>
<script>
    import { Link } from '@inertiajs/vue3'
    export default{
        props:{href:'',active:{default:''}},
        components:{Link}
    }
</script>


