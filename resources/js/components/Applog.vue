<template>
 <div class="background">

  <div class="box">

         <div :class="comp_main"></div>

         <transition :name="comp_slide" mode="out-in">
          <login v-if="visible_flag" key="login"></login>
          <signup v-else key="signup"></signup>
         </transition>

         <div class="login_msg">Have an account?</div>
         <div class="signup_msg">Don't have an account?</div>


         <button class="login_btn" @click="bounce_right">Login</button>

         <button class="signup_btn" @click="bounce_left">signup</button>
  </div>
 </div>
</template>

<script>
import Login from "./login/Login"
import Signup from "./login/Signup"
export default {
 data(){
     return{
         main:"main",
         slide:"",
         visible_flag:true

     }
 },
 components:{Login,Signup},
 created(){
     this.$router.push("/login")
 },
 methods:{
     bounce_left(){
        this.main= "main left";
        this.slide="slide-left";
        this.visible_flag=false;
     },
     bounce_right(){
         this.main = "main right";
         this.slide = "slide-right";
          this.visible_flag=true;
     }
 },
 computed:{
     comp_main(){
         return this.main;
     },
     comp_slide(){
         return this.slide;
     }
 }
}
</script>

<style lang="scss" scoped>
.background{
    width: 100%;
    height: 100%;
    background-image:url('../Helpers/img/abstract.jpg');
    background-size: cover;
}
.slide-left-enter-active{
 animation: signup .8s ease-in-out forwards;

}
.slide-left-leave-active{
//  animation: signup 1s ease-in-out forwards reverse;

}
// .slide-left-enter{
// left: 75%;
// //  visibility: hidden;
// }
.slide-left-leave-to{
left:75%;
 visibility: hidden;
}

.slide-right-enter-active{
  animation: login .8s ease-in-out forwards;

}
.slide-right-leave-active{
//    animation: login 1s ease-in-out forwards reverse;

}
// .slide-right-enter{
//   left: 25%;
// //   visibility: hidden;
// }
.slide-right-leave-to{
  left: 25%;
  visibility: hidden;
}
 .left{
     animation:main_left .8s ease-in-out forwards;
 }

 @keyframes signup{
     0%{
         left: 70%;
       visibility: hidden;
     }
     90%{
         visibility: visible;
         left: 17%;
     }
     100%{
        visibility: visible;
        left:30%;
     }
 }

  @keyframes login{
     0%{
         left:30%;
       visibility: hidden;
     }
     90%{
         visibility: visible;
         left: 83.5%;
     }
     100%{
        visibility: visible;
        left:70%;
     }
 }


 @keyframes main_left{
     0%{
        left:70%;
     }
     50%{
         left:22.5%;
     }
     100%{
         left:30%;
     }
 }

 .right{
     animation:main_right .8s ease-in-out forwards;
 }

 @keyframes main_right{
     0%{
        left:30%;
     }
     50%{
         left:77.5%;
     }
     100%{
         left:70%;
     }
 }


 .box{
     height:30rem;
     width: 150rem;
     background-color:rgba(0,0,0,0.7);
     border-radius: 5px;
     position: absolute;
     top: 50%;
     left: 50%;
     transform: translate(-50%,-50%);
 }

 .main{
     height: 55rem;
     width: 60rem;
     background-color: white;
     border-radius: 5px;
     position: absolute;
     top: 50%;
     left: 70%;
     transform: translate(-50%,-50%);
     z-index: 99;

     transition: left .8s cubic-bezier(.31,1.91,.85,.15);
 }

 .login_btn, .signup_btn{
      display: inline-block;
      height: 3.5rem;
      width: 12rem;
      font-size: 2.1rem;
      background-color: transparent;
      color: white;
      border:1px solid white;
      border-radius: 5px;
      outline: none;
      position: absolute;
      left: 75%;
      top: 65%;
      transform: translate(-50%,-50%);
      transition:all .5s;
 }


 .signup_btn{
      left: 25%;
 }

 .login_btn:hover, .signup_btn:hover{
     background-color: white;
     color:#2d2d2d;
     cursor: pointer;
 }

 .login_msg, .signup_msg{
     font-family: arial;
     font-size: 3.5rem;
     color:white;
     position: absolute;
     top:35%;
     left:75%;
     transform: translate(-50%,-50%);
     z-index: 1;
 }
 .signup_msg{
     left: 25%;
 }
</style>
