<template>

    <div
        v-if="open"
        class="post-detail-overlay"
        @click.self="close"
    >

        <button
            class="close-button"
            @click="close"
        >
            <i class="bi bi-x-lg"></i>
        </button>


        <article class="post-detail">

            <div class="post-detail-media">
                <PostMedia
                    :images="post.images"
                    :full-height="true"
                />
            </div>


            <div class="post-detail-content">
                <header class="post-detail-header">
                    <div class="author">
                        <UserHeader
                            :avatarUrl="post.author.avatarUrl"
                            :username="post.author.username"
                            :name="post.author.name"
                            size="medium"
                        />
                    </div>

                    <button
                        v-if="isOwner"
                        type="button"
                        class="delete-button"
                        title="Excluir publicação"
                        @click="showDeleteConfirm = true"
                    >
                        <i class="bi bi-trash"></i>
                    </button>
                </header>


                <div class="post-detail-body">

                    <p class="caption">
                        <strong>
                            {{ post.author?.username }}
                        </strong>

                        {{ post.caption }}
                    </p>

                    <hr class="ruler">

                    <div class="comments">
                        <div
                            v-for="comment in comments"
                            :key="comment.id"
                            class="comment-container"
                        >
                            <UserHeader
                                :avatar-url="comment.user.avatarUrl"
                                :username="comment.user.username"
                            />

                            <template v-if="editingCommentId === comment.id">
                                <input
                                    v-model="editingCommentText"
                                    type="text"
                                    class="editing-input"
                                >

                                <button
                                    @click="updateComment(comment.id)"
                                    class="action-button"
                                >
                                    Salvar
                                </button>

                                <button
                                    @click="cancelEditingComment"
                                    class="action-button"
                                >
                                    Cancelar
                                </button>
                            </template>

                            <template v-else>
                                <p class="comment">
                                    {{ comment.comment }}
                                </p>

                                <div
                                    v-if="comment.isMine"
                                    class="comment-actions"
                                >
                                    <button 
                                        @click="startEditingComment(comment)"
                                        class="action-button"
                                    >
                                        Editar
                                    </button>

                                    <button 
                                        @click="deleteComment(comment.id)"
                                        class="action-button"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>


                <footer class="post-detail-footer">
                    <PostActions
                        :commentsCount="commentsCount"
                        :localLikesCount="likesCount"
                        :isLiked="isLiked"
                        @like="$emit('like')"
                    />

                    <hr class="ruler">

                    <div class="user-comment">
                        <input 
                            v-model="newComment" 
                            type="text" 
                            placeholder="Deixe seu comentário"
                            @keyup.enter='createComment'
                        >

                        <button
                            @click="createComment"
                            :disabled="!newComment.trim()"
                            class="action-button"
                        >
                            Postar
                        </button>
                    </div>

                </footer>

            </div>

        </article>

    </div>

    <ConfirmModal
        :open="showDeleteConfirm"
        title="Excluir publicação?"
        message="Essa ação não pode ser desfeita."
        confirm-text="Excluir"
        danger
        :loading="deletingPost"
        @cancel="showDeleteConfirm = false"
        @confirm="handleDelete"
    />

</template>

<script setup>
import { watch, ref, computed, onMounted } from 'vue';
import PostActions from './PostActions.vue';
import PostMedia from './PostMedia.vue';
import UserHeader from './UserHeader.vue';
import ConfirmModal from './ConfirmModal.vue';
import postService from '@/services/postService.js';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();

const comments = ref([]);
const newComment = ref('');
const editingCommentId = ref(null);
const editingCommentText = ref('');
const showDeleteConfirm = ref(false);
const deletingPost = ref(false);

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    },

    post: {
        type: Object,
        required: true
    },

    likesCount: {
        type: [Number, String],
        required: true
    },

    commentsCount: {
        type: [Number, String],
        required: true
    },

    isLiked: {
        type: Boolean,
        required: true,
    },
        
    fullHeight: {
        type: Boolean,
        default: false
    }
});
const emit = defineEmits([
    'close',
    'like',
    'comment-created',
    'comment-deleted',
    'deleted'
]);

const isOwner = computed(() => {
    return authStore.user?.id === props.post.author?.id;
});

const close = () => {
    showDeleteConfirm.value = false;
    emit('close');
};

const handleDelete = async () => {
    if (deletingPost.value) return;

    deletingPost.value = true;

    try {
        await postService.deletePost(props.post.id);

        showDeleteConfirm.value = false;

        emit('deleted', props.post.id);

        close();
    } catch (error) {
        console.log(error);
    } finally {
        deletingPost.value = false;
    }
};

const createComment = async () => {
    if (! newComment.value.trim()) {
        return;
    }

    try {
        const response = await postService.createComment(
            props.post.id,
            newComment.value,
        );

        comments.value.push(response.data);

        newComment.value = '';

        emit('comment-created');

    } catch (e) {
        console.log(e);
    }
}

const cancelEditingComment = () => {
    editingCommentId.value = null;
    editingCommentText.value = '';
};

const startEditingComment = (comment) => {
    editingCommentId.value = comment.id;
    editingCommentText.value = comment.comment;
};

const updateComment = async (commentId) => {
    if (!editingCommentText.value.trim()) {
        return;
    }

    try {
        const response = await postService.updateComment(
            commentId,
            editingCommentText.value
        );

        const index = comments.value.findIndex(
            comment => comment.id === commentId
        );

        if (index !== -1) {
            comments.value[index] = response.data;
        }

        cancelEditingComment();

    } catch (error) {
        console.log(error);
    }
};

const deleteComment = async (commentId) => {
    try {
        await postService.deleteComment(commentId);

        comments.value = comments.value.filter(
            comment => comment.id !== commentId
        );

        emit('comment-deleted');

    } catch (error) {
        console.log(error);
    }
};

const loadComments = async () => {
    const response = await postService.getComments(props.post.id)

    comments.value = response.data
}

onMounted(() => {
    if (props.open) {
        loadComments()
    }
});

watch(
    () => props.open,
    async (isOpen) => {
        if (!isOpen) {
            return;
        }

        const response = await postService.getComments(props.post.id);

        comments.value = response.data;
    }
);

</script>

<style scoped>
.author {
    color: var(--text-primary);
}

.caption {
    color: var(--text-primary);
}

.post-detail-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;

    background: rgba(0, 0, 0, 0.75);

    display: flex;
    align-items: center;
    justify-content: center;
}

.post-detail {
    width: 90vw;
    max-width: 1000px;
    height: 80vh;

    display: flex;

    background: #333;
    border-radius: 8px;
    overflow: hidden;
}

.post-detail-header {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.delete-button {
    border: none;
    background: transparent;
    color: var(--text-tertiary);
    font-size: 1.1rem;
    cursor: pointer;
    padding: 6px;
}

.delete-button:hover {
    color: #d64545;
}

.post-detail-body {
    flex: 9;
}

.comments {
    color: var(--text-tertiary);
    font-size: 0.8rem;
    max-height: 480px;
    overflow-y: auto;
}

.comment-actions {
    display: flex;
    gap: 5px;
    font-size: 0.7rem;
}

.comments::-webkit-scrollbar {
    display: none;
}

.post-detail-footer {
    flex: 1.5;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: var(--text-primary);
}

.ruler {
    color: var(--text-primary);
    margin: 5px 0px;
}

.actions {
    padding: 0 10px;
    flex: 1;
}

.user-comment {
    flex: 4;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    

    input {
        background-color: transparent;
        border: none;
        outline: none;
        color: var(--text-primary);
    }
}

.action-button {
    background-color: transparent;
    border: none;
    color: var(--button-blue);
    margin: 0 4px;
}

.editing-input {
    padding: 4px;
    background-color: transparent;
    border: 1px solid var(--button-blue);
    border-radius: 10px;
    outline: none;
    color: var(--text-primary);
}

.post-detail-media {
    flex: 1;
    min-width: 0;
    height: 100%;
}

.post-detail-content {
    width: 400px;
    padding: 10px;

    display: flex;
    flex-direction: column;
}

.close-button {
    position: fixed;
    top: 20px;
    right: 20px;

    border: none;
    background: none;

    color: white;
    font-size: 24px;

    cursor: pointer;
}
</style>
