<template>
    <footer class="bg-theme1 p-2 pt-6 mt-6 max-h-full">
        <hr class="text-white"/>
        <div class="flex  justify-center text-xs py-2">
            <div class="flex flex-col justify-center sm:flex-row sm:justify-between gap-2">
                <span class="text-center">Copyright &#169; {{new Date().getFullYear()}} {{$page.props.config.company.name}}</span>
                <span class="text-yellow-400 italic font-semibold text-center flex items-center gap-1"><unicon name="bolt" fill="white" class="icon-sm"/> Powered by <a :href="$page.props.config.developer.website" target="_blank" class="underline">{{$page.props.config.developer.name}}</a></span>
            </div>
        </div>
    </footer>
</template>
<script>
import { Link } from '@inertiajs/vue3';
    export default{
        components:{Link},
    }
</script>
