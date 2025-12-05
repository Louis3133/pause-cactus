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
        <h1>{{ post.title }}</h1>
        <img :src="webtoonUrl" :alt="post.title">
</template>

<style scoped lang="scss">

</style>
