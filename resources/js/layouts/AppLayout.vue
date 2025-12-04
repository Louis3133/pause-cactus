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
    padding: 16px;
    padding-top: 38px;
    padding-bottom: 64px;
    border-radius: 54px 54px 0px 0px;
    margin-top: -54px;
    background: white;

    @media (min-width: 768px) {
        padding: 32px 64px 64px 64px;
    }
}

</style>
