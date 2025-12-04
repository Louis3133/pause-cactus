<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    posts: Array<any>;
}>();

defineOptions({ layout: AppLayout })

</script>

<template>

    <table>
        <thead>
        <tr>
            <th scope="col">Titre</th>
            <th scope="col">User</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>

        <tr v-for="post in props.posts" :key="post.id">
            <th scope="row">{{ post.title }}</th>
            <td>{{ post.user.name }}</td>
            <td class="action">

                <Link
                    :href="route('admin.approve', { post: post.id })"
                    method="put"
                    as="button"
                    class="btn-custom btn-purple btn-small"
                >
                    Valider
                </Link>

                <Link
                    :href="route('admin.reject', { post: post.id })"
                    method="put"
                    as="button"
                    class="btn-custom btn-pink btn-small"
                >
                    Refuser
                </Link>

            </td>
        </tr>
        </tbody>
    </table>

</template>

<style scoped lang="scss">

.action {
    display: flex;
    flex-direction: row;
    gap: 16px;
}


</style>
