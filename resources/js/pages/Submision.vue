<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({ layout: AppLayout })

</script>

<template>

    <section class="submit-content">
<h1>Votre article a été soumis à la modération</h1>

        <div class="text-image">
            <img class="illustration" src="../../assets/images/Succeed.png">

            <div>
                <p>Félicitations, votre webtoon a été soumis à notre équipe pour vérification.</p>
                <p>Nous vous tiendrons au courant lorsque celui-ci sera validé.</p>
            </div>

        </div>

    <Link class="btn-custom btn-small btn-purple" href="/">
        <a>Retour à l'accueil</a>
    </Link>

    </section>

</template>

<style scoped lang="scss">

.text-image {
    display: flex;
    flex-direction: column;
    gap: 64px;
}

.submit-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
    align-items: center;
}

h1 {
    color: var(--tertiary-green);
    text-align: center;
    font-size: 24px;
    font-style: normal;
    font-weight: 400;
    line-height: 140%;
    padding: 24px;
}

p {
    color: var(--black);
    text-align: center;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.illustration {
    width: 276px;
    margin-inline: auto;
    object-fit: cover;

    @media (min-width: 768px) {
        width: 50%;
    }
}

</style>
