<template>
    <form @submit.prevent="saveMovie" id="movieForm">
        <div v-if="successMessage" class="alert alert-success mb-3">
            {{ successMessage }}
        </div>

        <div v-if="errorMessage" class="alert alert-danger mb-3">
            {{ errorMessage }}
        </div>

        <div class="form-group mb-3">
            <label for="title" class="form-label">Movie Title</label>
            <input type="text" name="title" id="title" class="form-control" v-model="title" required />
        </div>

        <div class="form-group mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" v-model="description" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="poster" class="form-label">Poster</label>
            <input type="file" name="poster" id="poster" class="form-control" @change="handleFileUpload" required />
        </div>

        <button type="submit" class="btn btn-primary" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            {{ loading ? 'Saving...' : 'Save' }}
        </button>
    </form>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const title = ref('');
const description = ref('');
const poster = ref(null);
const loading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const handleFileUpload = (event) => {
    poster.value = event.target.files[0];
};

const saveMovie = () => {
    successMessage.value = '';
    errorMessage.value = '';
    loading.value = true;

    const token = localStorage.getItem('jwt_token');

    if (!token) {
        router.push('/login');
        return;
    }

    let formData = new FormData();
    formData.append('title', title.value);
    formData.append('description', description.value);
    formData.append('poster', poster.value);

    fetch("/api/v1/movies", {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
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
            loading.value = false;

            if (data.errors) {
                const errorList = Object.values(data.errors).flat();
                errorMessage.value = errorList.join(', ');
            } else if (data.message) {
                successMessage.value = data.message;


                title.value = '';
                description.value = '';
                poster.value = null;
                document.getElementById('movieForm').reset();


                setTimeout(() => {
                    router.push('/movies');
                }, 2000);
            }
        })
        .catch(error => {
            loading.value = false;
            console.error("Error saving movie:", error);
            errorMessage.value = "An error occurred while saving the movie. Please try again.";
        });
};
</script>