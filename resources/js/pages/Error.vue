<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';


defineOptions({ layout: AppLayout })


</script>

<template>

        <div class="error-container">
            <img class="cactus" src="../../assets/svg/cactusDead.svg">
            <div class="content-404">
                <h1 class="error-code">404</h1>
                <h2>Oops... Cette page n’existe pas !</h2>
                <p>Cette page semble ne pas/plus exister mais ne vous inquiétez pas ; retournez sur notre page d'accueil pour continuer d'explorer le site.</p>
                <Link href="/" class="btn-medium btn-purple btn-custom">
                    Retour à l'accueil
                </Link>
            </div>
        </div>
</template>

<style scoped lang="scss">

.cactus {
    width: 210px;

    @media (min-width: 1200px) {
        width: 500px;
    }
}

.content-404 {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;

    @media (min-width: 1200px) {
        align-items: unset;
        gap: 40px;
    }
}

h1 {
    color: var(--purple-dark);
    text-align: center;
    font-size: 40px;
    font-style: normal;
    font-weight: 400;
    line-height: 100%;

    @media (min-width: 1200px) {
        font-size: 80px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        text-align: unset;
    }
}

h2 {
    color: var(--purple-dark);
    text-align: center;
    font-size: 24px;
    font-style: normal;
    font-weight: 400;
    line-height: 140%;

    @media (min-width: 1200px) {
        font-size: 40px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        text-align: unset;
    }
}

p {
    color: #000;
    text-align: center;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;

    @media (min-width: 1200px) {
        font-family: Jost;
        font-size: 24px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        text-align: unset;
    }
}

.error-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    align-items: center;
    padding-top: 12px;

    @media (min-width: 1200px) {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 100px;
        padding: 50px;
    }
}

</style>
