<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout
});

</script>

<template>

    <h1>Mentions légales</h1>

    <div class="content-mentions">

        <p>Le nom et le type de société : le projet «Pause Cactus » est un projet étudiant encadré par Madame Delphine Reyss, le projet a été réalisé par les élèves : Dina Rakotonarivo, Naïka Jan, Louis Le Doussal, Phuc Nguyen, Nathan Vandaele et Léa Lafaye de 3e année du BUT MMI à l’IUT Bordeaux Montaigne</p>

        <div>
            <p><strong>Adresse</strong> : 1 Rue Jacques Ellul, 33800 Bordeaux</p>
        <p><strong>Email</strong> : lea.lafaye@mmibordeaux.com</p>
        <p><strong>Numéro de téléphone</strong> : 05 57 12 20 40</p>
        </div>

        <p>Tous les droits de propriété intellectuelle relatifs aux textes et illustrations créés par l’équipe « Pauses Cactus » lui appartiennent.  Tous les droits de propriété intellectuelle relatifs aux textes et illustrations créés par des contributeurs leur appartiennent.  Les contributeurs acceptent la diffusion de leurs contenus et sont libres de les retirer à tout moment.
        </p>

        <p>Nom, adresse et numéro de téléphone du fournisseur d’hébergement :
        </p>

        <div>
        <p>Oracle Corporation</p>
        <p>500 Oracle Parkway, M/S 5op7</p>
        <p>Redwood Shores, CA 94065</p>
        <p>Attention: Trademark and Copyright Legal Department</p>
        <p>Telephone: +1.650.506.5600</p>
        <p>E-mail: copyright_us@oracle.com</p>
        </div>

        <h2>Règles de communautés
        </h2>

        <p>L’équipe « Pause Cactus » modère les créateurs.</p>

        <p>Conformément au cadre légal français, la liberté d’expression est garantie tant qu’elle ne porte pas atteinte à la dignité d’autrui et ne véhicule pas de stéréotypes.</p>

        <p>L’équipe de « Pause Cactus » se réserve le droit de modérer le contenu à priori et à posteriori, et d’évaluer s’il peut être considéré comme offensant.</p>

        <p>Bien que le site soit alimenté par les contributions des artistes, il est attendu d’eux qu’ils citent leurs sources.</p>

        <p>Le web design et les illustrations réalisés par les artistes leur appartiennent. Cependant, la publication d’un cartoon sur notre média implique un consentement au partage des planches si la source est citée.</p>

        <p>De plus, les propos injurieux ou les sources faciles peuvent entraîner un banissement définitif si nécessaire.</p>

    </div>


</template>

<style scoped lang="scss">
@use "../../scss/fluid.scss" as *;

h1 {
    color: var(--purple-dark);
    font-size: #{fluid(24px, 40px, 320px, 1200px)};
    font-style: normal;
    font-weight: 400;
    line-height: 140%;
    text-align: center;
    padding: 24px;
    margin-bottom: 32px;
}

p {
    font-size: #{fluid(12px, 16px, 320px, 1200px)};
}

h2 {
    margin-block: 12px;
    color: var(--purple-dark);
    font-size: #{fluid(14px, 30px, 320px, 1200px)};
    font-style: normal;
    font-weight: 400;
    line-height: normal;;
}

.content-mentions {
    display: flex;
    flex-direction: column;
    gap: #{fluid(24px, 30px, 320px, 1200px)};
}

</style>
