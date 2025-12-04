<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { shareUrl } from 'share-url';
import Thumbnail from '@/components/Thumbnail.vue';
import { Modal } from 'bootstrap';
import { ref, onMounted } from 'vue';

const modalRef = ref<HTMLElement | null>(null);
const successModalRef = ref<HTMLElement | null>(null);

let modalInstance: Modal | null = null;
let successModalInstance: Modal | null = null;

onMounted(() => {
    if (modalRef.value) modalInstance = new Modal(modalRef.value);
    if (successModalRef.value) successModalInstance = new Modal(successModalRef.value);
});

const openModal = () => modalInstance?.show();
const closeModal = () => modalInstance?.hide();

const closeSuccessModal = () => {
    successModalInstance?.hide();
};

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

    postsRelated: Array<{
        slug: string;
        id: number;
        title: string;
        image_url?: string | null;
        is_favorited: boolean;
        user?: { id: number; name: string } | null;
        categories: Array<{ id: number; name: string }>;
    }>;

    canEdit: boolean;
    imageUrl?: string | null;
    isFavorite: boolean;
    editUrl: string;
    deleteUrl: string;
}>();
const sharePost = async () => {
    if (navigator.share) {
        try {
            await navigator.share({
                title: props.title,
                url: window.location.href
            });
        } catch (error) {
            console.log('Partage annulé');
        }
    } else {
        const url = shareUrl('twitter', {
            url: window.location.href,
            text: props.title
        });
        window.open(url, '_blank');
    }
};

const isFavorited = ref(props.isFavorite);

const addFavorite = async () => {
    try {
        await axios.put(`/posts/${props.post.id}/favorite`);
        isFavorited.value = !isFavorited.value;
    } catch (error) {
        alert('Erreur');
    }
};

const performDelete = () => {
    router.delete(props.deleteUrl, {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();

            setTimeout(() => {
                successModalInstance?.show();
            }, 200);
        },
        onError: () => {
            alert("Une erreur est survenue lors de la suppression.");
            closeModal();
        }
    });
};
</script>

<template>
    <AppLayout>

        <div class="show-content">
        <div class="post-elements">
            <img class="thumbnail thumbnail-mobile" v-if="props.imageUrl" :src="props.imageUrl" :alt="props.post.title">
            <div>
                <h1>{{ props.post.title }}</h1>
                <p>{{ props.post.user?.name }}</p>
                <div class="separator"></div>
            </div>
            <div class="informations">
            <img class="thumbnail thumbnail-desktop" v-if="props.imageUrl" :src="props.imageUrl" :alt="props.post.title">
                <div class="info-text">
                    <p class="description">{{ props.post.description }}</p>
                    <p>{{ props.post.licence?.name }}</p>
                </div>
            </div>
        </div>

            <div class="buttons-show">
                <button class="btn-medium btn-pink btn-custom" v-if="canEdit" @click="openModal">
                    Supprimer
                </button>
                <a class="btn-medium btn-purple btn-custom" v-if="canEdit" :href="editUrl">Modifier</a>
            </div>

            <div class="modal fade" id="deleteConfirmModal" ref="modalRef" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <h3>Souhaitez-vous vraiment supprimer cet article ?</h3>
                        <p>L’article sera supprimé définitivement.</p>
                        <img class="trash" src="../../assets/svg/Poubelle.svg" alt="Supprimer">

                        <button type="button" class="btn-custom btn-pink btn-small" @click="performDelete">
                            Oui, supprimer
                        </button>
                        <button type="button" class="btn-custom btn-purple btn-small" @click="closeModal">
                            Non, annuler
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteSuccessModal" ref="successModalRef" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <h3 class="second-moadl-h3">Votre article a été supprimé !</h3>
                        <img class="pouce" src="../../assets/svg/Pouce.svg">

                        <button type="button" class="btn-custom btn-purple btn-small" @click="closeSuccessModal">
                            Retour
                        </button>
                    </div>
                </div>
            </div>




        <div class="post-buttons">

            <div class="btn-item">
                <button class="btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path d="M16 22.6667C16.3778 22.6667 16.6944 22.5389 16.95 22.2833C17.2056 22.0278 17.3333 21.7111 17.3333 21.3333C17.3333 20.9556 17.2056 20.6389 16.95 20.3833C16.6944 20.1278 16.3778 20 16 20C15.6222 20 15.3056 20.1278 15.05 20.3833C14.7944 20.6389 14.6667 20.9556 14.6667 21.3333C14.6667 21.7111 14.7944 22.0278 15.05 22.2833C15.3056 22.5389 15.6222 22.6667 16 22.6667ZM14.6667 17.3333H17.3333V9.33333H14.6667V17.3333ZM11 28L4 21V11L11 4H21L28 11V21L21 28H11ZM12.1333 25.3333H19.8667L25.3333 19.8667V12.1333L19.8667 6.66667H12.1333L6.66667 12.1333V19.8667L12.1333 25.3333Z" fill="#694EC8"/>
                    </svg>
                </button>
                <p>Signaler</p>
            </div>

            <div class="btn-item">
                <button class="btn-icon" @click="addFavorite">
                    <span v-if="isFavorited"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20" fill="none">
                        <path d="M2 20C1.45 20 0.979167 19.8042 0.5875 19.4125C0.195833 19.0208 0 18.55 0 18V2C0 1.45 0.195833 0.979167 0.5875 0.5875C0.979167 0.195833 1.45 0 2 0H14C14.55 0 15.0208 0.195833 15.4125 0.5875C15.8042 0.979167 16 1.45 16 2V18C16 18.55 15.8042 19.0208 15.4125 19.4125C15.0208 19.8042 14.55 20 14 20H2ZM2 18H14V2H12V9L9.5 7.5L7 9V2H2V18Z" fill="#694EC8"/>
                    </svg></span>
                    <span v-else><svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20" fill="none">
                        <path d="M2 20C1.45 20 0.979167 19.8042 0.5875 19.4125C0.195833 19.0208 0 18.55 0 18V2C0 1.45 0.195833 0.979167 0.5875 0.5875C0.979167 0.195833 1.45 0 2 0H14C14.55 0 15.0208 0.195833 15.4125 0.5875C15.8042 0.979167 16 1.45 16 2V18C16 18.55 15.8042 19.0208 15.4125 19.4125C15.0208 19.8042 14.55 20 14 20H2ZM2 18H14V2H12V9L9.5 7.5L7 9V2H2V18Z" fill="red"/>
                    </svg></span>
                </button>
                <p>Favoris</p>
            </div>


            <div class="btn-item">
                <button @click="sharePost" class="share-button btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20" fill="none">
                        <path d="M15 20C14.1667 20 13.4583 19.7083 12.875 19.125C12.2917 18.5417 12 17.8333 12 17C12 16.9 12.025 16.6667 12.075 16.3L5.05 12.2C4.78333 12.45 4.475 12.6458 4.125 12.7875C3.775 12.9292 3.4 13 3 13C2.16667 13 1.45833 12.7083 0.875 12.125C0.291667 11.5417 0 10.8333 0 10C0 9.16667 0.291667 8.45833 0.875 7.875C1.45833 7.29167 2.16667 7 3 7C3.4 7 3.775 7.07083 4.125 7.2125C4.475 7.35417 4.78333 7.55 5.05 7.8L12.075 3.7C12.0417 3.58333 12.0208 3.47083 12.0125 3.3625C12.0042 3.25417 12 3.13333 12 3C12 2.16667 12.2917 1.45833 12.875 0.875C13.4583 0.291667 14.1667 0 15 0C15.8333 0 16.5417 0.291667 17.125 0.875C17.7083 1.45833 18 2.16667 18 3C18 3.83333 17.7083 4.54167 17.125 5.125C16.5417 5.70833 15.8333 6 15 6C14.6 6 14.225 5.92917 13.875 5.7875C13.525 5.64583 13.2167 5.45 12.95 5.2L5.925 9.3C5.95833 9.41667 5.97917 9.52917 5.9875 9.6375C5.99583 9.74583 6 9.86667 6 10C6 10.1333 5.99583 10.2542 5.9875 10.3625C5.97917 10.4708 5.95833 10.5833 5.925 10.7L12.95 14.8C13.2167 14.55 13.525 14.3542 13.875 14.2125C14.225 14.0708 14.6 14 15 14C15.8333 14 16.5417 14.2917 17.125 14.875C17.7083 15.4583 18 16.1667 18 17C18 17.8333 17.7083 18.5417 17.125 19.125C16.5417 19.7083 15.8333 20 15 20ZM15 18C15.2833 18 15.5208 17.9042 15.7125 17.7125C15.9042 17.5208 16 17.2833 16 17C16 16.7167 15.9042 16.4792 15.7125 16.2875C15.5208 16.0958 15.2833 16 15 16C14.7167 16 14.4792 16.0958 14.2875 16.2875C14.0958 16.4792 14 16.7167 14 17C14 17.2833 14.0958 17.5208 14.2875 17.7125C14.4792 17.9042 14.7167 18 15 18ZM3 11C3.28333 11 3.52083 10.9042 3.7125 10.7125C3.90417 10.5208 4 10.2833 4 10C4 9.71667 3.90417 9.47917 3.7125 9.2875C3.52083 9.09583 3.28333 9 3 9C2.71667 9 2.47917 9.09583 2.2875 9.2875C2.09583 9.47917 2 9.71667 2 10C2 10.2833 2.09583 10.5208 2.2875 10.7125C2.47917 10.9042 2.71667 11 3 11ZM15 4C15.2833 4 15.5208 3.90417 15.7125 3.7125C15.9042 3.52083 16 3.28333 16 3C16 2.71667 15.9042 2.47917 15.7125 2.2875C15.5208 2.09583 15.2833 2 15 2C14.7167 2 14.4792 2.09583 14.2875 2.2875C14.0958 2.47917 14 2.71667 14 3C14 3.28333 14.0958 3.52083 14.2875 3.7125C14.4792 3.90417 14.7167 4 15 4Z" fill="#694EC8"/>
                    </svg>
                </button>
                <p>Partager</p>
            </div>
        </div>

        <h2>Actus similaires</h2>

            <div class="related-static-container">
                <article v-for="postRelated in props.postsRelated" :key="postRelated.id" class="actus-related">
                    <Thumbnail
                        :slug="postRelated.slug"
                        :id="postRelated.id"
                        :title="postRelated.title"
                        :image-url="postRelated.image_url"
                        :is-favorited="postRelated.is_favorited"
                        :userName="postRelated.user?.name"
                        :categories="postRelated.categories"
                    />
                </article>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
@use "../../scss/fluid.scss" as *;

.show-content {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

h2 {
    color: var(--purple-dark);
    font-size: #{fluid(16px, 34px, 320px, 998px)};
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.separator {
    width: 100%;
    height: 2px;
    background: var(--purple-dark);
    margin-block: 24px;
    display: none;

    @media (min-width: 768px) {
        display: block;
    }
}

.description {
    font-family: var(--font-family-sans-serif);
    font-size: #{fluid(14px, 20px, 320px, 998px)};
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

h1 {
    color: var(--black);
    font-size:  #{fluid(14px, 40px, 320px, 998px)};
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.post-elements {
    display: flex;
    flex-direction: column;
    gap: 16px;
 }

.buttons-show {
    display: flex;
    flex-direction: row;
    gap: 10px;
    margin-inline: auto;
}

.informations {
    display: flex;
    flex-direction: column;
    gap: 24px;

    @media (min-width: 768px) {
        flex-direction: row;
        gap: 32px;
    }

    .info-text {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
}

.post-buttons {
    display: flex;
    flex-direction: row;
    gap: 30px;
    margin-inline: auto;

    .btn-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
        color: var(--purple-dark);
        font-family: var(--font-family-sans-serif);
        font-size: 12px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
}

.thumbnail {
    width: 130px;
    height: 170px;
    border-radius: 6px;
    object-fit: cover;
    margin-inline: auto;

    @media (min-width: 768px) {
        width: #{fluid(250px, 450px, 768px, 1200px)};
        height: #{fluid(250px, 450px, 768px, 1200px)};
        margin-inline: unset;
        aspect-ratio: 1/1;
    }
}

.thumbnail-mobile {
    @media (min-width: 768px) {
        display: none;
    }
}

.thumbnail-desktop {
    display: none;
    @media (min-width: 768px) {
        display: block;
    }
}

.modal-content {
    border-radius: 20px;
    display: flex;
    width: 320px;
    padding: 32px 16px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 20px;
    background: rgba(255, 255, 255, 0.60);
    backdrop-filter: blur(15px);
    margin-inline: auto;
    align-items: center;

    @media (min-width: 768px) {
        width: 500px;
    }

    .trash{
        margin-left: 20px;
        margin-bottom: 70px;
    }

    .pouce {
        margin-bottom: 70px;
    }

    h3 {
        color: var(--black);
        text-align: center;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
}

.related-static-container {
    display: flex;
    gap: 16px;
    width: 100%;
    overflow: hidden;
}

.actus-related {
    flex: 0 0 auto;

    width: calc(50% - 8px);

    @media (min-width: 768px) {
        width: 250px;
    }

}

.second-moadl-h3 {
    margin-top: 50px;
}
</style>
