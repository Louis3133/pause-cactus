<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Thumbnail from '@/components/Thumbnail.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';

const props = defineProps<{
    user: {
        id: number;
        name: string;
        avatar?: string;
    };
    posts: Array<{
        id: number;
        title: string;
        description: string;
        slug: string;
        image_url?: string | null;
        is_favorited: boolean;
        user?: { id: number; name: string } | null;
        categories: Array<{ id: number; name: string }>;
        licence?: { id: number; name: string } | null;
    }>;

    canEdit: boolean;
}>();

defineOptions({
    layout: AppLayout
});

const { getInitials } = useInitials();

const showAvatar = computed(
    () => props.user.avatar && props.user.avatar !== '',
);

</script>

<template>
    <Head :title="`Articles de ${props.user.name}`" />

    <div class="user-profile-container">

        <div class="profile-header">
            <div class="header-top">
            <Avatar class="avatar-image">
                <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
                <AvatarFallback>
                    {{ getInitials(user.name) }}
                </AvatarFallback>
            </Avatar>
            <div class="infos">
                <h1>{{ props.user.name }}</h1>
                <p class="stats">{{ props.posts.length }} article(s) publié(s)</p>
            </div>
            </div>

        <button class="btn-medium btn-pink btn-custom" v-if="canEdit">
            Supprimer
        </button>
        </div>

        <div class="separator"></div>

        <h2>Articles</h2>

        <div v-if="props.posts.length > 0" class="posts-grid">
            <article v-for="post in props.posts" :key="post.id">
                <Thumbnail
                    :slug="post.slug"
                    :id="post.id"
                    :title="post.title"
                    :image-url="post.image_url"
                    :is-favorited="post.is_favorited"
                    :user-name="props.user.name"
                    :user="props.user"
                    :categories="post.categories"
                />
            </article>
        </div>

        <div v-else class="empty-state">
            <p>Cet utilisateur n'a pas encore publié de contenu.</p>
        </div>

    </div>
</template>

<style scoped lang="scss">
@use "../../scss/fluid.scss" as *;

.user-profile-container {
    display: flex;
    flex-direction: column;
    gap: 32px;
    padding-top: 20px;
    margin-inline: auto;
    width: 100%;
}

.profile-header {
    display: flex;
    flex-direction: column;
    gap: 16px;

    @media (min-width: 998px) {
        flex-direction: row;
        justify-content: space-between;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: var(--purple-dark, #694EC8);
        color: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 32px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .infos {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
}

.header-top {
    display: flex;
    flex-direction: row;
    gap: #{fluid(8px, 32px, 320px, 998px)};
    align-items: center;
}

.avatar-image {
    width: #{fluid(60px, 107px, 320px, 998px)};
    height: #{fluid(60px, 107px, 320px, 998px)};
    border: 3px solid var(--purple-dark);
}

.separator {
    height: 2px;
    background-color: var(--purple-dark);
    width: 100%;
}

.posts-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    justify-content: flex-start;
}

.empty-state {
    text-align: center;
    padding: 60px;
    color: #6B7280;
    font-style: italic;
    font-family: var(--font-family-sans-serif, sans-serif);
}

h1 {
    color: #0F0F0F;
    font-family: var(--font-family-sans-serif);
    font-size: #{fluid(16px, 40px, 320px, 998px)};
    font-style: normal;
    font-weight: 600;
    line-height: 100%;

    @media (min-width: 768px) {
        font-family: var(--font-family-headings);
    }
}

.stats {
    color: #0F0F0F;
    font-family: var(--font-family-sans-serif);
    font-size: #{fluid(12px, 25px, 320px, 998px)};
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

h2 {
    color: var(--purple-dark);
    font-size: #{fluid(16px, 40px, 320px, 998px)};
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

</style>
