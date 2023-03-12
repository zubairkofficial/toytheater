<template lang="">
    <div class="text-center" v-if="show">
        <h1>Bad Luck!!!</h1>
        <router-link class="btn-start" to="/">Go To Home</router-link>
    </div>
</template>
<script>
export default {
    props:{
        show:Boolean
    }
}
</script>
<style lang="">
    
</style>