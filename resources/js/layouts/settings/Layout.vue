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
        <Heading title="Paramètres" />
            <div>
                <section>
                    <slot />
                </section>
            </div>

    </div>
</template>
