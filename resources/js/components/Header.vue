<script setup lang="ts">
import { dashboard, login, logout } from '@/routes';
import { edit } from '@/routes/profile';
import { create } from '@/routes/posts';
import { Link } from '@inertiajs/vue3';
import UserInfo from '@/components/UserInfo.vue';
import { usePage } from '@inertiajs/vue3';
import NavUser from '@/components/NavUser.vue';
const page = usePage();
const user = page.props.auth.user;

</script>


<template>
    <header>
        <nav class="bg-primary nav top-nav">
            <Link
                v-if="$page.props.auth.user"
                :href="dashboard()"

            >
                <div v-if="$page.props.auth.user" class="user-infos"><UserInfo  :user="user" /></div>
            </Link>
            <template v-else>
                <Link
                    :href="login()"

                >
                    Log in
                </Link>
            </template>
            <Link :href="edit()" prefetch as="button">
                Settings
            </Link>

            <Link :href="logout()" prefetch as="button">
                logout
            </Link>

            <NavUser></NavUser>
            <div v-if="$page.props.auth.user" class="user-infos"> <Link :href="create()" prefetch as="button">
                Ajouter
            </Link></div>
            <div v-else class="user-infos"> <Link :href="login()" prefetch as="button">
                Ajouter
            </Link></div>

        </nav>
    </header>
</template>

<style scoped lang="scss">
.user-infos {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 10px;
    background: black;
    color: white;
    border-radius: 45px;
    padding: 16px 48px;
    gap: 20px;
}

.top-nav {
    justify-content: right;
    display: flex;
    flex-direction: row-reverse;
    gap: 24px;
    align-items: center;
    padding: 32px;

    a,button {
        color: white;
        text-decoration: none;
    }
}

</style>
