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
    {path: '/forum',
    component: Forum ,
    name:'forum',
    beforeRouteLeave(to,from,next){
        if(to.params.name == "/ask" || "/category"){
            store.dispatch("changeTransition_Router","ReadToRead")
            store.dispatch("changeTransition_Tool","wipe")
        }else if(to.params.name == "/welcome"){
            store.dispatch("changeTransition_Router","ReadToHome")
            store.dispatch("changeTransition_Tool","fade-side")
        }
    }},
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
    component:Welcome,
    beforeRouteLeave(to,from,next){
        console.log(to.params.name)
        if(to.params.name == ("/forum" || "/ask" || "/category")){
            store.dispatch("changeTransition_Router","HomeToRead")
            store.dispatch("changeTransition_Tool","fade-up")
        }else{
            next()
        }
    }},
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
