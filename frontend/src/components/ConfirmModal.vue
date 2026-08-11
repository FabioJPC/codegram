<template>
    <Transition name="modal">
        <div
            v-if="open"
            class="overlay"
            @click="$emit('cancel')"
        >
            <div
                class="confirm-modal"
                @click.stop
            >
                <h3>{{ title }}</h3>

                <p v-if="message">
                    {{ message }}
                </p>

                <div class="confirm-actions">
                    <button
                        type="button"
                        class="button cancel-button"
                        :disabled="loading"
                        @click="$emit('cancel')"
                    >
                        {{ cancelText }}
                    </button>

                    <button
                        type="button"
                        class="button confirm-button"
                        :class="{ danger }"
                        :disabled="loading"
                        @click="$emit('confirm')"
                    >
                        {{ loading ? 'Excluindo...' : confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
defineProps({
    open: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Tem certeza?'
    },
    message: {
        type: String,
        default: ''
    },
    confirmText: {
        type: String,
        default: 'Confirmar'
    },
    cancelText: {
        type: String,
        default: 'Cancelar'
    },
    danger: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    }
});

defineEmits(['confirm', 'cancel']);
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .confirm-modal,
.modal-leave-active .confirm-modal {
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.modal-enter-from .confirm-modal,
.modal-leave-to .confirm-modal {
    opacity: 0;
    transform: scale(0.95) translateY(12px);
}

.overlay {
    position: fixed;
    inset: 0;
    z-index: 1100;

    background: rgba(0, 0, 0, 0.65);

    display: flex;
    align-items: center;
    justify-content: center;
}

.confirm-modal {
    width: 360px;
    max-width: 90%;

    background: var(--bg-modal);
    color: var(--text-primary);

    border-radius: 12px;
    padding: 24px;

    display: flex;
    flex-direction: column;
    gap: 16px;

    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
}

h3 {
    margin: 0;
    font-size: 1.1rem;
}

p {
    margin: 0;
    color: var(--text-tertiary);
    font-size: 0.9rem;
    line-height: 1.4;
}

.confirm-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.button {
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 600;
    cursor: pointer;
}

.button:disabled {
    opacity: .6;
    cursor: not-allowed;
}

.cancel-button {
    background-color: var(--bg-semi-transparent);
    color: var(--text-primary);
}

.confirm-button {
    background-color: var(--button-blue);
    color: var(--text-primary);
}

.confirm-button.danger {
    background-color: #d64545;
}
</style>
