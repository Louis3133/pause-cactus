<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
    serie: {
        id: number;
        title: string;
        description: string;
    }
}>();
</script>

<template>
    <AppLayout>
        <h1>{{ serie.title }}</h1>
        <p>{{ serie.description }}</p>
    </AppLayout>
</template>
