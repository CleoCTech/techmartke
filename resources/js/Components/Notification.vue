<template>
    <ul class="alert alert-danger" v-if="errors && Object.keys(errors).length > 0">
        <li v-for="(error,index) in errors" :key="index">{{error}}</li>
    </ul>
    <div class="bg-green-600 px-2 py-2 rounded-md" v-if="$page.props.flash.success">
        {{$page.props.flash.success}}
    </div>
    <div class="bg-red-600 px-2 py-2 rounded-md" v-if="$page.props.flash.error">
        {{$page.props.flash.error}}
    </div>
</template>
<script>
export default {
    name: "ErrorsAndMessages",
    props: ["errors"]
}
</script>
