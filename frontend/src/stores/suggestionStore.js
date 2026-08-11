import { defineStore } from 'pinia';
import { ref } from 'vue';
import userService from '@/services/userService';

export const useSuggestionStore = defineStore('suggestions', () => {

    const suggestions = ref([]);
    const error = ref(null);

    const loadSuggestions = async () => {

        error.value = null;

        try {
            const response = await userService.getSuggestions()

            suggestions.value = response;

        } catch (e) {
            error.value = 'Não foi possível carregar as sugestões.';
            console.log(e);
        }
    }

    const $reset = () => {
        suggestions.value = [];
        error.value = null;
    }

    return {
        suggestions,
        error,
        loadSuggestions,
        $reset
    }
})