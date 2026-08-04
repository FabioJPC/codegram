<template>
    <div class="form-floating mb-3 custom-input">
        <input 
            :id="id"
            :type="type" 
            :name="name"
            :placeholder="placeholder"
            
            :value="modelValue"
            
            @input="emit('update:modelValue', $event.target.value)"
            @blur="emit('blur')"
            
            class="form-control custom-input"
        >
        <label :for="id">
            {{ label }}
        </label>
    </div>
    <p v-if="error" class="error">
        {{ error }}
    </p>
</template>

<script setup>
const props = defineProps({

    modelValue: String,

    error: String,

    id: String,
    label: String,
    type: {
        type: String,
        default: 'text'
    },
    name: String,
    placeholder: {
        type: String,
        default: ' '
    }

})

const emit = defineEmits([
    'update:modelValue',
    'blur'
])

</script>

<style scoped>
    .custom-input {
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

    .form-floating label {
        color: var(--text-primary);
    }
    
    .form-control:hover {
        border-color: white;
    }

    .error {
        font-size: 0.9rem;
        color: red;
        text-align: center;
    }
</style>