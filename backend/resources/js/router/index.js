import { createRouter, createWebHistory } from 'vue-router';

import Home from '../../pages/Home.vue';
import LeagueDetail from '../../pages/LeagueDetail.vue';
import MatchDetail from '../../pages/MatchDetail.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/league/:id',
        name: 'LeagueDetail',
        component: LeagueDetail,
        props: true,
    },
    {
        path: '/matches/:id',
        name: 'MatchDetail',
        component: MatchDetail,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
