import Vue from 'vue'
import Router from 'vue-router'

import Login from '@/views/Login.vue'
import Layout from '@/views/Layout.vue'
import Banner from '@/views/Banner.vue'
import Home from '@/views/Home.vue'
import Reg from '@/views/Reg.vue'
import UserLayout from '@/views/UserLayout.vue'
import UserProfile from '@/views/user/Profile.vue'
import UserOrder from '@/views/user/Order.vue'
import UserWithdraw from '@/views/user/Withdraw.vue'
import UserTrade from '@/views/user/Trade.vue'
import UserWithdrawList from '@/views/user/WithdrawList.vue'

// const Layout = resolve => { require(['@/views/Layout.vue'], resolve) };

Vue.use(Router);

const routes = [
    {
        path: '/user',
        name: 'user',
        component: UserLayout,
        children: [
            {path: 'profile', component: UserProfile, meta: { requireAuth: true }},
            {path: 'order', component: UserOrder, meta: { requireAuth: true }},
            {path: 'trade', component: UserTrade, meta: { requireAuth: true }},
            {path: 'withdraw', component: UserWithdraw, meta: { requireAuth: true }},
            {path: 'withdraw/list', component: UserWithdrawList, meta: { requireAuth: true }},
        ]
    },
    {
        path: '*',
        name: 'default',
        component: Layout,
        children: [
            { path: '/banner', component: Banner, meta: { requireAuth: false } },
            { path: '/login', component: Login, meta: { requireAuth: false } },
            { path: '/reg', component: Reg, meta: { requireAuth: false } },
            { path: '/', component: Home, meta: { requireAuth: false } },
        ]
    }
];

const router = new Router({ routes: routes });//mode: 'history', 

export default router
