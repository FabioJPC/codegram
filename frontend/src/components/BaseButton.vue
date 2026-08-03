<template>
    <button 
        class="base-button"
        :class="`base-button--${variant}`" 
        :type="type">{{ text }}</button>
</template>

<script setup>

defineProps({
    variant: String,
    text: String,
    type: {
        type: String,
        default: 'button'
    }
})

</script>

<style scoped>

.base-button {
    width: 100%;
    border-radius: 20px;
    cursor: pointer;
    padding: 0.625rem 1.25rem;
    margin-bottom: 8px;
    font-weight: 600;
}

.base-button--login {
    background-color: var(--bg-light-gray);
}

.base-button--blue {
    box-sizing: border-box;
    background-color: var(--button-blue);
    border: 1px solid transparent;
}

.base-button--blue:hover {
    border: 1px solid white;
    box-shadow: 2px 2px 8px var(--button-blue);
}

.base-button--ghost {
    background-color: var(--bg-transparent);
    border: none;
    color: var(--text-primary);
    font-weight: 600;
}

.base-button--ghost:hover {
    background-color: var(--bg-dark-gray);

}

.base-button--outline {
    border: 1px solid var(--button-blue);
    background-color: var(--bg-transparent);
    color: var(--button-blue);

}
</style>