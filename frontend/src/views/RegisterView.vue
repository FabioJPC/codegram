<template>
    <div class="register-page">
        <main class="main">
            <form @submit.prevent="register">
                <i v-on:click="goBack" class="bi bi-arrow-left"></i>

                <h3>Comece agora no Codegram</h3>

                <p>Nome:</p>
                <BaseFormInput
                    v-model="form.name"
                    :error="errors.name"
                    @blur="errors.name = validateName(form.name)"
                    id="name"
                    type="text" 
                    name="name" 
                    label="Nome completo"
                />

                <p>Email:</p>
                <BaseFormInput
                    v-model="form.email"
                    :error="errors.email"
                    @blur="errors.email = validateEmail(form.email)"
                    id="email"
                    type="email" 
                    name="email" 
                    label="Email"
                />

                <p>Nome de usuário:</p>
                <BaseFormInput
                    v-model="form.username"
                    :error="errors.username"
                    @blur="errors.username = validateUsername(form.username)"
                    id="username"
                    type="text" 
                    name="username" 
                    label="Nome de usuário"
                />

                <p>Senha:</p>
                <BaseFormInput
                    v-model="form.password"
                    :error="errors.password"
                    @blur="errors.password = validatePassword(form.password)"
                    id="password"
                    type="password" 
                    name="password" 
                    label="Senha"
                />

                <p>Confirme a senha:</p>
                <BaseFormInput
                    v-model="form.password_confirmation"
                    :error="errors.password_confirmation"
                    @blur="errors.password_confirmation = 
                        confirmPassword(form.password, form.password_confirmation)"
                    id="password_confirmation"
                    type="password" 
                    name="password_confirmation" 
                    label="Confirme a senha"
                />

                <BaseButton
                    variant="blue"
                    text="Registrar"
                    type="submit"
                />
                
                <RouterLink to="/login">
                    <BaseButton
                        variant="ghost"
                        text="Já tenho uma conta" 
                    />
                </RouterLink>

            </form>
        </main>
    </div>

</template>

<script setup>
import BaseButton from '@/components/BaseButton.vue';
import BaseFormInput from '@/components/BaseFormInput.vue';
import router from '@/router';
import authService from '@/services/authService';
import { 
    confirmPassword, 
    validateName, 
    validateUsername, 
    validateEmail,
    validatePassword 
} from '@/validation/userValidation';
import { reactive } from 'vue';

const errors = reactive({
    name: '',
    email: '',
    username: '',
    password: '',
    password_confirmation: ''
})

const form = reactive({
    name: '',
    email: '',
    username: '',
    password: '',
    password_confirmation: ''
})

function goBack() {
    try {
        router.back();
    } catch {
        router.push('/login');
    }
}

async function register() {
    try {
        const response = await authService.register(form);

        authService.setToken(response.data.token);

        router.push('/feed');

    } catch (error) {
        if (error.response?.status === 422) {
            const backendErrors = error.response.data.errors;

            Object.keys(backendErrors).forEach(field => {
                errors[field] = backendErrors[field]?.[0] ?? null;
            });
        }
    }
}
</script>

<style scoped>
.register-page {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: var(--main-darker-gradient);
    color: var(--text-primary);
}

.register-page p{
    margin-bottom: 0;
}

.bi-arrow-left {
    cursor: pointer;
}

</style>