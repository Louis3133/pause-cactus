<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    slug: string;
    id: number;
    title: string;
    imageUrl?: string | null;
    isFavorited: boolean;
    userName?: string | null;
    categories: Array<{ id: number; name: string }>;
    user?: { id: number; name: string } | null;
}>();

const slugify = (text: string) => {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-');
};
</script>

<template>
    <Link
        :href="route('posts.show', { slug: props.slug, id: props.id })"
        class="thumbnail"
    >
        <div class="shadow"></div>
        <img v-if="props.imageUrl" :src="props.imageUrl" :alt="props.title">
        <p class="title">{{ props.title }}</p>

        <span class="favorite-badge" v-if="props.isFavorited">
             <svg xmlns="http://www.w3.org/2000/svg" width="29" height="41" viewBox="0 0 29 41" fill="none">
                <path d="M0 0.5C0 0.223857 0.223858 0 0.5 0H28.5C28.7761 0 29 0.223858 29 0.5V40.1785C29 40.5531 28.6031 40.7947 28.2703 40.6226L14.7297 33.6188C14.5856 33.5443 14.4144 33.5443 14.2703 33.6188L0.729712 40.6226C0.396906 40.7947 0 40.5531 0 40.1785V0.5Z" fill="#694EC8"/>
                <path d="M13.9579 9.66842C14.1285 9.14327 14.8715 9.14327 15.0421 9.66842L16.9539 15.5524C17.0302 15.7873 17.2491 15.9463 17.496 15.9463H23.6829C24.235 15.9463 24.4646 16.6529 24.0179 16.9774L19.0127 20.614C18.8129 20.7591 18.7293 21.0164 18.8056 21.2513L20.7174 27.1353C20.8881 27.6604 20.287 28.0971 19.8403 27.7726L14.835 24.136C14.6353 23.9909 14.3647 23.9909 14.165 24.136L9.15971 27.7726C8.713 28.0971 8.11194 27.6604 8.28257 27.1353L10.1944 21.2513C10.2707 21.0164 10.1871 20.7591 9.98734 20.614L4.98209 16.9774C4.53538 16.6529 4.76496 15.9463 5.31713 15.9463H11.504C11.7509 15.9463 11.9698 15.7873 12.0461 15.5524L13.9579 9.66842Z" fill="#E5DEFF"/>
             </svg>
        </span>
    </Link>

    <div class="content">
        <p class="author">par
            <Link
                class="user-link"
                v-if="props.user"
                :href="route('author.show', { slug: slugify(props.user.name), id: props.user.id })"
            >
                {{ props.userName }}
            </Link>
            <span v-else>{{ props.userName }}</span>
        </p>

        <div class="categories">
            <div v-for="category in props.categories" :key="category.id">
                <p class="category">{{ category.name }}</p>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
@use "../../scss/fluid.scss" as *;

.categories {
    display: flex;
    flex-direction: row;
    gap: 4px;

    .category {
        display: flex;
        width: 59px;
        height: 17px;
        padding: 14px 8px;
        justify-content: center;
        align-items: center;
        gap: 10px;
        border-radius: 50px;
        background: var(--secondary-green);
        color: var(--tertiary-green);
        font-family: var(--font-family-sans-serif);
        font-size: #{fluid(12px, 18px, 320px, 1200px)};
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
}

.author {
    .user-link {
        color: var(--purple-dark);
        text-decoration-line: underline;
        text-decoration-style: solid;
        text-decoration-skip-ink: auto;
        text-decoration-thickness: auto;
        text-underline-offset: auto;
        text-underline-position: from-font;
    }

    color: #000;
    font-family: var(--font-family-sans-serif);
    font-size: #{fluid(12px, 18px, 320px, 1200px)};
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}



.thumbnail {
    position: relative;
    display: flex;
    width: #{fluid(140px, 250px, 320px, 998px)};
    height: #{fluid(192px, 345px, 320px, 998px)};
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    background: red;
    border-radius: 6px;
    overflow: hidden;

    img {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
    }

    .favorite-badge {
        position: absolute;
        top: 0;
        right: 10px;
        z-index: 8;
    }

    .title {
        position: absolute;
        bottom: 9px;
        left: 9px;
        right: 9px;
        z-index: 3;
        flex-shrink: 0;
        color: white;
        font-size: #{fluid(12px, 20px, 320px, 998px)};
        font-style: normal;
        font-weight: 600;
        line-height: normal;
    }

    .shadow {
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.00) 40.23%, rgba(0, 0, 0, 0.70) 100%);
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 2;
    }
}

.content {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-top: 4px;
    margin-bottom: 40px;
}
</style>
