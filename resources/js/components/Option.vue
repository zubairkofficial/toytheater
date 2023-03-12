<template lang="">
    <button class="option" :class="isSelected ? 'selected' : ''">
        {{ text }}
    </button>
</template>
<script>
export default {
    props:{
        text:String,
        isSelected:Boolean,
    }
}
</script>