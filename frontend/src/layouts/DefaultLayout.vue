<template>
<div class="default-layout">
    <div class="sidebar">
        <SideBar/>
    </div>

    <div class="view">
        <RouterView />
    </div>
</div>
</template>

<script setup>
import SideBar from '@/components/SideBar.vue';
import { RouterView } from 'vue-router'

</script>

<style scoped>

.default-layout{
    background-color: var(--bg-main); 
    width: 100%;
    height: 100vh;
    display: flex;
}
.sidebar{
    flex: 2
}

.view {
    flex: 9;
}
</style>