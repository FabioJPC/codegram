<template>
    <div class="feed">
        <nav class="nav">
            <div class="logo">
                <Logo/>
            </div>
            <div class="buttons">
                <i class="bi bi-house-door-fill"></i>
                <i class="bi bi-search"></i>
                <i class="bi bi-plus-circle"></i>
                <i class="bi bi-person-circle"></i>
            </div>
        </nav>
        <main class="main">
            <div class="stories-container">
                
            </div>
        </main>
        <article class="suggestions">
            <div class="profile-container">
                
            </div>
        </article>
    </div>
</template>

<script setup>
import Logo from '@/components/Logo.vue';


</script>

<style scoped>
.feed {
    width: 100%;
    height: 100%;
    padding: 2px;
    display: flex;
    flex-wrap: nowrap;
    justify-content: flex-start;
}

.nav {
    padding: 10px;
    flex: 2 1 0%;
    display: flex;
    flex-direction: column;
    gap: 70px;
}

.buttons {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.logo {
    width: 100%;
    height: 15%;
}

.main {
    flex: 6 1 0%;
}

.suggestions {
    flex: 3 1 0%;
}

i {
    font-size: 1.6rem;
    color: var(--text-primary);
}

</style>