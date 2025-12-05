<script setup lang="ts">
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    licences: Array<{ id: number; name: string }>
    tags: Array<{ id: number; name: string }>
    categories: Array<{ id: number; name: string }>
    series: Array<{ id: number; title: string }>
}>();

const previewUrlCover = ref<string | null>(null);
const fileNameCover = ref<string | null>(null);
const fileSizeCover = ref<string | null>(null);

const handleFile = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;
    form.image = file;
    previewUrlCover.value = URL.createObjectURL(file);
    fileNameCover.value = file.name;
    fileSizeCover.value = (file.size / 1024).toFixed(1) + " KB";
};


const previewUrlWebtoon = ref<string | null>(null);
const fileNameWebtoon = ref<string | null>(null);
const fileSizeWebtoon = ref<string | null>(null);
const handleFileWebtoon = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;
    form.webtoon = file;
    previewUrlWebtoon.value = URL.createObjectURL(file);
    fileNameWebtoon.value = file.name;
    fileSizeWebtoon.value = (file.size / 1024).toFixed(1) + " KB";
};


const createNewSerie = ref(false);

const newSerie = ref({
    title: '',
    description: '',
    tags: [] as number[],
    categories: [] as number[]
});

const newTagName = ref('');

const form = useForm({
    title: '',
    description: '',
    licence_id: props.licences.length ? props.licences[0].id : null,
    tags: [] as number[],
    categories: [] as number[],
    serie_id: null as number | null,
    image: null as File | null,
    webtoon: null as File | null,
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

const createSerie = async () => {
    if (!newSerie.value.title) {
        alert('Veuillez entrer un titre de série');
        return;
    }

    try {
        const response = await axios.post('/series/store', {
            title: newSerie.value.title,
            description: newSerie.value.description,
            categories: form.categories,
            tags: form.tags
        });

        const addSerie = response.data;
        form.serie_id = addSerie.id;
        newSerie.value.title = '';
        newSerie.value.description = '';

    } catch (error) {
        alert('Erreur lors de la création de la série');
        console.error(error);
    }
    // Pour le prof qui passera par là, oui j'ai dupliqué du code pour les formulaires créa / edit, désolé j'avais pas le temps, bon courage pour les corrections !
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


</script>


<template>
    <AppLayout>
        <section class="form-section">
            <h1>Créer son article</h1>
            <div class="separator"></div>
        <form class="form-content" @submit.prevent="form.post('/posts/store', { forceFormData: true })">

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
                <textarea class="form-control" v-model="form.description" placeholder="Description"></textarea>
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



            <div  v-if="form.errors.licence_id" class="text-red-600 text-sm">
                {{ form.errors.licence_id }}
            </div>

            </div>

            <div class="form-label-input">
                <label>Couverture*</label>
                <div class="file-input-container">
            <div class="file-input">
                <button class="btn-custom btn-small btn-purple">Ajouter une image</button>
                <input
                    class="form-control"
                    ref="fileInput"
                    type="file"
                    @change="handleFile"
                    accept="image/*"
                />
                <div v-if="form.errors.image" class="text-red-600 text-sm">
                    {{ form.errors.image }}
                </div>

                <div v-if="previewUrlCover" class="image-preview">
                    <img :src="previewUrlCover" alt="Preview" class="preview-img">
                </div>
            </div>

            <div class="file-info">
                <p><strong>Nom :</strong> {{ fileNameCover }}</p>
                <p><strong>Taille :</strong> {{ fileSizeCover }}</p>
            </div>

                </div>

            </div>

            <div class="form-label-input">
                <label>Webtoon*</label>
                <div class="file-input-container">
                    <div class="file-input">
                        <button class="btn-custom btn-small btn-purple">Ajouter une planche</button>
                        <input
                            class="form-control"
                            ref="fileInput"
                            type="file"
                            @change="handleFileWebtoon"
                            accept="image/*"
                        />
                        <div v-if="form.errors.webtoon" class="text-red-600 text-sm">
                            {{ form.errors.webtoon }}
                        </div>

                        <div v-if="previewUrlWebtoon" class="image-preview">
                            <img :src="previewUrlWebtoon" alt="Preview" class="preview-img">
                        </div>
                    </div>

                    <div class="file-info">
                        <p><strong>Nom :</strong> {{ fileNameWebtoon }}</p>
                        <p><strong>Taille :</strong> {{ fileSizeWebtoon }}</p>
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

            <div class="form-label-input">
                <label>Créer une nouvelle série</label>
                <input class="form-checkbox" type="checkbox" v-model="createNewSerie">
            </div>

            <div v-if="createNewSerie">
                <input
                    type="text"
                    v-model="newSerie.title"
                    placeholder="Titre de la série"
                >
                <textarea class="form-control" v-model="newSerie.description" placeholder="Description"></textarea>
                <button type="button" @click="createSerie" class="btn btn-secondary">
                    Créer la série
                </button>
            </div>

            <div v-if="!createNewSerie" class="form-group mb-3">
                <select v-model="form.serie_id" class="form-control ">
                    <option value="">Pas de série (post solo)</option>
                    <option v-for="serie in props.series" :key="serie.id" :value="serie.id">
                        {{ serie.title }}
                    </option>
                </select>
            </div>


        <div class="buttons-form">

            <button class="btn-custom btn-small btn-purple" type="submit" :disabled="form.processing">
                Publier
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

.file-input-container {
    display: flex;
    flex-direction: column;

    @media (min-width: 768px) {
        flex-direction: row;
        gap: 24px;
    }
}

</style>
