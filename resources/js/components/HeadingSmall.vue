<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <header>
        <h3>{{ title }}</h3>
        <p v-if="description">
            {{ description }}
        </p>
    </header>
</template>
