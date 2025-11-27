<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const form = useForm({
    title: '',
    description: '',
});
</script>

<template>
    <AppLayout>
    <h1>Ajouter un article</h1>
    <form @submit.prevent="form.post('/posts/store')" enctype="multipart/form-data">
        <div >
            <input class="form-control"
                type="text"
                v-model="form.title"
                placeholder="Titre"
            >
            <div v-if="form.errors.title" class="text-red-600 text-sm">
                {{ form.errors.title }}
            </div>
        </div>


        <textarea class="form-control"
            v-model="form.description"
            placeholder="Description"
        ></textarea>

        <div>
            <input class="form-control"
                type="file"

            >
        </div>

        <button class="btn btn-primary" type="submit" :disabled="form.processing">
            Enregistrer
        </button>
    </form>
        </AppLayout>
</template>
