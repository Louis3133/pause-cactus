<script setup lang="ts">
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps<{
    licences: Array<{ id: number; name: string }>
    tags: Array<{ id: number; name: string }>
    categories: Array<{ id: number; name: string }>
}>();

const newTagName = ref('');

const form = useForm({
    title: '',
    description: '',
    licence_id: props.licences.length ? props.licences[0].id : null,
    tags: [] as number[],
    categories: [] as number[],
    image: null as File | null,
});

const availableTags = ref([...props.tags]);

const createTag = async () => {
    if (!newTagName.value.trim()) {
        alert('Veuillez entrer un nom de tag');
        return;
    }

    try {
        const response = await axios.post('/tags/store', {
            name: newTagName.value
        });

        const newTag = response.data;

        availableTags.value.push(newTag);
        form.tags.push(newTag.id);
        newTagName.value = '';

    } catch (error) {
        alert('Erreur lors de la création du tag');
        console.error(error);
    }
};
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

            <select v-model="form.licence_id" class="form-control">
                <option value="">Choisir une licence</option>
                <option
                    v-for="licence in props.licences"
                    :key="licence.id"
                    :value="licence.id"
                >
                    {{ licence.name }}
                </option>
            </select>

            <div  v-if="form.errors.licence_id" class="text-red-600 text-sm">
                {{ form.errors.licence_id }}
            </div>

            <select class="form-control" multiple v-model="form.categories">
                <option v-for="category in props.categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                </option>
            </select>

            <select class="form-control" multiple v-model="form.tags">
                <option v-for="tag in availableTags" :key="tag.id" :value="tag.id">
                    {{ tag.name }}
                </option>
            </select>

            <div class="form-group mb-3">
                <label>Nouveau tag :</label>
                <input
                    type="text"
                    v-model="newTagName"
                    placeholder="Créer un nouveau tag"
                    class="form-control"
                >
                <button type="button" @click="createTag" class="btn btn-secondary">
                    Ajouter ce tag
                </button>
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
