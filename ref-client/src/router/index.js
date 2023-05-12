import {createRouter, createWebHistory} from "vue-router";
import {useAuthStore} from "../stores/auth.js";

let isInit = false

const routes = [
    // {
    //     path: '/',
    //     component: () => import('../views/Home.vue'),
    //     alias: '/home',
    //     name: 'home',
    //     meta: {requireAuth: true},
    // },
    {
        path: '/',
        alias: '/requests',
        component: () => import('../views/Requests.vue'),
        name: 'requests',
        meta: {requireAuth: true}
    },
    {
        path: '/links',
        component: () => import('../views/Links.vue'),
        name: 'links',
        meta: {requireAuth: true}
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
    // catch all - go home page
    {
        path: '/:pathMatch(.*)*',
        // component: () => import('../views/Requests.vue'),
        meta: {requireAuth: true},
        redirect: '/'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from) => {

    const authStore = useAuthStore();

    if (!isInit && localStorage.getItem('token') && from.name !== 'login') {
        await authStore.me()
        isInit = true;
    }

    if (authStore.isAuth && (to.name === 'login' || to.name === 'registration')) {
        return {name: 'requests'}
    }

    if (to.matched.some(record => record.meta.requireAuth)) {
        if (!authStore.isAuth && to.name !== 'login') {
            return {name: 'login'}
        }
    }
})

export default router;