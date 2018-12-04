import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Seller from '@/components/Seller'
import Buyer from '@/components/Buyer'
import Market from '@/components/Market'

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home
        },
        {
            path: '/seller',
            name: 'Seller',
            component: Seller
        },
        {
            path: '/buyer',
            name: 'Buyer',
            component: Buyer
        },
        {
            path: '/market',
            name: 'Market',
            component: Market
        }
    ]
})
