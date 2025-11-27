<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <Header></Header>
    <main class="main-container">
        <slot>

        </slot>
    </main>
    <Footer></Footer>
</template>

<style scoped lang="scss">

.main-container {
    display: flex;
    flex-direction: column;
    width: 90%;
    margin-inline: auto;
}

</style>
