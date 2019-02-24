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
import AppSearch from "../components/expantion/AppSearch.vue"
import UserProfile from "../setting/UserProfile.vue"
import Setting from "../setting/Setting.vue"
import Bookmark from "../components/bookmark/Bookmark.vue"
const routes = [
    {path:'/bookmark',component:Bookmark},
    {path:'/setting',component:Setting},
    {path:'/userprofile',component:UserProfile},
    {path:'/search',component:AppSearch},
    {path: '/login',component: Login},
    {path:'/modal',component:Modal},
    {path: '/signup',component: Signup},
    {path: '/forum',
    component: Forum,
    name:'forum'},
    {path: '/logout',component: Logout},
    {path:'/question/:slug',component: Read,name:'read'},
    {path:'/ask',
    component:Create,
    beforeRouteLeave(to,from,next){
        if(to.params.name == "/forum" || "/category"){
            store.dispatch("changeTransition_Router","ReadToRead")
            store.dispatch("changeTransition_Tool","wipe")
        }else if(to.params.name == "/welcome"){
            store.dispatch("changeTransition_Router","ReadToHome")
            store.dispatch("changeTransition_Tool","fade-side")
        }
    }},
    {path:'/welcome',
    component:Welcome},
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
        },
        beforeRouteLeave(to,from,next){
            if(to.params.name == "/ask" || "/forum"){
                store.dispatch("changeTransition_Router","ReadToRead")
                store.dispatch("changeTransition_Tool","wipe")
            }else if(to.params.name == "/welcome"){
                store.dispatch("changeTransition_Router","ReadToHome")
                store.dispatch("changeTransition_Tool","fade-side")
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
