<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue';


defineProps<{
    post: {
        id: number;
        title: string;
    };
    webtoonUrl: string;
}>();


defineOptions({ layout: AppLayout })

</script>


<template>
        <img class="webtoon" :src="webtoonUrl" :alt="post.title">
</template>

<style scoped lang="scss">
@use '../../scss/fluid.scss' as *;

.webtoon {
    max-width: 900px;
    margin-inline: auto;
    width: #{fluid(300px, 950px, 320px, 1200px)};
}

</style>
