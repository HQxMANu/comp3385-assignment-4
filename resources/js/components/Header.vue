<script setup>

</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">COMP3385</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <RouterLink class="nav-link" :class="{ active: $route.path === '/'}" to="/">Home</RouterLink>
                    </li>
                    <li class="nav-item">
                        <RouterLink class="nav-link" :class="{ active: $route.path === '/about'}" to="/about">About</RouterLink>
                    </li>
                    <li class="nav-item">
                        <RouterLink class="nav-link" :class="{ active: $route.path === '/movies'}" to="/movies">Movies</RouterLink>
                    </li>
                    <li class="nav-item">
                        <RouterLink class="nav-link" :class="{ active: $route.path === '/movies/create'}" to="/movies/create">Add Movie</RouterLink>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li v-if="isLoggedIn" class="nav-item">
                        <a href="#" class="nav-link" @click.prevent="logout">
                            <span v-if="loggingOut" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Logout
                        </a>
                    </li>

                    <li v-else class="nav-item">
                        <router-link class="nav-link" to="/login">Login</router-link>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
