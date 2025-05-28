<template>
    <div class="h-screen sticky top-12">
        <div class="bg-theme1 min-h-screen h-full w-full sm:w-full whitespace-nowrap sticky">
            <sidebar-link :href="'/dashboard'" :active="$page.url == '/dashboard'"><chart-pie-icon class="icon-sm"/> Dashboard</sidebar-link>
        </div>
    </div>
</template>
<script>
    import SidebarLink from '@/Components/SidebarLink.vue'
    export default{
        components:{SidebarLink}
    }
</script>
