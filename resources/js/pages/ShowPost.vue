<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    post: {
        id: number;
        title: string;
        description: string;
        slug: string;
        image?: string | null;
        licence?: { id: number; name: string } | null;
    };
}>();
</script>

<template>
    <AppLayout>
        <h1>{{ props.post.title }}</h1>
        <p>{{ props.post.description }}</p>
        <p>{{ props.post.licence.name }}</p>
    </AppLayout>
</template>

<style scoped lang="scss"></style>
