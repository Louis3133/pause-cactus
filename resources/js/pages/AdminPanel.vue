<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import { ref, onMounted } from 'vue';

const props = defineProps<{
    pendingPosts: Array<any>;
    publishedPosts: Array<any>;
    users: Array<any>;
}>();

defineOptions({ layout: AppLayout });

const viewModalRef = ref<HTMLElement | null>(null);
const deleteModalRef = ref<HTMLElement | null>(null);

let viewModalInstance: Modal | null = null;
let deleteModalInstance: Modal | null = null;

const selectedPost = ref<any>(null);

const deleteUrl = ref<string>('');
const deleteMessage = ref<string>('');

onMounted(() => {
    if (viewModalRef.value) viewModalInstance = new Modal(viewModalRef.value);
    if (deleteModalRef.value) deleteModalInstance = new Modal(deleteModalRef.value);
});

const openViewModal = (post: any) => {
    selectedPost.value = post;
    viewModalInstance?.show();
};

const closeViewModal = () => viewModalInstance?.hide();

const confirmDelete = (url: string, type: 'post' | 'user') => {
    deleteUrl.value = url;

    if (type === 'post') {
        deleteMessage.value = "Êtes-vous sûr de vouloir supprimer cet article définitivement ?";
    } else {
        deleteMessage.value = "Êtes-vous sûr de vouloir bannir cet utilisateur ? Cette action est irréversible.";
    }

    deleteModalInstance?.show();
};

const closeDeleteModal = () => deleteModalInstance?.hide();

const executeDelete = () => {
    if (!deleteUrl.value) return;

    router.delete(deleteUrl.value, {
        onSuccess: () => {
            closeDeleteModal();
        }
    });
};

const slugify = (text: string) => {
    return text.toString().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-');
};
</script>

<template>
    <div class="admin-container">

        <h1>Modération des posts (En attente)</h1>
        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th scope="col">Titre</th>
                    <th scope="col">Utilisateur</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="post in props.pendingPosts" :key="post.id">
                    <th scope="row">{{ post.title }}</th>
                    <td>{{ post.user?.name }}</td>
                    <td class="action">
                        <Link :href="route('admin.approve', { post: post.id })" method="put" as="button" class="btn-custom btn-purple btn-small">Valider</Link>
                        <Link :href="route('admin.reject', { post: post.id })" method="put" as="button" class="btn-custom btn-pink btn-small">Refuser</Link>
                        <button class="btn-small btn-purple-secondary btn-custom" @click="openViewModal(post)">Voir</button>
                    </td>
                </tr>
                <tr v-if="props.pendingPosts.length === 0">
                    <td colspan="3" style="text-align: center;">Aucun article en attente.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <hr class="separator" />

        <h1>Articles en ligne</h1>
        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th scope="col">Titre</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="post in props.publishedPosts" :key="post.id">
                    <th scope="row">{{ post.title }}</th>
                    <td>{{ post.user?.name }}</td>
                    <td>{{ new Date(post.created_at).toLocaleDateString() }}</td>
                    <td class="action">
                        <button class="btn-small btn-purple-secondary btn-custom" @click="openViewModal(post)">Voir</button>

                        <button
                            class="btn-custom btn-pink btn-small"
                            @click="confirmDelete(route('admin.deletePost', { post: post.id }), 'post')"
                        >
                            Supprimer
                        </button>
                    </td>
                </tr>
                <tr v-if="props.publishedPosts.length === 0">
                    <td colspan="4" style="text-align: center;">Aucun article publié.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <hr class="separator" />

        <h1>Gestion des Utilisateurs</h1>
        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in props.users" :key="user.id">
                    <th scope="row">
                        <Link class="user-link" :href="route('author.show', { slug: slugify(user.name), id: user.id })">
                            {{ user.name }}
                        </Link>
                    </th>
                    <td>{{ user.email }}</td>
                    <td class="action">
                        <button
                            class="btn-custom btn-pink btn-small"
                            @click="confirmDelete(route('admin.deleteUser', { user: user.id }), 'user')"
                        >
                            Bannir
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="viewPostModal" ref="viewModalRef" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <h3>{{ selectedPost?.title }}</h3>
                    <p>{{ selectedPost?.description }}</p>
                    <img v-if="selectedPost?.image_url" :src="selectedPost.image_url" :alt="selectedPost.title" class="img-fluid">
                    <img v-if="selectedPost?.webtoon_url" :src="selectedPost.webtoon_url" :alt="selectedPost.title" class="img-fluid">

                    <div class="modal-actions">
                        <button type="button" class="btn-custom btn-purple btn-small" @click="closeViewModal">Retour</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteConfirmModal" ref="deleteModalRef" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <h3 class="text-danger">Attention</h3>
                    <p>{{ deleteMessage }}</p>

                    <div class="modal-actions">
                        <button type="button" class="btn-custom btn-purple btn-small" @click="closeDeleteModal">Annuler</button>
                        <button type="button" class="btn-custom btn-pink btn-small" @click="executeDelete">Oui, confirmer</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped lang="scss">

tr {
    border: 1px solid var(--black);
}
td, th {
    padding: 14px; border-right: 1px solid var(--black);
}
td:last-child {
    border-right: unset;
}

h1 {
    color: var(--purple-dark);
    font-size: #{fluid(24px, 40px, 320px, 1500px)};
    font-weight: 400;
    line-height: 140%;
    padding: 24px;
    text-align: center;
    margin-bottom: 24px;
    margin-top: 24px;
}

.action {
    display: flex;
    flex-direction: row;
    gap: 16px;
    justify-content: center;
}

.separator {
    margin: 48px 0;
    border: 1px dashed var(--purple-dark);
    opacity: 0.3;
}

.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    justify-content: flex-end;
}

.modal-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
    padding: 32px;
}

table {
    width: 100%;
}

.table-responsive {
    margin-bottom: 32px;
}
</style>
