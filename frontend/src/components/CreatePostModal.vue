<template>
  <Transition name="modal">
    <div
      v-if="open"
      class="overlay"
      @click="$emit('close')"
    >
      <div
        class="create-post-modal"
        @click.stop
      >
        <button class="close" @click="$emit('close')">
          ✕
        </button>

        <h2>Criar publicação</h2>

        <div v-if="!preview" class="upload-area">
          <label for="images">
              Arraste fotos aqui
              <span>ou clique para selecionar</span>
          </label>
        </div>

        <div v-else class="preview">
          <img :src="preview" alt="Preview da imagem">
        </div>

        <input
          id="images"
          name="images"
          type="file"
          accept="image/*"
          hidden
          @change="handleFile"
        >

        <textarea
          id="caption"
          placeholder="Escreva uma legenda..."
          v-model="caption"
        ></textarea>

        <BaseButton
          variant="blue"
          :text="publishing ? 'Publicando...' : 'Publicar'"
          @click = 'publish'
        />
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue';
import BaseButton from './BaseButton.vue';
import postService from '@/services/postService.js';

defineProps({
    open: Boolean
});

const emit = defineEmits(['close']);

const preview = ref(null);
const caption = ref('');
const image = ref(null)
const publishing = ref(false);

function handleFile(event) {
    const file = event.target.files[0]

    if (! file) return;

    image.value = file;
    preview.value = URL.createObjectURL(file);
}

async function publish() {
    if (publishing.value) return;

    publishing.value = true;

    try {
        await postService.create(image.value, caption.value);

        resetForm();

        emit('close');
    } catch (error) {
        console.log(error);
    } finally {
        publishing.value = false;
    }
}

function resetForm() {
    preview.value = null;
    caption.value = '';
    image.value = null;
}


</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .create-post-modal,
.modal-leave-active .create-post-modal {
    transition: transform 0.25s ease, opacity 0.25s ease;
}

.modal-enter-from .create-post-modal,
.modal-leave-to .create-post-modal {
    opacity: 0;
    transform: scale(0.95) translateY(12px);
}

.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);

    display: flex;
    justify-content: center;
    align-items: center;

    z-index: 1000;
}

.create-post-modal {
    width: 500px;
    max-width: 90%;
    background: var(--bg-modal);
    color: var(--text-primary);
    border-radius: 12px;
    padding: 24px;

    display: flex;
    flex-direction: column;
    gap: 16px;

    position: relative;

    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
}

h2 {
    margin: 0;
    color: var(--text-primary);
}

.close {
    position: absolute;
    top: 16px;
    right: 16px;
    border: none;
    background: transparent;
    color: var(--text-primary);
    font-size: 20px;
    cursor: pointer;
}

.upload-area {
    border: 2px dashed var(--bg-dark-gray);
    border-radius: 10px;
    padding: 50px;
    text-align: center;
    cursor: pointer;
    color: var(--text-primary);

    display: flex;
    flex-direction: column;
    gap: 8px;
}

.upload-area label {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.upload-area label span {
    color: var(--text-tertiary);
    font-size: 0.85rem;
}

.upload-area:hover {
    border-color: var(--button-blue);
}

textarea {
    padding: 10px;
    min-height: 120px;
    resize: none;

    background-color: var(--bg-semi-transparent);
    border: 1px solid #333;
    border-radius: 8px;
    color: var(--text-primary);
    font-family: inherit;
}

textarea:focus {
    outline: none;
    border-color: white;
    background-color: transparent;
}

textarea::placeholder {
    color: var(--text-tertiary);
}

.preview {
    display: flex;
    justify-content: center;
}

.preview img {
    width: 100%;
    max-height: 450px;
    object-fit: contain;
    border-radius: 10px;
}
</style>