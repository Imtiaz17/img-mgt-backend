import Vue from 'vue'
import VueRouter from 'vue-router'
import { getToken } from '../utils/auth';
import store from '../store/index'
Vue.use(VueRouter)
const Home  = () => import ("../views/home.vue")
const Dashboard  = () => import ("../views/dashboard.vue")
const Category  = () => import ("../views/category.vue")
const Subcategory  = () => import ("../views/subCategory.vue")
const Product  = () => import ("../views/product.vue")

const routes = [
    { path: '/', component: Home, name:'Root' },
    { path: '/dashboard', component: Dashboard, name:'dashboard'  },
    { path: '/category', component: Category , name:'category' },
    { path: '/subcategory', component: Subcategory, name:'subcategory'  },
    { path: '/products', component: Product, name:'products'  },
]

const router = new VueRouter({
    routes, // short for `routes: routes`
    hashbang : false,
    mode :'history'
  })
const whiteList = ['/'];
router.beforeEach(async (to, from, next) => {
    // determine whether the user has logged in
    const hasToken = getToken();
    const noToken = !!getToken();
    if (hasToken) {
        if (to.path == '/') {
            // if is logged in, redirect to the home page
           router.push({ name: 'dashboard' })
        }
        if (store.state['user'] == null) {
            await store.dispatch('getInfo');
        }

        next();
    } else {
        /* has no token*/
        if (whiteList.indexOf(to.path) !== -1) {
            // in the free login whitelist, go directly
            next();
        } else {
             router.push({ name: 'Root' })
            next();
        }
    }
});

export default router