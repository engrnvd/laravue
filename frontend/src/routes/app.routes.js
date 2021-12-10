import AuthRequired from "../utils/AuthRequired";

export default {
    path: '/',
    component: () => import(/* webpackChunkName: "app" */ '../views/app'),
    redirect: '/dashboard',
    beforeEnter: AuthRequired,
    children: [
        {
            path: 'dashboard',
            component: () => import(/* webpackChunkName: "dashboard" */ '../views/app/dashboard')
        },
        {
            path: 'users',
            component: () => import(/* webpackChunkName: "users" */ '../views/app/users'),
        },
        {
            path: 'profile-settings',
            component: () => import(/* webpackChunkName: "settings" */ '../views/app/settings/profile_settings'),
            children: [
                {
                    path: 'profile',
                    name: 'profile'
                },
                {
                    path: 'theme-settings',
                    name: 'theme-settings'
                },
            ]
        },
{
            path: 'companies',
            component: () => import(/* webpackChunkName: "companies" */ '../views/app/companies'),
        },

{
            path: 'employees',
            component: () => import(/* webpackChunkName: "employees" */ '../views/app/employees'),
        },

// vue routes generated here. Do not remove this line.
    ]
};
