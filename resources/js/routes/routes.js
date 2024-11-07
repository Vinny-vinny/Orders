import {useAuthStore} from "../store/auth.js";

const AuthenticatedLayout = () => import('../layouts/Authenticated.vue')

function requireLogin(to, from, next) {
    const auth = useAuthStore()
    let isLogin = false;
    console.log("=========")
    console.log(auth.authenticated)
    console.log(auth.user)
    console.log("******")
    isLogin = !!auth.authenticated;

    if (isLogin) {
        next()
    } else {
        next('/login')
    }
}
export default [
    {
        path: '/login',
        name: 'auth.login',
        component: () => import('../pages/auth/Login.vue')
    },

    {
        path: '/',
        component: AuthenticatedLayout,
        beforeEnter: requireLogin,
        children: [
            {
                name: 'home.index',
                path: '',
                component: () => import('../pages/home/Index.vue'),
                meta: { breadCrumb: 'Home' }
            },
            {
                name: 'orders.index',
                path: 'orders',
                component: () => import('../pages/orders/Index.vue'),
                meta: { breadCrumb: 'Orders' }
            },
            {
                name: 'orders.create',
                path: 'orders/create',
                component: ()  => import('../pages/orders/Create.vue'),
                meta: { breadCrumb: 'Add new order' }
            }
        ]
    },
    {
        path: "/:pathMatch(.*)*",
        name: 'NotFound',
        component: () => import("../pages/errors/404.vue"),
    },
];
