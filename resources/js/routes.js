import Dashboard from './components/Dashboard.vue';
import Login from './components/auth/Login.vue';

import ContentModelsMain from './components/content-models/Main.vue';
import ContentModelsList from './components/content-models/List.vue';
import CreateContentModel from './components/content-models/Create.vue';
import EditContentModel from './components/content-models/Edit.vue';

import ContentEntriesMain from './components/content-entries/Main.vue';
import ContentEntriesList from './components/content-entries/List.vue';
import CreateContentEntry from './components/content-entries/Create.vue';
import EditContentEntry from './components/content-entries/Edit.vue';

import Users from './components/users/Users.vue';
import Detail from "./components/users/Detail.vue";

export const routes = [
    {
        path: '/', redirect: '/dashboard'
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        component: Login,
        meta: {
            emptyLayout: true
        }
    },
    {
        path: '/model',
        component: ContentModelsMain,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: '/',
                component: ContentModelsList,
                meta: {
                    type: 'list'
                }
            }
            ,
            {
                path: 'create',
                component: CreateContentModel,
                meta: {
                    type: 'view'
                }
            },
            {
                path: 'edit/:id',
                component: EditContentModel,
                meta: {
                    type: 'view'
                }
            }
        ]
    },
    {
        path: '/entry/:model',
        component: ContentEntriesMain,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: '/',
                component: ContentEntriesList,
                meta: {
                    type: 'list'
                }
            },
            {
                path: 'create',
                component: CreateContentEntry,
                meta: {
                    type: 'view'
                }
            },
            {
                path: 'edit/:id',
                component: EditContentEntry,
                meta: {
                    type: 'view'
                }
            }
        ]
    },
    {
        path: '/users',
        component: Users,
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/users/show/:id',
        component: Detail,
        meta: {
            requiresAuth: true
        },
    }
];
