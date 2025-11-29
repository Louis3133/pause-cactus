<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import axios from 'axios';
import { ref } from 'vue';

interface Licence {
    id: number;
    name: string;
}

interface Post {
    id: number;
    title: string;
    description: string;
    slug: string;
    licence_id?: number | null;
}

interface FormData {
    title: string;
    description: string;
    licence_id: string | number;
    image: File | null;
    tags: number[];
    categories: number[];
}

const props = defineProps<{
    post: Post,
    licences: Licence[],
    tags: Array<{ id: number; name: string }>;
    selectedTags: number[];
    categories: Array<{ id: number; name: string }>;
    selectedCategories: number[];
}>();

const newTagName = ref('');

const availableTags = ref([...props.tags]);

const form = useForm<FormData>({
    title: props.post.title,
    description: props.post.description,
    licence_id: props.post.licence_id ?? '',
    tags: props.selectedTags || [],
    categories: props.selectedCategories || [],
    image: null,
});

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

function submit() {
    form.transform((data) => ({
        ...data,
        _method: 'PUT', // Spoofing de méthode
    })).post(`/posts/${props.post.id}`, {
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <AppLayout>
        <h1>Modifier un article</h1>

        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <input class="form-control"
                       type="text"
                       v-model="form.title"
                       placeholder="Titre"
                >
                <div v-if="form.errors.title" class="text-red-600 text-sm">
                    {{ form.errors.title }}
                </div>
            </div>

            <div class="form-group mb-3">
                <textarea class="form-control"
                          v-model="form.description"
                          placeholder="Description"
                ></textarea>
                <div v-if="form.errors.description" class="text-red-600 text-sm">
                    {{ form.errors.description }}
                </div>
            </div>

            <div class="form-group mb-3">
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
                <div v-if="form.errors.licence_id" class="text-red-600 text-sm">
                    {{ form.errors.licence_id }}
                </div>
            </div>

            <div class="form-group mb-3">
                <select multiple v-model="form.categories" class="form-control">
                    <option v-for="category in props.categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div class="form-group mb-3">
                <select multiple v-model="form.tags" class="form-control">
                    <option v-for="tag in availableTags" :key="tag.id" :value="tag.id">
                        {{ tag.name }}
                    </option>
                </select>
            </div>

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
                <input
                    class="form-control"
                    type="file"
                    @input="form.image = ($event.target as HTMLInputElement).files?.[0] || null"
                    accept="image/*"
                >
            </div>


            <button class="btn btn-primary" type="submit" :disabled="form.processing">
                Modifier
            </button>
        </form>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
