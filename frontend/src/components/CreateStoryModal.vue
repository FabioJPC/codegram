<template>
  <Transition name="modal">
    <div
      v-if="open"
      class="overlay"
      @click="handleClose"
    >
      <div
        class="create-story-modal"
        @click.stop
      >
        <button class="close" @click="handleClose">
          ✕
        </button>

        <h2>Adicionar ao seu story</h2>

        <div v-if="!preview" class="upload-area">
          <label for="story-image">
              Arraste uma foto aqui
              <span>ou clique para selecionar</span>
          </label>
        </div>

        <div v-else class="preview">
          <img :src="preview" alt="Preview do story">
        </div>

        <input
          id="story-image"
          name="image"
          type="file"
          accept="image/*"
          hidden
          @change="handleFile"
        >

        <p v-if="storyStore.createError" class="error">
          {{ storyStore.createError }}
        </p>

        <BaseButton
          variant="blue"
          :text="storyStore.creating ? 'Publicando...' : 'Publicar story'"
          :disabled="!image || storyStore.creating"
          @click="publish"
        />
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue';
import BaseButton from './BaseButton.vue';
import { useStoryStore } from '@/stores/storyStore';

defineProps({
    open: Boolean
});

const emit = defineEmits(['close']);

const storyStore = useStoryStore();

const preview = ref(null);
const image = ref(null);

function handleFile(event) {
    const file = event.target.files[0]

    if (! file) return;

    image.value = file;
    preview.value = URL.createObjectURL(file);
}

async function publish() {
    if (storyStore.creating || !image.value) return;

    try {
        await storyStore.createStory(image.value);

        resetForm();

        emit('close');
    } catch (error) {
        console.log(error);
    }
}

function handleClose() {
    resetForm();
    emit('close');
}

function resetForm() {
    preview.value = null;
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

.modal-enter-active .create-story-modal,
.modal-leave-active .create-story-modal {
    transition: transform 0.25s ease, opacity 0.25s ease;
}

.modal-enter-from .create-story-modal,
.modal-leave-to .create-story-modal {
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

.create-story-modal {
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

.error {
    color: #ee2a7b;
    font-size: 0.85rem;
    margin: 0;
}
</style>
