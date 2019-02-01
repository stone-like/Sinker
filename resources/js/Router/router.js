import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import store from  '../Store/store'

import Login from '../components/login/Login'
import Signup from '../components/login/Signup'
import Forum from '../components/forum/Forum.vue'
import Logout from '../components/login/Logout'
import Read from '../components/forum/Read'
import Create from '../components/forum/Create'
import CreateCategory from '../components/category/CreateCategory.vue'
import Modal from "../components/expantion/Modal.vue"
import Welcome from "../components/css-related/Welcome.vue"

const routes = [
    {path: '/login',component: Login},
    {path:'/modal',component:Modal},
    {path: '/signup',component: Signup},
    {path: '/forum',component: Forum ,name:'forum'},
    {path: '/logout',component: Logout},
    {path:'/question/:slug',component: Read,name:'read'},
    {path:'/ask',component:Create},
    {path:'/welcome',component:Welcome},
    {
        path:'/category',
        component:CreateCategory,
        beforeEnter (to,from,next){
            if(store.getters.checkadmin){

                next()
            }
            else{
                store.dispatch('changeModalFlag');




            }
        }},
        {
            path:'/deleted',
            component:Login,
            beforeEnter(to,from,next){
                next()
            }
        }
]

const router = new VueRouter(
    {
        routes,
    hashbang:false,
    mode:'history'
    }
)

export default router
