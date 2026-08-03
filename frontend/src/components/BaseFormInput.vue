<template>
    <div class="form-floating mb-3 custom-input">
        <input 
            :id="id"
            :type="type" 
            :name="name"
            :placeholder="placeholder"
            
            :value="modelValue"
            
            @input="emit('update:modelValue', $event.target.value)"
            
            class="form-control custom-input"
        >
        <label :for="id">
            {{ label }}
        </label>
    </div>
</template>

<script setup>
const props = defineProps({

    modelValue: String,

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
    'update:modelValue'
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
</style>