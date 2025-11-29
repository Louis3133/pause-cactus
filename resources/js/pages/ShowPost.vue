<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';



const props = defineProps<{
    post: {
        id: number;
        title: string;
        description: string;
        slug: string;
        image?: string | null;
        licence?: { id: number; name: string } | null;
        user?: { id: number; name: string } | null;
    };
    canEdit: boolean;
    isFavorite: boolean
    editUrl: string;
    deleteUrl: string;
}>();

const isFavorited = ref(props.isFavorite);

const addFavorite = async () => {
    try {
        await axios.put(`/posts/${props.post.id}/favorite`);
        isFavorited.value = !isFavorited.value;
    } catch (error) {
        alert('Erreur');
    }
};


const deletePost = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce post ?')) {
        router.delete(props.deleteUrl);
    }
};
</script>

<template>
    <AppLayout>
        <h1>{{ props.post.title }}</h1>
        <p>{{ props.post.description }}</p>
        <p>{{ props.post.licence?.name }}</p>
        <p>{{ props.post.user?.name }}</p>
        <a class="btn btn-primary" v-if="canEdit" :href="editUrl">Modifier</a>
        <button class="btn btn-danger" v-if="canEdit" @click="deletePost">
            Supprimer
        </button>
        <button class="btn btn-success" @click="addFavorite">
            <span v-if="isFavorited">Retirer des favoris</span>
            <span v-else>Mettre en favori !</span>
        </button>
    </AppLayout>
</template>

<style scoped lang="scss"></style>
