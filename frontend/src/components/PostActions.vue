<template>
    <div class="actions">
                <div class="left-actions">
                    <div class="action">
                        <i 
                            :class="[isLiked ? 'bi-heart-fill liked' : 'bi-heart']"
                            class="bi"
                            @click = "$emit('like')"
                        ></i>
                        <span v-if="localLikesCount > 0"> {{ localLikesCount }} </span>
                    </div>

                    <div 
                        class="action"
                        @click="$emit('comments')"
                    >
                        <i class="bi bi-chat"></i>
                        <span v-if="commentsCount > 0"> {{ commentsCount }} </span>
                    </div>

                    <i class="bi bi-send"></i>
                </div>
            </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const emit = defineEmits(['like', 'comments'])

const props = defineProps({
    isLiked: {
        type: Boolean,
        required: true,
    },

    localLikesCount: {
        type: [Number, String],
        required: true,
        
    },

    commentsCount: {
        type: [Number, String],
        required: true,   
    }
})
</script>

<style>
.actions {
    font-size: 1.6rem;
    display: flex;
    justify-content: space-between;
    font-size: 1.2rem;
    color: inherit;
}

.left-actions {
    display: flex;
    gap: 15px;;
}

.liked {
    color: red;
}
</style>