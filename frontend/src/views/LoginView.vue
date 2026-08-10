<template>

    <div class="login-page">
        <main class="main">
            <div class="logo">
                <Logo />
            </div>
            <h1 class="title">Sua comunidade dev,<br> <strong>agora conectada.</strong></h1>
            <div class="welcome-container">
                <img 
                    class="welcome-image"
                    src="../assets/images/login-image.png" 
                    alt="Uma imagem que mostra várias postagens em uma rede social"
                >
            </div>
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

                <p v-if="error" class="error">
                    {{ error }}
                </p>

                <BaseButton 
                        variant="login"
                        type="submit"
                        text="Entrar"
                />
            </form>

            <RouterLink to="/register">
                <BaseButton 
                    variant="outline"
                    text="Criar uma nova conta"
                />
            </RouterLink>
        </aside>
    </div>
</template>

<script setup>
import BaseButton from '@/components/BaseButton.vue';
import BaseFormInput from '@/components/BaseFormInput.vue';
import Logo from '@/components/Logo.vue';
import { ref, reactive } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();

const router = useRouter()

const form = reactive({
    email: '',
    password: ''
})

const error = ref('')

async function login() {

    error.value = ''

    try {
        await authStore.login(form)

        router.push('/feed')

    } catch (err) {
        error.value = 
            err.response?.data?.message ??
            'Email ou senha inválidos'
    }
}

</script>

<style scoped>
    .login-page {
        display: flex;
        background-color: var(--color-background);
        width: 100%;
        height: 100%;
    }

    .main {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background-color: var(--color-background);
        flex: 9;
        padding: 4px;

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

    .welcome-container {
        flex: 1;
    }

    .welcome-image {
        flex: 1;
        height: auto;
        width: 80%;
        object-fit: cover;
    }

    .error {
        color: red;
        text-align: center;
    }

</style>