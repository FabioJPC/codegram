<template>
    <div class="auth-layout">
        <RouterView />
    </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
</script>

<style scoped>
.auth-layout {
    width: 100%;
    height: 100%;
}
</style>