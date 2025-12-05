<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import axios from 'axios';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

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
    image_url?: string | null; // URL complète ou chemin pour l'affichage
    webtoon_url?: string | null; // URL complète ou chemin pour l'affichage
}

interface FormData {
    title: string;
    description: string;
    licence_id: string | number;
    image: File | null;
    webtoon: File | null;
    tags: number[];
    categories: number[];
    _method: string;
}

const props = defineProps<{
    post: Post,
    licences: Licence[],
    tags: Array<{ id: number; name: string }>;
    selectedTags: number[];
    categories: Array<{ id: number; name: string }>;
    selectedCategories: number[];
    // Assure-toi que ces URLs arrivent bien du controller
    imageUrl?: string;
    webtoonUrl?: string;
}>();

// --- GESTION IMAGE COVER ---
// On affiche l'image existante au départ
const previewUrlCover = ref<string | null>(props.imageUrl || null);
const fileNameCover = ref<string | null>(props.imageUrl ? 'Image actuelle conservée' : null);
const fileSizeCover = ref<string | null>(null);

const handleFile = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;

    // Si l'utilisateur choisit un fichier, on remplit le form
    form.image = file;

    // Mise à jour de la preview visuelle
    previewUrlCover.value = URL.createObjectURL(file);
    fileNameCover.value = file.name;
    fileSizeCover.value = (file.size / 1024).toFixed(1) + " KB";
};

// --- GESTION WEBTOON ---
const previewUrlWebtoon = ref<string | null>(props.webtoonUrl || null);
const fileNameWebtoon = ref<string | null>(props.webtoonUrl ? 'Planche actuelle conservée' : null);
const fileSizeWebtoon = ref<string | null>(null);

const handleFileWebtoon = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.webtoon = file;

    previewUrlWebtoon.value = URL.createObjectURL(file);
    fileNameWebtoon.value = file.name;
    fileSizeWebtoon.value = (file.size / 1024).toFixed(1) + " KB";
};



const newTagName = ref('');
const availableTags = ref([...props.tags]);

const form = useForm<FormData>({
    title: props.post.title,
    description: props.post.description,
    licence_id: props.post.licence_id ?? '',
    tags: props.selectedTags || [],
    categories: props.selectedCategories || [],
    image: null,   // IMPORTANT : On laisse à null par défaut
    webtoon: null, // IMPORTANT : On laisse à null par défaut
    _method: 'PUT',
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
    // Note: Inertia gère automatiquement la conversion en FormData quand il y a des fichiers
    // Pas besoin d'ajouter manuellement _method ici car il est déjà dans l'état initial du form
    form.post(`/posts/${props.post.id}`, {
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
                    <label>Titre de l'article*</label>
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
                    <label>Description de l'article*</label>
                    <textarea class="form-control"
                              v-model="form.description"
                              placeholder="Description"
                    ></textarea>
                    <div v-if="form.errors.description" class="text-red-600 text-sm">
                        {{ form.errors.description }}
                    </div>
                </div>

                <div class="form-label-input">
                    <label>Licence*</label>
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
                    <label>Couverture</label>
                    <div class="file-input-container">
                        <div class="file-input">
                            <button class="btn-custom btn-small btn-purple" type="button" @click="$refs.fileInputCover.click()">
                                {{ previewUrlCover ? "Changer l'image" : "Ajouter une image" }}
                            </button>

                            <input
                                class="form-control"
                                ref="fileInputCover"
                                type="file"
                                @change="handleFile"
                                accept="image/*"
                                style="display: none;"
                            />
                            <div v-if="form.errors.image" class="text-red-600 text-sm">
                                {{ form.errors.image }}
                            </div>

                            <div v-if="previewUrlCover" class="image-preview">
                                <img :src="previewUrlCover" alt="Preview" class="preview-img">
                            </div>
                        </div>

                        <div class="file-info" v-if="fileNameCover">
                            <p><strong>Statut :</strong> {{ fileNameCover }}</p>
                            <p v-if="fileSizeCover"><strong>Taille (nouveau) :</strong> {{ fileSizeCover }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-label-input">
                    <label>Webtoon</label>
                    <div class="file-input-container">
                        <div class="file-input">
                            <button class="btn-custom btn-small btn-purple" type="button" @click="$refs.fileInputWebtoon.click()">
                                {{ previewUrlWebtoon ? "Changer la planche" : "Ajouter une planche" }}
                            </button>
                            <input
                                class="form-control"
                                ref="fileInputWebtoon"
                                type="file"
                                @change="handleFileWebtoon"
                                accept="image/*"
                                style="display: none;"
                            />

                            <div v-if="form.errors.webtoon" class="text-red-600 text-sm">
                                {{ form.errors.webtoon }}
                            </div>

                            <div v-if="previewUrlWebtoon" class="image-preview">
                                <img :src="previewUrlWebtoon" alt="Preview" class="preview-img">
                            </div>
                        </div>

                        <div class="file-info" v-if="fileNameWebtoon">
                            <p><strong>Statut :</strong> {{ fileNameWebtoon }}</p>
                            <p v-if="fileSizeWebtoon"><strong>Taille (nouveau) :</strong> {{ fileSizeWebtoon }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-label-input">
                    <label>Catégories*</label>
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

                <div style="display: none">
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
                </div>

                <div class="buttons-form">
                    <button class="btn-custom btn-small btn-purple" type="submit" :disabled="form.processing">
                        Enregistrer
                    </button>
                    <Link href="/" class="btn-custom btn-small btn-pink">
                        Annuler
                    </Link>
                </div>

            </form>
        </section>
    </AppLayout>
</template>

<style scoped lang="scss">
/* Styles identiques à CreatePost pour harmonisation */

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
    cursor: pointer; /* Ajouté pour UX */
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

.file-input-container {
    display: flex;
    flex-direction: column;

    @media (min-width: 768px) {
        flex-direction: row;
        gap: 24px;
    }
}

.file-input {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.image-preview {
    margin-top: 10px;
    max-width: 200px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #ddd;

    .preview-img {
        width: 100%;
        height: auto;
        display: block;
    }
}
</style>
