<template>
    <div class="container mt-4">
        <h1>Movies</h1>

        <div v-if="loading" class="text-center my-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div v-else-if="movies.length === 0" class="alert alert-info">
            No movies available. <router-link to="/movies/create">Add a movie</router-link>
        </div>

        <div v-else class="row">
            <div v-for="movie in movies" :key="movie.id" class="col-md-6 mb-4">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img :src="movie.poster" class="img-fluid rounded-start" alt="Movie poster">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ movie.title }}</h5>
                                <p class="card-text">{{ movie.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from "vue";
import {useRouter} from "vue-router";

const router = useRouter();
let movies = ref([]);
let loading = ref(true);
let error = ref(null);

const fetchMovies = () => {

    const token = localStorage.getItem('jwt_token');

    if (!token) {
        router.push('/login');
        return;
    }

    const headers = {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    fetch("/api/v1/movies", {
        method: 'GET',
        headers: headers
    })
        .then(response => {
            if (response.status === 401) {
                localStorage.removeItem('jwt_token');
                router.push('/login');
                throw new Error('Unauthorized. Please login again.');
            }
            return response.json();
        })
        .then(data => {
            movies.value = data.movies;
            loading.value = false;
        })
        .catch(err => {
            console.error("Error fetching movies:", err);
            error.value = "Failed to load movies. Please try again.";
            loading.value = false;
        });
};

onMounted(() => {
    fetchMovies();
});
</script>

