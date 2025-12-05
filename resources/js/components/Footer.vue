<script setup lang="ts">
import { login, logout } from '@/routes';
import { edit } from '@/routes/profile';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = usePage().props.auth?.user;
</script>

<template>

    <footer>
        <img class="footer-cactus" src="../../assets/svg/cactus.svg">

        <div class="footer-content">

            <div class="social-navigation">
            <div class="footer-navigation">

                <div class="nav-demi">
                    <Link href="/">
                        Accueil
                    </Link>
                    <Link href="/about">
                        À propos
                    </Link>
                    <Link href="/concept">
                        Notre concept
                    </Link>

                </div>

                <div class="nav-demi">
                    <Link class="button-link" v-if="user" :href="logout()">
                        Déconnexion
                    </Link>
                    <Link v-if="!user" :href="login()">
                        Connexion
                    </Link>
                    <Link v-if="user" :href="edit()">
                        Paramètres
                    </Link>
                    <Link href="/mentions-legales">
                        Mentions légales
                    </Link>
                </div>

            </div>


                <div class="footer-socials">
                    <a target="_blank" href="https://instagram.com" class="footer-icon-socials">
                        <img src="../../assets/svg/instagram.svg" alt="Instagram">
                    </a>
                </div>
            </div>
            <img class="footer-illu mobile" src="../../assets/svg/footer-illustration.svg">
            <img class="footer-illu desktop" src="../../assets/svg/footer-illu-cactus.svg">

        </div>
    </footer>

</template>

<style scoped lang="scss">

.button-link {
    display: flex;
    justify-content: left;

    @media (min-width: 768px) {
        justify-content: right;
    }
}

    footer{
        display: flex;
        flex-direction: column;
        background: var(--purple-dark);
        gap: 5pxpx;
        color: var(--white);
        font-family: Jost;
        font-size: 18px;
        font-style: normal;
        font-weight: 600;
        line-height: 100%;
        padding: 24px;
        padding-top: 10px;

        @media (min-width: 768px) {
            padding: 38px;
        }

        @media (min-width: 998px) {
            padding: 70px;
        }

        .footer-cactus {
            height: 72px;
            width: 40px;
            margin-left: auto;
            margin-right: 12px;
            transform: rotate(12deg);

            @media (min-width: 768px) {
                display: none;
            }
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            gap: 45px;

            @media (min-width: 768px) {
                flex-direction: row-reverse;
                justify-content: space-between;
            }
        }

        a {
            text-decoration: none;
            color: var(--white);
        }

        .footer-navigation {
            display: flex;
            flex-direction: column;
            gap: 38px;

            @media (min-width: 768px) {
                flex-direction: row;
            }
        }

        .social-navigation {
            display: flex;
            flex-direction: column;
            gap: 45px;

            @media (min-width: 768px) {
               justify-content: space-between;
            }
        }

        .nav-demi {
            display: flex;
            flex-direction: column;
            gap: 38px;

            @media (min-width: 768px) {
                min-width: 150px;
                text-align: right;
            }

            @media (min-width: 998px) {
                min-width: 230px;
            }
        }

        .footer-socials {
            display: flex;
            flex-direction: row;
            gap: 18px;
            align-items: center;

            @media (min-width: 768px) {
                margin-left: auto;
            }
        }

        .footer-socials .footer-icon-socials {

            img {
                height: 28px;
                width: 28px;

                @media (min-width: 1200px) {
                    height: 42px;
                    width: 42px;
                }
            }
        }

        .footer-illu {
            width: 100%;
            height: 100%;
            object-fit: cover;
            max-width: 400px;

            @media (min-width: 768px) {
                width: 40%;
                max-width: unset;
            }
        }

        .footer-illu.mobile {
            display: block;

            @media (min-width: 768px) {
                display: none;
            }
        }

        .footer-illu.desktop {
            display: none;

            @media (min-width: 768px) {
                display: block;
            }
        }
    }

</style>
