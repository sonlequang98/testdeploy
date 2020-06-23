import Home from './components/admin/Home.vue';
import Login from './components/auth/Login.vue';
import ReleaseNumberCreate from './components/admin/ReleaseNumberCreate.vue';
import ReleaseNumber from './components/admin/ReleaseNumber.vue';
import ReleaseNumberEdit from './components/admin/ReleaseNumberEdit.vue';
import PostCreate from './components/admin/PostCreate.vue';
import Post from './components/admin/Post.vue';
import PostEdit from './components/admin/PostEdit.vue';
import Category from './components/admin/Category.vue';
import CategoryCreate from './components/admin/CategoryCreate.vue';
import CategoryParentEdit from './components/admin/CategoryParentEdit.vue';
import CategoryChildEdit from './components/admin/CategoryChildEdit.vue';
import UserAdmin from './components/admin/UserAdmin.vue';
import Member from './components/admin/Member.vue';
import Setting from './components/admin/Setting.vue';
import Image from './components/admin/Image.vue';
import ImageCreate from './components/admin/ImageCreate.vue';

export const routes = [{
        path: '/',
        component: Home,
        name: 'home',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/releasenumber',
        name: 'releaseNumber',
        meta: {
            requiresAuth: true
        },
        component: ReleaseNumber
    },
    {
        path: '/releasenumber/create',
        name: 'releaseNumberCreate',
        meta: {
            requiresAuth: true
        },
        component: ReleaseNumberCreate
    },
    {
        path: '/releasenumber/edit/:id',
        name: 'ReleaseNumberEdit',
        meta: {
            requiresAuth: true
        },
        component: ReleaseNumberEdit
    },
    {
        path: '/posts/create',
        name: 'postCreate',
        meta: {
            requiresAuth: true
        },
        component: PostCreate
    },
    {
        path: '/posts',
        name: 'post',
        meta: {
            requiresAuth: true
        },
        component: Post
    },
    {
        path: '/posts/edit/:id',
        name: 'PostEdit',
        meta: {
            requiresAuth: true
        },
        component: PostEdit
    },
    {
        path: '/categories',
        name: 'category',
        meta: {
            requiresAuth: true
        },
        component: Category
    },
    {
        path: '/categories/create',
        name: 'categoryCreate',
        meta: {
            requiresAuth: true
        },
        component: CategoryCreate
    },
    {
        path: '/categories/parent/edit/:id',
        name: 'categoryParentEdit',
        meta: {
            requiresAuth: true
        },
        component: CategoryParentEdit
    },
    {
        path: '/categories/childrent/:id/edit',
        name: 'categoryChildEdit',
        meta: {
            requiresAuth: true
        },
        component: CategoryChildEdit
    },
    {
        path: '/useradmins',
        name: 'userAdmin',
        meta: {
            requiresAuth: true
        },
        component: UserAdmin
    },
    {
        path: '/members',
        name: 'member',
        meta: {
            requiresAuth: true
        },
        component: Member
    },
    {
        path: '/settings',
        name: 'setting',
        meta: {
            requiresAuth: true
        },
        component: Setting
    },
    {
        path: '/imagesManages',
        name: 'imageManages',
        meta: {
            requiresAuth: true
        },
        component: Image
    },
    {
        path: '/imagesManages/create',
        name: 'imageCreate',
        meta: {
            requiresAuth: true
        },
        component: ImageCreate
    },




];