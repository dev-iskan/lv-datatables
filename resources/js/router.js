import VueRouter from 'vue-router'
import Vue from 'vue'
import UserIndex from './components/users/Index'
Vue.use(VueRouter);


const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/users',
            name: 'users.index',
            component: UserIndex
        }
    ]
});

export default router
