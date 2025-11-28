<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    licences: Array<{ id: number; name: string }>
}>();

const form = useForm({
    title: '',
    description: '',
    licence_id: props.licences.length ? props.licences[0].id : null, // ← Changé
    image: null as File | null,
});
</script>

<template>
    <AppLayout>
    <h1>Ajouter un article</h1>
        <form @submit.prevent="form.post('/posts/store', { forceFormData: true })">
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
        <div v-if="form.errors.description" class="text-red-600 text-sm">
            {{ form.errors.description }}
        </div>

            <select v-model="form.licence_id" class="form-control"> <!-- ← Changé -->
                <option value="">Choisir une licence</option>
                <option
                    v-for="licence in props.licences"
                    :key="licence.id"
                    :value="licence.id"
                >
                    {{ licence.name }}
                </option>
            </select>

            <div v-if="form.errors.licence_id" class="text-red-600 text-sm"> <!-- ← Et ici aussi -->
                {{ form.errors.licence_id }}
            </div>

        <div>
            <input class="form-control"
                   type="file"
                   @input="form.image = ($event.target as HTMLInputElement).files?.[0] || null"
                   accept="image/*"
            >
            <div v-if="form.errors.image" class="text-red-600 text-sm">
                {{ form.errors.image }}
            </div>
        </div>

            <div v-if="Object.keys(form.errors).length" class="alert alert-danger">
                <pre>{{ form.errors }}</pre>
            </div>

        <button class="btn btn-primary" type="submit" :disabled="form.processing">
            Enregistrer
        </button>
    </form>
        </AppLayout>
</template>
