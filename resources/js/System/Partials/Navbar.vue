<template>
    <header>
        <Head :title="title" />
        <nav class="relative bg-gray-900 sticky top-0 z-50 shadow-lg ">
            <div class="flex justify-between items-center py-0 mt-0 mb-0 h-full">
                <!--left side-->
                <div class="flex items-center">
                    <button @click="toggleSidebar()" id="btnSidebar" class="sm:mr-6 inline-flex items-center justify-center p-0 w-8 h-8 rounded-full text-white hover:text-gray-800 hover:bg-gray-300 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" title="Toggle sidebar">
                        <unicon name="bars"/>
                    </button>
                    <jet-application-mark/>
                </div>
                <div class="hidden sm:block items-center">
                    <h5 class="text-xs sm:text-sm md:text-md text-white font-semibold items-center">{{title}}</h5>
                </div>
                <!--right side-->
                <div class="hidden sm:flex">
                    <jet-dropdown>
                        <template #trigger>
                            <div class="flex items-center px-1 cursor-pointer">
                                <img class="h-10 w-10 rounded-full mt-0 mb-0" src="/storage/general/obama.jpg" alt="photo" />
                                <span class="pl-2 text-teal-600 text-sm">{{user != null? user.name:'Default'}}</span>
                            </div>
                        </template>
                        <template #content>
                            <Link :href="route('logout')" method="post" as="button" class="bg-theme1 hover:bg-amber-500 text-teal-600 w-full py-1 text-sm text-left pl-4 flex items-center">
                                <span class="flex items-center w-full "><unicon name="sign-out-alt"/> Log Out</span>
                            </Link>
                        </template>
                    </jet-dropdown>
                </div>
            </div>
        </nav>
    </header>
</template>
<script>
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import { Link,Head } from '@inertiajs/vue3'
export default {
    components:{JetApplicationMark,JetDropdown,JetDropdownLink,Link,Head},
    props:{title:{default:''}},
    setup() {
        const user = computed(() => usePage().props.value.auth.user);
        const company = computed(() => usePage().props.value.config.company);
        return {
            user,company
        }
    },
    methods:{
        toggleSidebar(){
                var sidebar = document.getElementById('sidebar');
                var btnSidebar = document.getElementById('btnSidebar');
                var windowWidth = window.innerWidth;
                if(windowWidth < 640){
                    sidebar.classList.toggle("hidden");
                }else{
                    sidebar.classList.toggle("sm:block");
                    if(sidebar.classList.contains("sm:block") && !sidebar.classList.contains("hidden")){
                        sidebar.classList.add("hidden");
                        btnSidebar.click();
                    }
                }
            }
    },
}
</script>
