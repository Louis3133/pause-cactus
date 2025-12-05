<script setup lang="ts">
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Form } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <div

        >
            <div class="delete-description">

                <h2>Fermer votre compte et supprimer vos données</h2>
                <p>
                    Nous sommes tristes de te voir partir, mais nous respectons ce choix. Nous restons toujours à ta disposition en cas de besoin ou si tu veux nous rejoindre à nouveau !
                </p>
                <p>Ce n’est pas un adieu mais un “à bientôt” ?</p>
            <Dialog>
                <DialogTrigger as-child>
                    <Button class="btn-custom btn-small btn-pink" variant="destructive" data-test="delete-user-button"
                        >Fermer mon compte</Button
                    >
                    <p class="text-mini">La suppression des données est immédiate et non réversible, en cliquant sur ce bouton tu consens à perdre toutes les données associées à ton compte et tu ne pourras plus t’y connecter.</p>
                </DialogTrigger>
                <DialogContent>
                    <Form
                        v-bind="ProfileController.destroy.form()"
                        reset-on-success
                        :options="{
                            preserveScroll: true,
                        }"

                        v-slot="{ processing, reset, clearErrors }"
                    >
                        <DialogHeader>
                            <DialogTitle
                                >Are you sure you want to delete your
                                account?</DialogTitle
                            >
                            <DialogDescription>
                                Once your account is deleted, all of its
                                resources and data will also be permanently
                                deleted. Please confirm you would like to
                                permanently delete your account.
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter>
                            <DialogClose as-child>
                                <Button
                                    variant="secondary"
                                    @click="
                                        () => {
                                            clearErrors();
                                            reset();
                                        }
                                    "
                                >
                                    Cancel
                                </Button>
                            </DialogClose>

                            <Button
                                type="submit"
                                variant="destructive"
                                :disabled="processing"
                                data-test="confirm-delete-user-button"
                            >
                                Delete account
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "../../scss/fluid.scss" as *;

h2 {
    color: var(--purple-dark);
    font-size: #{fluid(14px, 30px, 320px, 1500px)};
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.delete-description {
    display: flex;
    flex-direction: column;
    gap: 24px;

    p {
        color: #000;
        font-family: var(--font-family-sans-serif);
        font-size: #{fluid(14px, 16px, 320px, 1500px)};
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .text-mini {
        color: #5D5D5D;
        font-family: var(--font-family-sans-serif);
        font-size: 12px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        text-align: center;
    }
}


</style>
