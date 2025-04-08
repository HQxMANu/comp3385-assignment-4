import { createRouter, createWebHistory } from 'vue-router';
import AboutView from "../Pages/AboutView.vue";
import HomeView from "../Pages/HomeView.vue";
import AddMovieView from "../Pages/AddMovieView.vue";
import MoviesView from "../Pages/MoviesView.vue";
import LoginView from "../Pages/LoginView.vue";

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView
    },
    {
        path: '/movies',
        name: 'movies',
        component: MoviesView,
        meta: { requiresAuth: true }
    },
    {
        path: '/movies/create',
        name: 'add-movie',
        component: AddMovieView,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView,
        meta: { guestOnly: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const isLoggedIn = localStorage.getItem('jwt_token') !== null;

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isLoggedIn) {
            next({ name: 'login', query: { redirect: to.fullPath } });
        } else {
            next();
        }
    }
    else if (to.matched.some(record => record.meta.guestOnly)) {
        if (isLoggedIn) {
            next({ name: 'movies' });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
