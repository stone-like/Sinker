<template>

    <header class="header">


     <router-link to="/welcome" class="welcome">
       <!-- <div @click="toHome"> -->
           <img class="logo" src="../Helpers/img/logo.svg" @click="toHome">
       <!-- </div> -->
     </router-link>


    <nav class="navigation">

      <app-notification v-if="userLoggedIn"></app-notification>

     <!-- <router-link v-for="item in filteritems" :to="item.to" :key="item.title" >
      <v-btn flat>{{item.title}}</v-btn>
     </router-link> -->
        <router-link to='/forum'>
         <v-btn flat @click="toRead">Forum</v-btn>
        </router-link>
        <a href='/login' v-if="!userLoggedIn">
         <v-btn flat>Login</v-btn>
        </a>
        <router-link to='/ask' v-if="userLoggedIn">
         <v-btn flat @click="toRead">Ask Question</v-btn>
        </router-link>
        <router-link to='/category' v-if="userLoggedIn" >
         <v-btn flat @click="toRead">Category</v-btn>
        </router-link>
        <router-link to='/logout' v-if="userLoggedIn">
         <v-btn flat>Logout</v-btn>
        </router-link>
     </nav>

    </header>


</template>

<script>
import {mapGetters} from 'vuex'
import AppNotification from "./AppNotification"
import Sticky from 'vue-sticky-position'

export default {
    components:{
      AppNotification,
      Sticky
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
        //   this.$store.dispatch('changeReadMode');
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

<style lang="scss" scoped>
@import url('https://fonts.googleapis.com/css?family=Geostar');
a{
    text-decoration: none;
    color:currentColor;
}

.logo{
    width: 18rem;
    height: 18rem;
}

.header{
    border-bottom: 1px solid #f4f2f2;
    display:flex;
    justify-content: space-between;
    align-items: center;
    // background-color: black;
}

.welcome{
    display: flex;
    margin-top:7rem;
}

.navigation{
    display:flex;


}


</style>
