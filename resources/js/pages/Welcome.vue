<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import posts from '@/routes/posts';

const page = usePage();
const props = withDefaults(
    defineProps<{
        canRegister: boolean;
        posts: Array<any>;
    }>(),
    {
        canRegister: true,
    }
);
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <AppLayout>
        <div v-if="page.props.flash?.success" class="alert alert-success">
            {{ page.props.flash.success }}
        </div>
        <ul>
            <li v-for="post in props.posts" :key="post.id">
                <h2>{{ post.title }}</h2>
                <p>{{ post.description }}</p>
                <p>{{ post.slug }}</p>
                <p>{{ post.id}}</p>
                <p>{{ post.licence?.name}}</p>
                <div v-if="post.image_url"><img v-if="post.image_url" :src="post.image_url" :alt="post.title"></div>
                <a :href="posts.show.url({ slug: post.slug, id: post.id })">Lire la suite</a>
            </li>
        </ul>

    </AppLayout>

</template>

<style lang="scss" scoped>
.collapse {
    visibility: unset;
}
</style>
