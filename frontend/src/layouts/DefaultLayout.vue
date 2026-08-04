<template>
<div class="default-layout">
    <RouterView />
</div>
</template>

<script setup>
import { RouterView } from 'vue-router'

</script>

<style scoped>
.default-layout{
    background-color: var(--bg-main); 
    width: 100%;
    height: 100%;  
}
</style>