<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Thumbnail from '@/components/Thumbnail.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';

const { getInitials } = useInitials();
const page = usePage();
const props = withDefaults(
    defineProps<{
        canRegister: boolean;
        posts: Array<any>;
        series: Array<any>;
        selectedCategory: number;
        categories: Array<any>;
    }>(),
    {
        canRegister: true,
    },
);

const lastPost = computed(() => {
    if (props.posts && props.posts.length > 0) {
        return props.posts[0];
    }
    return null;
});

const otherPosts = computed(() => {
    if (props.posts && props.posts.length > 0) {
        return props.posts.slice(1);
    }
    return [];
});

const showAvatar = computed(() => {
    return lastPost.value?.user?.avatar;
});
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <AppLayout>
        <a :href="`/posts/${lastPost.slug}`" class="post-one" v-if="lastPost">
            <img class="thumbnail-one" v-if="lastPost.image_url" :src="lastPost.image_url" :alt="lastPost.title">
            <div class="user-one">
                <Avatar class="avatar-image">
                    <AvatarImage
                        v-if="showAvatar"
                        :src="lastPost.user.avatar!"
                        :alt="lastPost.user.name"
                    />
                    <AvatarFallback>
                        {{ getInitials(lastPost.user.name) }}
                    </AvatarFallback>
                </Avatar>
                <p>par {{lastPost.user.name}}</p>
            </div>
            <div class="featured-content">
                <h2>L’actu de la semaine</h2>
            </div>

            <div class="background-purple">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="1448"
                    height="608"
                    viewBox="0 0 1448 608"
                    fill="none"
                >
                    <path
                        d="M2575.02 530.919C1541.59 825.086 262.334 281.337 -113.699 -73.8114L-263.532 763.172L365.321 1195.52C1549.6 877.525 3632.19 229.994 2575.02 530.919Z"
                        fill="#694EC8"
                    />
                </svg>
            </div>
        </a>

        <h1>L’info qui pik pik pik</h1>

        <ul class="categories-home">
            <li>
                <a
                    class="categorie-item"
                    href="/"
                    :class="{ active: !selectedCategory }"
                >
                    Tout
                </a>
            </li>
            <li v-for="categoryitem in categories" :key="categoryitem.id">
                <a
                    class="categorie-item"
                    :href="`/?category=${categoryitem.id}`"
                    :class="{ active: selectedCategory === categoryitem.id }"
                >
                    {{ categoryitem.name }}
                </a>
            </li>
        </ul>

        <div class="posts-grid">
            <article v-for="post in otherPosts" :key="post.id" class="posts-container">
                <Thumbnail
                    :slug="post.slug"
                    :id="post.id"
                    :title="post.title"
                    :image-url="post.image_url"
                    :is-favorited="post.is_favorited"
                    :userName="post.user?.name"
                    :categories="post.categories"
                    :user="post.user"
                />
            </article>
        </div>

        <ul style="display: none">
            <li v-for="serie in props.series" :key="serie.id">
                <h2>{{ serie.title }}</h2>
                <p>{{ serie.description }}</p>
                <a :href="`/series/${serie.slug}-${serie.id}`">Voir la série</a>
            </li>
        </ul>
    </AppLayout>
</template>

<style lang="scss" scoped>
@use '../../scss/fluid.scss' as *;

.categories-home {
    display: flex;
    flex-direction: row;
    gap: #{fluid(8px, 12px, 320px, 1200px)};
    padding: unset;
    font-family: var(--font-family-sans-serif);
    font-size: #{fluid(12px, 20px, 320px, 1200px)};
    font-style: normal;
    font-weight: 500;
    line-height: normal;

    @media (min-width: 768px) {
        margin-bottom: 14px;
    }

    a {
        text-decoration: none;
        color: var(--black);
    }

    a.active {
        color: var(--tertiary-purple);
        text-decoration-line: underline;
        text-decoration-style: solid;
        text-decoration-skip-ink: auto;
        text-decoration-thickness: auto;
        text-underline-offset: auto;
        text-underline-position: from-font;
    }
}

.post-one {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    background: var(--secondary-purple);
    height: #{fluid(155px, 600px, 320px, 1200px)};
    width: 100%;
    border-radius: #{fluid(48px, 54px, 320px, 1200px)};
    margin-bottom: #{fluid(40px, 64px, 320px, 1200px)};
    margin-top: 32px;
    position: relative;
    overflow: hidden;

    .featured-content {
        position: absolute;
        bottom: #{fluid(25px, 60px, 320px, 1200px)};
        left: #{fluid(25px, 60px, 320px, 1200px)};
        z-index: 3;
        display: flex;
        flex-direction: column;
        gap: 10px;

        h2 {
            color: var(--white);
            width: #{fluid(113px, 670px, 320px, 1200px)};
            font-size: #{fluid(16px, 70px, 320px, 1500px)};
            font-style: normal;
            font-weight: 400;
            line-height: 125%;
            margin: 0;
            position: relative;
            bottom: auto;
            left: auto;
        }
    }

    .background-purple {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;

        svg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    .avatar-image {
        width: #{fluid(40px, 150px, 320px, 1200px)};
        height: #{fluid(40px, 150px, 320px, 1200px)};
    }

    .user-one {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: #{fluid(7px, 50px, 320px, 1200px)};
        color: var(--purple-dark);
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        position: absolute;
        z-index: 4;
        top: #{fluid(25px, 80px, 320px, 1200px)};
        left: #{fluid(20px, 160px, 320px, 1200px)};

        p {
            font-size: #{fluid(12px, 40px, 320px, 1500px)};
            max-width: #{fluid(100px, 400px, 320px, 1500px)};
        }
    }

    .thumbnail-one {
        width: #{fluid(90px, 355px, 320px, 1200px)};
        height: #{fluid(120px, 475px, 320px, 1200px)};
        transform: rotate(10deg);
        border-radius: 13.917px;
        position: absolute;
        right: #{fluid(24px, 120px, 320px, 1500px)};
        display: flex;
        z-index: 4;
        bottom: 0;
        top: 0;
        margin-top: auto;
        margin-bottom: auto;
        object-fit: cover;
    }
}

h1 {
    color: var(--purple-dark);
    font-family: "Rammetto One";
    font-size: #{fluid(16px, 30px, 320px, 1500px)};
    font-style: normal;
    font-weight: 400;
    line-height: normal;

    @media (min-width: 768px) {
        margin-bottom: 24px;
    }
}

.posts-grid {
    display: grid;
    gap: 20px;

    grid-template-columns: repeat(2, 1fr);

    @media (min-width: 768px) {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    }
}


.posts-container {
    width: 100%;
}

</style>
