<template>

    <div class="login-page">
        <main class="main">
            <div class="logo">
                <Logo />
            </div>
            <h1 class="title">Sua comunidade dev,<br> <strong>agora conectada.</strong></h1>
            <img 
                class="welcome-image"
                src="../assets/images/login-image.png" 
                alt="Uma imagem que mostra várias postagens em uma rede social">
        </main>

        <aside class="login-container">
            <h2 class="tech-text">Entre no codegram</h2>
            <form class="login-form" @submit.prevent="login">

                <BaseFormInput
                    v-model="form.email"
                    id="email"
                    type="email" 
                    name="email"
                    label="Digite seu email"
                />

                <BaseFormInput
                    v-model="form.password"
                    id="password"
                    type="password" 
                    name="password" 
                    label="Digite sua senha"
                />

                <BaseButton 
                    variant="login"
                    type="submit"
                    text="Entrar"
                />
            </form>

            <BaseButton 
                variant="ghost"
                text="Esqueceu sua senha?"
            />

            <BaseButton 
                variant="outline"
                text="Criar uma nova conta"
            />
        </aside>
    </div>
</template>

<script setup>
import BaseButton from '@/components/BaseButton.vue';
import BaseFormInput from '@/components/BaseFormInput.vue';
import Logo from '@/components/Logo.vue';
import authService from '@/services/authService';
import { reactive } from 'vue';

const form = reactive({
    email: '',
    password: ''
})

async function login() {
    try {
        const response = await authService.login(form)

        localStorage.setItem('api-token', response.data.token)

    } catch (error) {
        console.error(error)
    }
}

</script>

<style scoped>
    .login-page {
        display: flex;
        background-color: var(--color-background);
        width: 100vw;
        height: 100vh;
    }

    .main {
        background-color: var(--color-background);
        flex: 9;
    }

    .logo {
        height: 14rem;
    }

    .title {
        color: var(--text-primary);
        margin-left: 4rem;
        font-size: 3rem;
    }

    .login-container {
        color: var(--text-primary);
        font-size: 1.0625rem;
        background: var(--main-gradient);
        display: flex;
        justify-content: center;
        flex-direction: column;
        padding: 4rem;
        gap: 30px;
        flex: 4;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        color: var(--text-primary);
    }

    .welcome-image {
        height: auto;
        width: 50rem;
    }

</style>