<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    post: {
        id: number;
        title: string;
        description: string;
        slug: string;
        image?: string | null;
    };
}>();
</script>

<template>
    <AppLayout>
        <h1>{{ props.post.title }}</h1>
        <p>{{ props.post.description }}</p>
    </AppLayout>
</template>

<style scoped lang="scss"></style>
