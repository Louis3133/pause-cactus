<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { toUrl, urlIsActive } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: editProfile(),
    },
    {
        title: 'Appearance',
        href: editAppearance(),
    },
];

</script>

<template>
    <div>
        <h1>Paramètres</h1>
            <div>
                <section>
                    <slot />
                </section>
            </div>

    </div>
</template>

<style scoped lang="scss">
@use "../../../scss/fluid.scss" as *;

h1 {
    color: var(--purple-dark);
    font-size: #{fluid(24px, 40px, 320px, 1500px)};
    font-style: normal;
    font-weight: 400;
    line-height: 140%;
    padding: 24px;
    text-align: center;
}
</style>
