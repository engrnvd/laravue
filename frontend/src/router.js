import Vue from "vue";
import VueRouter from "vue-router";
import authRoutes from "./routes/auth.routes"
import appRoutes from "./routes/app.routes"

Vue.use(VueRouter);

const routes = [
    appRoutes,
    {
        path: '/error',
        component: () => import(/* webpackChunkName: "error" */ './views/Error')
    },
    authRoutes,
    {
        path: '*',
        component: () => import(/* webpackChunkName: "error" */ './views/Error')
    }
];

const router = new VueRouter({
    linkActiveClass: "active",
    routes,
    mode: "history"
});

export default router;
