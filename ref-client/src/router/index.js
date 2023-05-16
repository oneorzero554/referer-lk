import {createRouter, createWebHistory} from "vue-router";
import {useAuthStore} from "../stores/auth.js";

let isInit = false

const routes = [
    // {
    //     path: '/',
    //     component: () => import('../views/Home.vue'),
    //     alias: '/home',
    //     name: 'home',
    //     meta: {requireAuth: true, requireVerified: true},
    // },
    {
        path: '/',
        alias: '/requests',
        component: () => import('../views/Requests.vue'),
        name: 'requests',
        meta: {requireAuth: true, requireVerified: true}
    },
    {
        path: '/links',
        component: () => import('../views/Links.vue'),
        name: 'links',
        meta: {requireAuth: true, requireVerified: true}
    },
    {
        path: '/registration',
        component: () => import('../views/Registration.vue'),
        name: 'registration'
    },
    {
        path: '/login',
        component: () => import('../views/Login.vue'),
        name: 'login'
    },
    {
        path: '/verification',
        component: () => import('../views/EmailVerification.vue'),
        name: 'verification',
        meta: {requireAuth: true}
    },
    // catch all - go home page
    {
        path: '/:pathMatch(.*)*',
        // component: () => import('../views/Requests.vue'),
        meta: {requireAuth: true, requireVerified: true},
        redirect: '/'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from) => {

    const authStore = useAuthStore();

    if (!isInit && localStorage.getItem('token') && from.name !== 'login' && from.name !== 'registration') {
        await authStore.me()

        isInit = true;
    }

    if (authStore.isAuth && (to.name === 'login' || to.name === 'registration')) {
        return {name: 'requests'}
    }

    if (authStore.isVerified && (to.name === 'verification')) {
        return {name: 'requests'}
    }

    if (to.matched.some(record => record.meta.requireAuth)) {
        if (!authStore.isAuth && to.name !== 'login') {
            return {name: 'login'}
        }
    }

    if (to.matched.some(record => record.meta.requireVerified)) {
        if (!authStore.isVerified) {
            return {name: 'verification'}
        }
    }
})

export default router;