<script setup lang="ts">
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { edit } from '@/routes/profile';
import { Form, Head, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';

interface Props {
    status?: string;
}

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit().url,
    },
];

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="info-account">
                <Form
                    v-bind="ProfileController.update.form()"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="form-duo">
                        <div class="label-input">
                            <Label for="name">Nom</Label>
                            <Input
                                id="name"
                                name="name"
                                :default-value="user.name"
                                required
                                autocomplete="name"
                                placeholder="Full name"
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="label-input">
                            <Label for="email">Adresse mail</Label>
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                :default-value="user.email"
                                required
                                autocomplete="username"
                                placeholder="Email address"
                                disabled
                            />
                            <InputError class="mt-2" :message="errors.email" />
                        </div>
                    </div>

                    <div>

                        <div class="save-btn">
                        <Button
                            class="save-button"
                            :disabled="processing"
                            data-test="update-profile-button"
                            >Sauvegarder</Button
                        >
                        </div>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="recentlySuccessful">Saved.</p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>

<style scoped lang="scss">
@use '../../../scss/fluid.scss' as *;

.label-input {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.info-account {
    padding-bottom: #{fluid(32px, 64px, 320px, 1200px)};
    border-bottom: 1px solid var(--purple-dark);
    margin-bottom: #{fluid(32px, 64px, 320px, 1200px)};
}

.save-btn {
    margin-inline: auto;
    display: flex;
    width: 100%;
}

.form-duo {
    display: flex;
    flex-direction: column;
    gap: 14px;
    width: 100%;
    margin-bottom: 24px;

    .label-input {
        width: 100%;
    }

    @media (min-width: 768px) {
        flex-direction: row;
        gap: 32px;
    }
}
</style>
