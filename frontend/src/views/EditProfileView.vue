<template>
    <main class="edit-profile-page">
        <section class="edit-profile-container">

            <header class="edit-profile-header">
                <h1>Editar perfil</h1>
            </header>

            <form
                class="edit-profile-form"
                @submit.prevent="updateProfile"
            >
                <div class="photo-group">
                    <div class="photo-preview">
                        <img
                            v-if="photoPreview"
                            :src="photoPreview"
                            alt="Prévia da foto de perfil"
                        >
                        <i
                            v-else
                            class="bi bi-person-circle avatar"
                        ></i>
                    </div>

                    <label
                        for="profile-photo"
                        class="button photo-button"
                    >
                        Alterar foto
                    </label>

                    <input
                        id="profile-photo"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        @change="handlePhotoChange"
                        hidden
                    >
                </div>

                <div class="form-group">
                    <p>Nome:</p>
                    <BaseFormInput
                        v-model="form.name"
                        :error="errors.name"
                        @blur="errors.name = validateName(form.name)"
                        id="name"
                        type="text"
                        name="name"
                        label="Nome"
                    />
                </div>

                <div class="form-group">
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
                </div>

                <div class="bio-group">
                    <label for="bio">
                        Bio
                    </label>

                    <textarea
                        id="bio"
                        v-model="form.bio"
                        maxlength="500"
                        rows="5"
                        class="custom-input"
                        @blur="errors.bio = validateBio(form.bio)"
                    ></textarea>

                    <span class="bio-counter">
                        {{ form.bio?.length ?? 0 }}/500
                    </span>
                </div>

                <div class="form-actions">

                    <button
                        type="button"
                        class="button cancel-button"
                        @click="cancel"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="button save-button"
                        :disabled="loading"
                    >
                        {{ loading ? 'Salvando...' : 'Salvar' }}
                    </button>

                </div>
            </form>

        </section>

    </main>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';
import BaseFormInput from '@/components/BaseFormInput.vue';
import { 
    validateUsername,
    validateBio,
    validateName    
} from '@/validation/userValidation';
import userService from '@/services/userService';

const router = useRouter();
const authStore = useAuthStore();

const loading = ref(false);
const error = ref(null);

const selectedPhoto = ref(null);

const errors = reactive({
    name: null,
    username: null,
    bio: null
});

const form = reactive({
    name: '',
    username: '',
    bio: ''
});

const photoPreview = ref(
    authStore.user?.avatarUrl ?? null
);

const loadUser = () => {
    form.name = authStore.user?.name ?? ''
    form.username = authStore.user?.username ?? ''
    form.bio = authStore.user?.bio ?? ''
};

const updateProfile = async () => {

    errors.name = validateName(form.name);
    errors.username = validateUsername(form.username);
    errors.bio = validateBio(form.bio);

    if (
        errors.name ||
        errors.username ||
        errors.bio
    ) {
        return;
    }

    loading.value = true;
    error.value = null;

    try {

        const data = new FormData();
            data.append('_method', 'PATCH');
            data.append('name', form.name);
            data.append('username', form.username);
            data.append('bio', form.bio ?? '');

        if (selectedPhoto.value) {
            data.append(
                'profile_photo',
                selectedPhoto.value
            );
        }

        const response = await userService.updateProfile(data);

        authStore.setUser(response.data);

        router.push(`/profile/${response.data.username}`);

    } catch (error) {
        if (error.response?.status === 422) {
            console.log(error.response);
            Object.keys(errors).forEach(field => {
                errors[field] = null
            });

            const backendErrors = error.response.data.errors;

            Object.keys(backendErrors).forEach(field => {
                if (field in errors) {
                    errors[field] = backendErrors[field]?.[0] ?? null;
                }
            });
        }
    } finally {
        loading.value = false
    }
}

const handlePhotoChange = (event) => {
    const file = event.target.files[0];

    if (!file) return;

    selectedPhoto.value = file;

    photoPreview.value = URL.createObjectURL(file);
}

const cancel = () => {
    router.back();
}

onMounted(() => {
    loadUser();
});
</script>

<style scoped>
.edit-profile-page {
    color: var(--text-primary);
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    align-items: center;
    justify-content: center;
    transform: translateX(-120px);
}

.edit-profile-container {
    display: flex;
    flex-direction: column;
    width: 60%;
    height: 80%;
    justify-content: space-between;
    align-items: center;
}

.avatar {
    font-size: 6rem;
}

.edit-profile-header {
    flex: 1
}

.edit-profile-form {
    flex: 10;
    width: 60%;
}

.bio-counter {
    align-self: flex-end;
    font-size: 12px;
}

.bio-group{
    display: flex;
    flex-direction: column;
}

.custom-input {
    padding: 10px;
    color: var(--text-primary);
    background-color: var(--bg-semi-transparent);
    border: 1px solid #333;
    border-radius: 15px;
}

.custom-input:focus {
    color: var(--text-primary);
    background-color: transparent;
    border: 1px solid white;
    outline: none;
    box-shadow: none;
}

.form-actions {
    display: flex;
    justify-content: center;
    gap: 80px;
}

.button {
    color: var(--text-primary);
    background-color: var(--bg-semi-transparent);
    border-radius: 8px;
    border: none;
    padding: 6px;
    min-width: 80px;
}

.photo-button {
    cursor: pointer;
}

.save-button {
    background-color: var(--button-green);
}

.cancel-button {
    background-color: var(--button-blue);
}

.photo-group {
    display: flex;
    flex-direction: column;
    gap: 25px;
    align-items: center;
}

.photo-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0; 

    display: flex;
    align-items: center;
    justify-content: center;

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
}


</style>