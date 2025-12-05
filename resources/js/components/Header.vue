<script setup lang="ts">
import { dashboard, login, logout } from '@/routes';
import { edit } from '@/routes/profile';
import { create } from '@/routes/posts';
import { Link } from '@inertiajs/vue3';
import UserInfo from '@/components/UserInfo.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = usePage().props.auth?.user;

const slugify = (text: string) => {
    if (!text) return '';
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
    <header>

        <Link href="/">
            <img class="logo" src="../../assets/svg/logo.svg">
        </Link>

        <div class="header-nav">

            <button class="burger-menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                <img src="../../assets/svg/burger.svg">
            </button>

            <div class="offcanvas-end offcanvas-md" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
                <div class="offcanvas-header">
                    <a href="/"><img class="logo" src="../../assets/svg/logo.svg"></a>
                    <button type="button"  data-bs-dismiss="offcanvas" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 52 52" fill="none">
                        <line x1="15.1213" y1="15.0415" x2="37.0416" y2="36.9618" stroke="white" stroke-width="3" stroke-linecap="round"/>
                        <line x1="15" y1="36.9202" x2="36.9203" y2="14.9999" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    </svg></button>
                </div>

                <nav class="top-nav links">

                    <Link class="button-mobile" href="">
                        Accueil
                    </Link>

                    <Link href="/about">
                        À propos
                    </Link>

                    <Link href="/concept">
                        Notre concept
                    </Link>

                    <Link class="button-mobile" :href="edit()" prefetch as="button">
                        Paramètres
                    </Link>

                    <Link class="button-mobile" :href="logout()" prefetch as="button">
                        Déconnexion
                    </Link>
                </nav>

                <nav class="top-nav actions">
                    <Link
                        v-if="$page.props.auth?.user"
                        :href="dashboard()"
                    >
                        <div v-if="$page.props.auth?.user" class="button-mobile btn-medium btn-purple-secondary btn-custom auth avatar-btn"><UserInfo :user="user" /></div>
                    </Link>

                    <template v-else>
                        <Link
                            :href="login()"
                            class="btn-medium btn-purple-secondary btn-custom auth"
                        >
                            Connexion/ Inscription
                        </Link>
                    </template>

                    <div class="dropdown auth-dropdown" v-if="user">
                        <button class="btn-medium btn-purple-secondary auth avatar-btn btn-custom dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <UserInfo :user="user" />
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <Link class="dropdown-item-auth" v-if="$page.props.auth?.user" :href="route('author.show', { slug: slugify(user.name), id: user.id })" prefetch as="button">
                                    Mon profil
                                </Link>
                            </li>
                            <li>
                                <Link class="dropdown-item-auth" :href="edit()" prefetch as="button">
                                    Paramètres
                                </Link>
                            </li>
                            <li>
                                <Link class="dropdown-item-auth" :href="logout()" prefetch as="button">
                                    Déconnexion
                                </Link>
                            </li>
                        </ul>
                    </div>



                    <div class="btn-medium btn-purple-secondary btn-custom"> <Link :href="create()" prefetch as="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M6 8H0V6H6V0H8V6H14V8H8V14H6V8Z" fill="currentColor"/>
                        </svg>
                        Publier
                    </Link></div>

                </nav>

            </div>
        </div>


    </header>
</template>

<style scoped lang="scss">
.dropdown.auth-dropdown {
    display: none;

    .dropdown-toggle::after {
        display: none;
    }

    @media (min-width: 768px) {
        display: block;
    }
    .dropdown-menu.show {
        padding-block: unset;
        overflow: hidden;
        background: transparent;
        border: unset;
        border-radius: 20px !important;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);
    }

    .dropdown-item-auth {
        color: var(--purple-dark);
        font-family: var(--font-family-sans-serif);
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        text-align: center;
        width: 100%;
        padding-block: 8px;
        background-color: var(--secondary-purple);
        overflow: hidden;

        &:hover {
            background-color: var(--tertiary-purple);
            color: var(--secondary-purple);
        }
    }

    .dropdown-menu:last-child{
        border-radius: 0 0 20px 20px;
    }

    .dropdown-menu:first-child{
        border-radius: 20px 20px 0 0;
        overflow: hidden;
    }
}

.btn-custom button {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 18px;

    svg {
        transition: .3s ease;
        color: var(--purple-dark);
    }

}

.btn-custom:hover {
    svg {
        color: var(--tertiary-purple);
    }
}

.button-mobile {
    display: flex;
    margin-left: auto;
    color: var(--purple-dark);
    text-align: right;
    font-family: var(--font-family-sans-serif);
    font-size: 18px;
    font-style: normal;
    font-weight: 600;
    line-height: 100%;

    @media (min-width: 768px) {
        display: none;
    }
}

.auth.btn-purple-secondary {
    @media (max-width: 768px) {
        background-color: var(--purple-dark);
        color: var(--purple-dark);
        font-family: var(--font-family-sans-serif);
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        width: 268px;
        margin-inline: auto;
    }
}

.top-nav.links {
    @media (max-width: 768px) {
        margin-top: 62px;
        display: flex;
        flex-direction: column;
        gap: 36px;
        padding-inline: 12px;
    }
}

header {
    background: var(--purple-dark);
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 16px 12px 78px 8px;
    gap: 32px;

    @media (min-width: 768px) {
        justify-content: unset;
        padding: 24px 24px 78px 24px;
    }

    .offcanvas-md {
        display: flex;
        flex-direction: column;
        width: 100%;

        @media (min-width: 768px) {
            flex-direction: row;
            justify-content: space-between;
        }
    }

    .logo {
        width: 98px;
        height: 48px;

        @media (min-width: 768px) {
            width: 140px;
            height: 70px;
        }
    }

    .actions {
        margin-bottom: 32px;
        margin-top: auto;

        @media (min-width: 768px) {
            margin-left: auto;
            margin-bottom: unset;
            margin-top: unset;
        }

        .btn-custom {
            @media (max-width: 768px) {
                width: 268px;
                margin-inline: auto;
            }
        }
    }

    .header-nav {
        display: flex;
        flex-direction: row;
        justify-content: space-between;

        @media (min-width: 768px) {
            width: 100%;
        }
    }

    .burger-menu {
        display: block;
        @media (min-width: 768px) {
            display: none;
        }
    }
}

.offcanvas-header {
    background: var(--purple-dark);
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    gap: 32px;

    @media (min-width: 768px) {
        display: none;
    }
}

.top-nav {
    display: flex;
    flex-direction: column;
    gap: 16px;
    justify-content: right;

    @media (min-width: 768px) {
        flex-direction: row-reverse;
        align-items: center;
        justify-content: unset;

    }

    .btn-custom {
        color: var(--purple-dark);

        &:hover {
            color: var(--tertiary-purple);
        }
    }

    a {
        color: var(--purple-dark);
        text-align: right;
        font-family: var(--font-family-sans-serif);
        font-size: 18px;
        font-style: normal;
        font-weight: 600;
        line-height: 100%;

        @media (min-width: 768px) {
            color: white;
        }
        text-decoration: none;
    }
}

</style>
