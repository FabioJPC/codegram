<template>
    <div 
        class="post-media-container"
        :class="{ 'full-height' : fullHeight}"
    >
        <Swiper 
            :modules="[Navigation, Pagination]" 
            :navigation="images.length > 1" 
            :pagination="images.length > 1" 
            :space-between="0"  
        >
            <SwiperSlide
                v-for="image in images"
                :key="image.id"    
            >
                <img 
                    :src="image.url" 
                    :alt="`Imagem ${image.position} do post`"
                    class="post-media" 
                    @click.stop="handleClick"   
                >

            </SwiperSlide>
        </Swiper>
    </div>
</template>

<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import { computed } from 'vue';

import 'swiper/css'; 
import 'swiper/css/navigation'; 
import 'swiper/css/pagination';

interface PostImage { 
    id: number; 
    url: string; 
    position: number; 
}

const props = defineProps<{ 
    images: PostImage[],
    fullHeight?: boolean
}>();


const images = computed(() => { 
    return [...props.images].sort(
         (a, b) => a.position - b.position 
    ); 
});

const emit = defineEmits(['click']);

const handleClick = () => {
    emit('click');
};

</script>

<style scoped>
.post-media-container {
    width: 100%;
    height: 500px;
    overflow: hidden;
}

.post-media-container.full-height {
    height: 100%;
}

.post-media-container .swiper {
    width: 100%;
    height: 100%;
}

.post-media-container .swiper-slide {
    width: 100%;
    height: 100%;
}

.post-media {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.post-media-container :deep(.swiper-button-prev),
.post-media-container :deep(.swiper-button-next) {
    width: 20px;
    height: 20px;
}
.post-media-container :deep(.swiper-pagination-bullet) {
    background: white;
    opacity: 0.6;
}

.post-media-container :deep(.swiper-pagination-bullet-active) {
    background: white;
    opacity: 1;
}

</style>