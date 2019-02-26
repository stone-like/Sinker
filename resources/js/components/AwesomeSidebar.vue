<template>
 <div>
    <nav :class="comp_Sidebar" ref="sidebar">
        <ul class="side-nav">
            <li :class="comp_Forum">
                <router-link to='/forum' class="side-nav__link">
                    <div class="side-nav__main">

                     <svg class="side-nav__icon">
                          <use xlink:href="../Helpers/img/sprite.svg#icon-brush"></use>
                     </svg>
                     <span class="side-nav__menu">Forum</span>
                    </div>
                     <small class="side-nav__small">sweet home</small>
                </router-link>
            </li>
            <li :class="comp_Question"  v-if="userLoggedIn" >
                <router-link to='/ask' v-if="userLoggedIn" class="side-nav__link">
                   <div class="side-nav__main">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-chat" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                    <span class="side-nav__menu">Question</span>
                    </div>
                    <small class="side-nav__small">sweet home</small>
                </router-link>
            </li>
            <li :class="comp_Home">
                <router-link to="/welcome" class="side-nav__link">
                  <div class="side-nav__main">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-home"></use>
                    </svg>
                    <span class="side-nav__menu">Home</span>
                   </div>
                   <small class="side-nav__small">sweet home</small>
                </router-link>
            </li>
            <li class="side-nav__item"  v-if="userLoggedIn" >
                <router-link to="/userprofile"  class="side-nav__link">
                  <div class="side-nav__main">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-v-card" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                     <span class="side-nav__menu">User Profile</span>
                   </div>
                     <small class="side-nav__small">sweet home</small>
                </router-link>
            </li>
            <li :class="comp_Setting"  v-if="userLoggedIn" >
                <router-link to="/setting" class="side-nav__link">
                  <div class="side-nav__main">
                    <svg class="side-nav__icon">
                        <use xlink:href="../Helpers/img/sprite.svg#icon-cog" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                    <span class="side-nav__menu">Setting</span>
                   </div>
                    <small class="side-nav__small">sweet home</small>
                </router-link>
            </li>
            <li :class="comp_Category" v-if="userLoggedIn&&this.$store.getters.checkadmin">
                <router-link to='/category' v-if="userLoggedIn" class="side-nav__link">
                  <div class="side-nav__main">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-text-document" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                     <span class="side-nav__menu">Category</span>
                  </div>
                     <small class="side-nav__small">sweet home</small>
                </router-link>
            </li>
            <li :class="comp_Bookmark"  v-if="userLoggedIn" >
                <router-link to='/bookmark' v-if="userLoggedIn" class="side-nav__link">
                  <div class="side-nav__main">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite5.svg#icon-star-outlined" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                     <span class="side-nav__menu">Bookmark</span>
                  </div>
                     <small class="side-nav__small">sweet home</small>
                </router-link>
            </li>
            <li class="side-nav__item"  v-if="userLoggedIn" >
                <router-link to='/logout' v-if="userLoggedIn" class="side-nav__link">
                  <div class="side-nav__main">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite2.svg#icon-log-out" v-if="userLoggedIn"></use>
                    </svg>
                     <span class="side-nav__menu">Logout</span>
                  </div>
                    <small class="side-nav__small">sweet home</small>
                </router-link>
            </li>
            <li class="side-nav__item" v-if="!userLoggedIn" >
                <a href='/login' class="side-nav__link">
                  <div class="side-nav__main">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite2.svg#icon-login"></use>
                    </svg>
                     <span class="side-nav__menu">Login</span>
                 </div>
                     <small class="side-nav__small">sweet home</small>
                </a>
            </li>
        </ul>
    </nav>
 </div>
</template>

<script>
import {mapGetters} from 'vuex'
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
    data(){
      return{
          forum:false,
          ask:false,
          setting:false,
          home:false,
          category:false,
          isInvisible:false
      }
    },
 computed:{

      ...mapGetters({
           userLoggedIn:'userLoggedIn'//これでログイン状態を監視している、ログインしていなかったらfalse、していればtrue

     }),
     comp_Forum(){
         return this.forum ? "side-nav__item active":  "side-nav__item";
     },
     comp_Question(){
         return this.ask ?  "side-nav__item active":  "side-nav__item";
     },
     comp_Setting(){
         return this.setting ?  "side-nav__item active":  "side-nav__item";
     },
     comp_Home(){
         return this.home ?  "side-nav__item active":  "side-nav__item";
     },
     comp_Category(){
         return this.category ?  "side-nav__item active":  "side-nav__item";
     },
     comp_Bookmark(){
         return this.bookmark ?  "side-nav__item active":  "side-nav__item";
     },
     comp_Sidebar(){
         return this.userLoggedIn ? "sidebar" : "sidebar mini";
     }
 },
  methods:{
      listen(){
          var self = this;
          self.$eventBus.$on("changeSidebarMode",(topath) => {
              console.log("sidemodeok?")
              console.log(topath)
               this.setActive(topath)
               //あるルートから出るときと入るときで都合二回呼ばれることになる
               //sidebarでの処理はReadModeへ入るときの処理と、出るとき、それとReadToReadのwipe、ReadToHomeででるとき、でfade-sideで消す
               //HomeToReadで入るとき、同じくfade-sideなのでfade-upはここではいらない、それはtoolbarでの処理
               var ToolMode = self.$store.getters.getTransitionTool;
               var ReadMode = self.$store.getters.getTransitionRouter;
              if(ToolMode == "fade-side"){
                if(ReadMode == "HomeToRead"){
                    var tm_tool1 = new TimelineMax();
    　　　　　　　　　tm_tool1.fromTo(self.$refs.sidebar,1,{x:-200,y:0,opacity:0},{x:0,y:0,opacity:1})
                       　
                }else if(ReadMode == "ReadToHome"){
                      var tm_tool1 = new TimelineMax();
    　　　　　　　　　tm_tool1.to(self.$refs.sidebar,1,{x:-200,opacity:0})
                }
              }///ReadToRead
              else if(ToolMode == "wipe"){
                   if(ReadMode == "ReadToRead_enter"){
                       //入るときにwiperは全部隠れているのでそれを左にどけたらsidebarを出現
                       var tm_tool1 = new TimelineMax();
    　　　　　　　　　tm_tool1.fromTo(self.$refs.sidebar,1,                {x:-200,y:0,opacity:0},{x:0,y:0,opacity:1})

                   }else if(ReadMode == "ReadToRead_leave"){
                      //最初にsidebarを隠して、次にwiperしたい
                        var tm_tool1 = new TimelineMax();
    　　　　　　　　　tm_tool1.to(self.$refs.sidebar,1,                           {x:-200,opacity:0})
                   }
              }
          })

        //   self.$eventBus.$on("invisibleSidebar",(boolean) => {
        //       self.isInvisivle = boolean;
        //   })
      },
      setActive(topath){
        if(topath == "/forum"){
           　//ひとつをtrueとするだけじゃ毎回このtrueが他コンポーネントでも引き継がれてしまうので一旦これ以外全部falseの必要がある
            this.forum = true;
            this.ask= false;
            this.home = false;
             this.settig = false;
             this.category = false;
        }else if(topath == "/ask"){
            this.ask= true;
            this.forum= false;
            this.home = false;
             this.settig = false;
              this.category = false;
        }else if(topath == "/home"){
            this.home = true;
            this.ask= false;
            this.forum = false;
             this.settig = false;
              this.category = false;
        }else if(topath == "/setting"){
            this.settig = true;
            this.ask= false;
            this.home = false;
             this.forum = false;
              this.category = false;
        }else if(topath == "/category"){
             this.forum = false;
            this.ask= false;
            this.home = false;
             this.settig = false;
             this.category = true;
        }
      }
  },
   created(){
      this.listen()
  },
  beforeDestroy(){
    this.$eventBus.$off("changeSidebarMode")
  }

}
</script>

<style lang="scss">
.sidebar{
    background-color: #363636;
    // display:flex;
    // flex-direction: column;
    // justify-content: space-around;
    height: 66vh;
    position: fixed;
    z-index:2;
    top:0;
    left:0;
    overflow-x: hidden;
    margin-top: 17vh;//常に中心に来るように
    border-radius: 2px;
    box-shadow:0px 0px 1.5px 0px #808080;


    //中身を中心へ
     display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.sidebar.mini{
    height: 25vh;
    margin-top: 37.5vh;
}
.side-nav{
  width: 100%;
  height: 100%;
  font-size: 1.4rem;
  list-style: none;
  padding: 0;

  &__item{
      position: relative;
      &:not(:last-child){
          margin-bottom: .5rem;
          border-bottom: 1px solid #2c2c2c;
      }
  }

  &__link:link,
  &__link:visited{
     text-decoration: none;
     text-transform: uppercase;
     color:rgb(223, 221, 221);
     display: block;
     padding: 1.5rem 3rem;
     display: flex;
     flex-direction: column;
     align-items: flex-start;

     position: relative;
     z-index: 10;
  }

  &__main{
      display: flex;
  }

  &__icon{
      width: 1.75rem;
      height: 1.75rem;
      fill:currentColor;
      margin-right: 2rem;
  }

  &__small{
      margin-top: .7rem;
      display:block;
      font-size: 0.9rem;
  }

  &__menu,&__small,&__icon{
    position: relative;
    transition: all .3s linear;
  }

  &__item:hover  &__icon{
    opacity:1;
    animation:moveFromTop .3s ease-in-out;
  }

  &__item:hover  &__menu{
    opacity:1;
    animation:moveFromLeft .3s ease-in-out;
  }

  &__item:hover  &__small{
    opacity:1;
    animation:moveFromRight .3s ease-in-out;
  }

  &__item:hover  &__link{
    color:#e67e22;
  }

  .active{
     //なぜかheight0がかかってしまうのでheight:autoをかけてあげる
     height: auto;
      .side-nav__link{

      position: relative;
      color: #e67e22;
      border:0;
    //   box-shadow: 0 0 5px #DDD;

    //   border-left: 4px solid #e67e22;
    //   border-right: 4px solid #e67e22;
    //   margin: 0 -4px;
      }

       .side-nav__link::before{
          content:"";
          position: absolute;
          top: 42%;
          left: 0;
          border-left: 5px solid #e67e22;
          border-top: 5px solid transparent;
          border-bottom: 5px solid transparent;
        //左の三角部分
       }

        .side-nav__link::after{
           content:"";
           position: absolute;
           top: 42%;
           right: 0;
           border-right: 5px solid #e67e22;
           border-top: 5px solid transparent;
           border-bottom: 5px solid transparent;
       }

  }

//   &__item &__link.active:before{
//       content:"";
//       position: absolute;
//       top: 42%;
//       left: 0;
//       border-left: 5px solid #e67e22;
//       border-top: 5px solid transparent;
//       border-bottom: 5px solid transparent;
//       //左の三角部分
//   }

//    &__item &__link.active:after{
//        content:"";
//        position: absolute;
//        top: 42%;
//        right: 0;
//        border-right: 5px solid #e67e22;
//        border-top: 5px solid transparent;
//        border-bottom: 5px solid transparent;

//    }
}

@keyframes moveFromTop{
    from{
        opacity:0;
        transform:translateY(200%);
    }
    to{
        opacity: 1;
        transform: translateY(0%);
    }
}

@keyframes moveFromLeft{
    from{
        opacity:0;
        transform:translateX(200%);
    }
    to{
        opacity: 1;
        transform: translateX(0%);
    }
}

@keyframes moveFromRight{
    from{
        opacity:0;
        transform:translateX(-200%);
    }
    to{
        opacity: 1;
        transform: translateX(0%);
    }
}





</style>
