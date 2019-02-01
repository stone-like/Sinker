<template>
    <v-toolbar>
     <router-link to="/welcome">
       <v-toolbar-title @click="toHome">Spark</v-toolbar-title>
     </router-link>
    <v-spacer></v-spacer>
    <app-notification v-if="userLoggedIn"></app-notification>
    <div>
     <!-- <router-link v-for="item in filteritems" :to="item.to" :key="item.title" >
      <v-btn flat>{{item.title}}</v-btn>
     </router-link> -->
       <router-link to='/forum'>
      <v-btn flat @click="toRead">Forum</v-btn>
     </router-link>
     <router-link to='/login' v-if="!userLoggedIn">
      <v-btn flat>Login</v-btn>
     </router-link>
     <router-link to='/ask' v-if="userLoggedIn">
      <v-btn flat @click="toRead">Ask Question</v-btn>
     </router-link>
     <router-link to='/category' v-if="userLoggedIn" >
      <v-btn flat @click="toRead">Category</v-btn>
     </router-link>
     <router-link to='/logout' v-if="userLoggedIn">
      <v-btn flat>Logout</v-btn>
     </router-link>
    </div>
  </v-toolbar>
</template>

<script>
import {mapGetters} from 'vuex'
import AppNotification from "./AppNotification"
export default {
    components:{
      AppNotification
    },
    //   data(){
//       return{
//           items:[
//             {title:'Forum',to:'/forum',show:true},
//             {title:'Login',to:'/login',show:!User.isLogeedIn()},
//             {title:'Ask Question',to:'/question',show:User.isLogeedIn()},
//             {title:'Category',to:'/category',show:User.isLogeedIn()},
//             {title:'Logout',to:'/logout',show:User.isLogeedIn()}
//           ],

//       }
//   },

  computed:{

      ...mapGetters({
           userLoggedIn:'userLoggedIn'//これでログイン状態を監視している、ログインしていなかったらfalse、していればtrue

     })
    //   filteritems:function(){
    //       return _.pickBy(this.items,function(item){
    //           return item.show;
    //       });

    //   }
  },
  methods:{
      toRead(){
          this.$store.dispatch('changeReadMode');
          console.log(this.$store.getters.getReadFlag);
          console.log(this.$store.getters.getHomeFlag);
      },
      toHome(){
          this.$store.dispatch('changeHomeMode');
           console.log(this.$store.getters.getReadFlag);
          console.log(this.$store.getters.getHomeFlag);
      }
  }

}
</script>

<style>
a{
    text-decoration: none;
    color:currentColor;
}
</style>
