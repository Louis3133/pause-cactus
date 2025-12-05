<script setup lang="ts">


import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout
});

</script>

<template>

<div class="concept-top">
    <h1>Notre concept</h1>
    <img src="../../assets/svg/cactuswrite.svg">
    <p>Avec Pause Cactus, vous pouvez accéder à l’information de manière plus créative et ludique. Ici, nous proposons des actualités orienté de gauche, avec une touche propre à chaque artiste. </p>
</div>
    <div class="sliders">
        <h2>Comment ça marche ? </h2>
        <div class="first-slider">
            <h3>Trouver une actu ?</h3>
        </div>
        <div class="second-slider">
            <h3>Déposer une planche</h3>
        </div>
    </div>




</template>

<style scoped lang="scss">
@use '../../scss/fluid.scss' as *;

.concept-top {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: #{fluid(40px, 64px, 320px, 1500px)};
    margin-bottom: #{fluid(40px, 64px, 320px, 1500px)};

    img {
        width: #{fluid(190px, 450px, 320px, 1500px)};
    }

    p {
        color: #000;
        text-align: center;
        font-family: var(--font-family-sans-serif);
        font-size: #{fluid(14px, 24px, 320px, 1500px)};
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
}

h1 {
    color: var(--purple-dark);
    text-align: center;
    font-size: #{fluid(24px, 40px, 320px, 1500px)};
    font-style: normal;
    font-weight: 400;
    line-height: 140%;
    padding: 24px;
}

.sliders {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: #{fluid(32px, 64px, 320px, 1500px)};

    h2 {
        color: var(--purple-dark);
        font-size: #{fluid(16px, 30px, 320px, 1500px)};
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    h3 {
        color: var(--tertiary-purple-dark);
        font-size: #{fluid(14px, 25px, 320px, 1500px)};
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
}

</style>
