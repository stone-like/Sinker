<template>

    <header class="header" ref="toolbar">


     <router-link to="/welcome" class="welcome">
       <div>
           <img class="logo" src="../Helpers/img/logo.svg">
       </div>
     </router-link>


    <nav class="navigation">

      <app-notification v-if="userLoggedIn"></app-notification>

     <!-- <router-link v-for="item in filteritems" :to="item.to" :key="item.title" >
      <v-btn flat>{{item.title}}</v-btn>
     </router-link> -->
        <router-link to='/forum'>
         <v-btn flat>Forum</v-btn>
        </router-link>
        <a href='/login' v-if="!userLoggedIn">
         <v-btn flat>Login</v-btn>
        </a>
        <router-link to='/ask' v-if="userLoggedIn">
         <v-btn flat>Ask Question</v-btn>
        </router-link>
        <router-link to='/category' v-if="userLoggedIn" >
         <v-btn flat>Category</v-btn>
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
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"

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
    //   toRead(){
    //       changeToRead()
    //   },
    //   toHome(){
    //      changeToHome()
    //   },
    //   changeToRead(){
    //        this.$store.dispatch('changeReadMode');
    //   },
    //   changeToHome(){
    //       this.$store.dispatch('changeHomeMode');
    //   },
      listen(){
          var self = this;
          self.$eventBus.$on("changeToolbarMode",() => {
               var ToolMode = self.$store.getters.getTransitionTool;
              if(ToolMode == "fade-up"){
                var tm_tool1 = new TimelineMax();
　　　　　　　　　　　tm_tool1.to(self.$refs.toolbar,.9,{y:-200,opacity:0})　　　
              }
              else if(ToolMode == "fade-side"){

              }
              else if(ToolMode == "wipe"){

              }
          })
      }
  },
  created(){
      this.listen()
  },
  beforeDestroy(){
    this.$eventBus.$off("changeToolbarMode")
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
