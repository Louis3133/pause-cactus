<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout
});

</script>

<template>

    <div class="aboutus">

    <h1>À propos de nous</h1>

        <img class="familytop" src="../../assets/svg/family.svg">

    <p>
        La mission de Pause Cactus est de résumer l’actualité en webtoon pour faciliter l’accès à l’information, et permettre aux créateurs de s’exprimer et donner vie à l’actualité. Nous proposons donc un média jeune et transparent qui n’a pas peur de prendre position. 
    </p>

    <h2>Nos valeurs</h2>

    <div class="valor-list">

        <div class="valor-item">
            <img src="../../assets/svg/loupe.svg">
            <h3>TRANSPARENCE</h3>
            <p>Nous nous engageons à proposer du contenu fiable et vérifié tout en transmettant de l’information de la manière la plus transparente.</p>
        </div>

        <div class="valor-item">
            <img src="../../assets/svg/puzzle.svg">
            <h3>INCLUSION</h3>
            <p>Nous souhaitons garantir l’inclusion de toutes les minorités, et garantir le respect et les bonnes pratiques en leur égard.</p>
        </div>

        <div class="valor-item">
            <img src="../../assets/svg/light.svg">
            <h3>CRÉATIVITÉ</h3>
            <p>Notre objectif est aussi, et avant tout de mettre en avant la créativité de nos artistes. Et leur permettre de s’exprimer à leur manière.</p>
        </div>

    </div>

    </div>

</template>

<style scoped lang="scss">
@use "../../scss/fluid.scss" as *;

.aboutus {
   display: flex;
    flex-direction: column;
    gap: #{fluid(24px, 64px, 320px, 998px)};
    align-items: center;

    p {
        color: #000;
        text-align: center;
        font-family: var(--font-family-sans-serif);
        font-size: #{fluid(14px, 18px, 320px, 1500px)};
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    h1 {
        color: var(--purple-dark);
        text-align: center;
        font-size: #{fluid(24px, 40px, 320px, 1200px)};
        font-style: normal;
        font-weight: 400;
        line-height: 140%;
        padding: 24px;
    }

    h2 {
        color: var(--purple-dark);
        text-align: center;
        font-size: #{fluid(16px, 30px, 320px, 1200px)};
        font-style: normal;
        font-weight: 400;
        line-height: 125%;
    }

    h3 {
        color: #000;
        text-align: center;
        font-family: Jost;
        font-size: #{fluid(12px, 25px, 320px, 1200px)};
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .familytop {
        width: #{fluid(190px, 620px, 320px, 1200px)};
        height: #{fluid(142px, 470px, 320px, 1200px)};
    }
}

.valor-list {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;

    @media (min-width: 768px) {
        flex-direction: row;
        gap: 64px;
    }

    img {
        width: #{fluid(60px, 250px, 320px, 1500px)};
        height: #{fluid(70px, 350px, 320px, 1500px)};
        object-fit: cover;
    }
}

.valor-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: center;
}

</style>
