<template>
    <router-link to="/feed">
        <div class="logo-component">
            <img 
                src="../assets/images/codegram-no-background.png" 
                alt="Logo da empresa codegram">
        </div>
    </router-link>
</template>

<script setup>
import router from '@/router';
</script>

<style scoped>
    .logo-component {
        height: 100%;
        width: 100%;
        background-color: transparent;
        
        img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }
    }
</style>