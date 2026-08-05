<template>
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
      ></textarea>

      <BaseButton 
        variant="blue"
        text="Publicar"  
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import BaseButton from './BaseButton.vue';

defineProps({
    open: Boolean
});

const emit = defineEmits(['close']);

const preview = ref(null);

function handleFile(event) {
    const eventFile = event.target.files[0]

    if (! eventFile) return;

    preview.value = URL.createObjectURL(eventFile);
}

</script>

<style scoped>
.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);

    display: flex;
    justify-content: center;
    align-items: center;

    z-index: 1000;
}

.create-post-modal {
    width: 500px;
    max-width: 90%;
    background: white;
    border-radius: 12px;
    padding: 24px;

    display: flex;
    flex-direction: column;
    gap: 16px;

    position: relative;
}

.close {
    position: absolute;
    top: 16px;
    right: 16px;
    border: none;
    background: transparent;
    font-size: 20px;
    cursor: pointer;
}

.upload-area {
    border: 2px dashed #ccc;
    border-radius: 10px;
    padding: 50px;
    text-align: center;
    cursor: pointer;

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
    cursor: pointer;
}


.upload-area:hover {
    border-color: #0095f6;
}

textarea {
    padding: 4px;
    min-height: 120px;
    resize: none;
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