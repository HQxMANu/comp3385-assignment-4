<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form @submit.prevent="login">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    v-model="email"
                                    required
                                    :disabled="loading"
                                >
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    v-model="password"
                                    required
                                    :disabled="loading"
                                >
                            </div>

                            <div v-if="error" class="alert alert-danger">
                                {{ error }}
                            </div>

                            <button type="submit" class="btn btn-primary" :disabled="loading">
                                <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                {{ loading ? 'Logging in...' : 'Login' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);

const login = () => {
    error.value = '';
    loading.value = true;

    fetch('/api/v1/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            email: email.value,
            password: password.value
        })
    })
        .then(response => response.json())
        .then(data => {
            loading.value = false;

            if (data.error) {
                error.value = data.error;
            } else if (data.access_token) {
                localStorage.setItem('jwt_token', data.access_token);

                router.push('/movies');
            } else {
                error.value = 'An unexpected error occurred. Please try again.';
            }
        })
        .catch(err => {
            loading.value = false;
            error.value = 'An error occurred. Please try again.';
            console.error(err);
        });
};
</script>
