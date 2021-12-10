import AlreadyLoggedIn from "../utils/AlreadyLoggedIn";

export default {
    path: '/user',
    component: () => import(/* webpackChunkName: "user" */ '../views/user'),
    redirect: '/user/login',
    beforeEnter: AlreadyLoggedIn,
    children: [
        {
            path: 'login',
            component: () => import(/* webpackChunkName: "Login" */ '../views/user/Login'),
        },
        {
            path: 'register',
            component: () => import(/* webpackChunkName: "Register" */ '../views/user/Register')
        },
        {
            path: 'forgot-password',
            component: () => import(/* webpackChunkName: "ForgotPassword" */ '../views/user/ForgotPassword')
        },
        {
            path: 'reset-password',
            component: () => import(/* webpackChunkName: "ResetPassword" */ '../views/user/ResetPassword')
        },
        {
            path: 'verify',
            component: () => import(/* webpackChunkName: "VerifyUser" */ '../views/user/VerifyUser')
        },
    ]
};
