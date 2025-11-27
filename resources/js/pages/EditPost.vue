<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    post: { id: number; title: string; description: string; slug: string };
}>();

const form = useForm({
    title: props.post.title,
    description: props.post.description,
    image: null,
});

function submit() {
    form.put(`/posts/${props.post.id}`, {
        method: 'put',
        forceFormData: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <AppLayout>
        <h1>Modifier un article</h1>

        <form @submit.prevent="submit">
            <div class="form-group">
                <input class="form-control"
                    type="text"
                    v-model="form.title"
                    placeholder="Titre"
                >
                <div v-if="form.errors.title" class="text-red-600 text-sm">
                    {{ form.errors.title }}
                </div>
            </div>


            <textarea class="form-control"
                v-model="form.description"
                placeholder="Description"
            ></textarea>

                <input class="form-control" type="file" @change="e => form.image = e.target.files[0]" />

            <button class="btn btn-primary" type="submit" :disabled="form.processing">
                Modifier
            </button>
        </form>
        </AppLayout>
</template>
