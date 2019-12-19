import Vue from 'vue'
import Router from 'vue-router'

import Login from '@/views/Login.vue'
import Layout from '@/views/Layout.vue'
import BannerList from '@/views/BannerList.vue'
import WithdrawList from '@/views/WithdrawList.vue'
import OrderList from '@/views/OrderList.vue'
import OrderGrant from '@/views/OrderGrant.vue'
import OrderAdd from '@/views/OrderAdd.vue'
import UserList from '@/views/UserList.vue'
import Dashboard from '@/views/Dashboard.vue'
import AdminList from '@/views/AdminList.vue'
import AdminProfile from '@/views/AdminProfile.vue'
import TradeList from '@/views/TradeList.vue'

Vue.use(Router);

const routes = [
    { path: '/', component: Login, meta:{requireAuth:false} },
    { path: '/login', component: Login, meta:{requireAuth:false} },
    {
        path: '*',
        name: 'default',
        component: Layout,
        children: [
            { path: '/admin', component: AdminList },
            { path: '/admin/profile', component: AdminProfile },
            { path: '/banner', component: BannerList },
            { path: '/dashboard', component: Dashboard },
            { path: '/user', component: UserList },
            { path: '/user/trade', component: TradeList },
            { path: '/order', component: OrderList },
            { path: '/order/add', component: OrderAdd },
            { path: '/order/grant', component: OrderGrant },
            { path: '/withdraw', component: WithdrawList },
        ]
    }
];

const router = new Router({ routes: routes });//mode: 'history', 

export default router
