<template>
    <div class="register-page">
        <main class="main">
            <form>

                <i v-on:click="goBack" class="bi bi-arrow-left"></i>

                <h3>Comece agora no Codegram</h3>

                <p>Nome:</p>
                <BaseFormInput
                    id="name"
                    type="text" 
                    name="name" 
                    label="Nome completo"
                />

                <p>Email:</p>
                <BaseFormInput
                    id="email"
                    type="email" 
                    name="email" 
                    label="Email"
                />

                <p>Nome de usuário:</p>
                <BaseFormInput
                    id="username"
                    type="text" 
                    name="username" 
                    label="Nome de usuário"
                />

                <p>Senha:</p>
                <BaseFormInput
                    id="password"
                    type="password" 
                    name="password" 
                    label="Senha"
                />

                <p>Confirme a senha:</p>
                <BaseFormInput
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
import { reactive } from 'vue';

function goBack() {
    try {
        router.back()
    } catch {
        router.push('/login')
    }
}

const form = reactive({
    name: '',
    email: '',
    username: '',
    password: '',
    password_confirmation: ''
})
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