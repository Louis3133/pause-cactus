<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <div>
        <h2>{{ title }}</h2>
        <p v-if="description">
            {{ description }}
        </p>
    </div>
</template>
