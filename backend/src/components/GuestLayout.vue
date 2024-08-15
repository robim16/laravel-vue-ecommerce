<template>
    <div>
        <slot></slot>
    </div>
</template>

<script setup>
    const {title} = defineProps({
        title: String
    })
</script>

<style scoped>

</style>