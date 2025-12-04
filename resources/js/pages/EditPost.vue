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

const toggleCategory = (id: number) => {
    const current = form.categories.slice();
    const index = current.indexOf(id);
    if (index >= 0) {
        current.splice(index, 1);
    } else {
        current.push(id);
    }
    form.categories = current;
};

const isCategorySelected = (id: number) => form.categories.includes(id);

const toggleTag = (id: number) => {
    const current = form.tags.slice();
    const index = current.indexOf(id);
    if (index >= 0) {
        current.splice(index, 1);
    } else {
        current.push(id);
    }
    form.tags = current;
};

const isTagSelected = (id: number) => form.tags.includes(id);

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
        <section class="form-section">
        <h1>Modifier un article</h1>
            <div class="separator"></div>

        <form class="form-content" @submit.prevent="submit" enctype="multipart/form-data">


            <div class="form-label-input">
                <label>Titre de l'article</label>
                <input class="form-control"
                       type="text"
                       v-model="form.title"
                       placeholder="Titre"
                >
                <div v-if="form.errors.title" class="text-red-600 text-sm">
                    {{ form.errors.title }}
                </div>
            </div>

            <div class="form-label-input">

                <label>Description de l'article</label>
                <textarea class="form-control"
                          v-model="form.description"
                          placeholder="Description"
                ></textarea>
                <div v-if="form.errors.description" class="text-red-600 text-sm">
                    {{ form.errors.description }}
                </div>
            </div>

            <div class="form-label-input">

                <label>Licence</label>
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

            <div class="form-label-input">
                <label>Catégories</label>
                <div class="select">
                    <div
                        v-for="category in props.categories"
                        :key="category.id"
                        :class="['select__item', { 'select__item--selected': isCategorySelected(category.id) }]"
                        @click="toggleCategory(category.id)"
                    >
                        {{ category.name }}
                    </div>
                </div>

            </div>

            <div class="form-label-input">
                <label>Tags</label>
                <div class="select">
                    <div
                        v-for="tag in availableTags"
                        :key="tag.id"
                        :class="['select__item', { 'select__item--selected': isTagSelected(tag.id) }]"
                        @click="toggleTag(tag.id)"
                    >
                        {{ tag.name }}
                    </div>
                </div>
            </div>

            <div class="form-label-input">
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

            <div class="buttons-form">
                <a class="btn-custom btn-small btn-pink">
                    Annuler
                </a>
                <button class="btn-custom btn-small btn-purple" type="submit" :disabled="form.processing">
                    Enregistrer
                </button>

            </div>

        </form>
        </section>
    </AppLayout>
</template>

<style scoped lang="scss">

.select {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 10px;
}

.select__item {
    display: flex;
    height: 24px;
    padding: 16px 8px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border-radius: 50px;
    background: var(--gray-400);
    transition: background .1s;
    color: var(--gray-300);
    font-family: var(--font-family-sans-serif);
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.select__item--selected {
    background: #009578;
    color: #ffffff;
}


.form-section {
    display: flex;
    flex-direction: column;
    gap: 32px;

    .buttons-form {
        display: flex;
        flex-direction: row;
        gap: 10px;
        justify-content: center;

        .btn-custom {
            width: 140px;
        }
    }

    label {
        color: var(--black);
        font-family: var(--font-family-sans-serif);
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .form-content {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-label-input {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
}


h1 {
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 100%;
    color: var(--black);

    @media (min-width: 768px) {
        font-size: 40px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

}

.separator {
    display: none;
    @media (min-width: 768px) {
        display: block;
        width: 100%;
        height: 2px;
        background-color: var(--purple-dark);
        margin-top: -12px;
    }
}


</style>
