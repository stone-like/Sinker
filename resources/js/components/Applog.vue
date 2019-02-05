<template>
 <div class="background_glitch imgloaded" v-if="isloading">
     <div class="glitch">
         <div class="glitch_img"></div>
         <div class="glitch_img"></div>
         <div class="glitch_img"></div>
         <div class="glitch_img"></div>
         <div class="glitch_img"></div>
     </div>

     <p class="content_text">Welcome to Sinker</p>
 </div>
 <div class="background" v-else>

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
         visible_flag:true,
         isloading:true

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

<style lang="scss">
@import url('https://fonts.googleapis.com/css?family=Geostar');
:root{
       //変数の設定
    --glitch-width:100vw;
    --glitch-height:100vh;
    --gap-horizontal:1rem;
    --gap-vertical:.5rem;
    --color-title:#fff;
    --time-anim:4s;
    --delay-anim:2s;
    --blend-mode-1:none;
    --blend-mode-2:none;
    --blend-mode-3:none;
    --blend-mode-4:overlay;
    --blend-mode-5:overlay;
    --blend-color-1:transparent;
    --blend-color-2:transparent;
    --blend-color-3:transparent;
    --blend-color-4:#fb909a;
    --blend-color-5:#101310;
}

.content_text{
    font-family: 'Geostar';
   color: white;
   font-size: 10rem;
   width: 120rem;
   position: absolute;
   top: 57%;
   left: 50%;
   transform: translate(-50%,-50%);


    white-space: nowrap;
    overflow: hidden;

   border-right: 2px solid rgba(255,255,255,.75);

   animation:typewriter 4s steps(44) 1s 1 normal both,
             blinkTextCursor .5s steps(44) infinite normal;
}

@keyframes typewriter{
    from{width:0;}
    to{width:120rem;}
}

@keyframes blinkTextCursor{
    from{border-right-color: rgba(255,255,255,.75);}
    to{border-right-color: transparent}
}

.background_glitch{
     //background自体の大きさ　not画像
     width: 100vw;
     height: 100vh;
}

.glitch{
    position:absolute;
    top: 0;
    left: 0;
    width: var(--glitch-width);
    height: var(--glitch-height);
    overflow: hidden;
}

.glitch_img{
    position: absolute;
    top: calc(-1*var(--gap-vertical));
    left: calc(-1*var(--gap-horizontal));
    width: calc(100% + var(--gap-horizontal)*2);
    height: calc(100% + var(--gap-vertical)*2);
    background: url("../Helpers/img/abstract.jpg") no-repeat 50% 0;
    background-size: cover;
    background-color: var(--blend-color-1); //ここはnoneなので何の意味もなさないように感じるがこれはanimationで変化させるから初期値として必要
    transform: translate3d(0,0,0);//初期値
    background-blend-mode: var(--blend-mode-1);//初期値
}

.glitch_img:nth-child(n+2){
    opacity: 0;
}

///ここからload時のglitch
.imgloaded .glitch_img:nth-child(n+2){
    animation-duration: var(--time-anim);
    animation-delay: var(--delay-anim);
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}
.imgloaded .glitch_img:nth-child(2){
    background-color: var(--blend-color-2);
    background-blend-mode:var(--blend-mode-2);
    animation-name:glitch-anim-1;
}

.imgloaded .glitch_img:nth-child(3){
    background-color: var(--blend-color-3);
    background-blend-mode:var(--blend-mode-3);
    animation-name:glitch-anim-2;
}

.imgloaded .glitch_img:nth-child(4){
    background-color: var(--blend-color-4);
    background-blend-mode:var(--blend-mode-4);
    animation-name:glitch-anim-3;
}

.imgloaded .glitch_img:nth-child(5){
    background-color: var(--blend-color-5);
    background-blend-mode:var(--blend-mode-5);
    animation-name:glitch-anim-flash;
}

//実際のanimation
@keyframes glitch-anim-1{
    0%{
        opacity: 1;
        transform: translate3d(var(--gap-horizontal),0,0);
        clip-path: polygon(0 2%,100% 2%,100% 5%, 0 5%);
    }
    2%{
        clip-path: polygon(0 15%,100% 15%,100% 15%,0 15%);
    }
    4%{
        clip-path: polygon(0 10%,100% 10%,100% 20%,0 20%);
    }
    6%{
        clip-path: polygon(0 1%,100% 1%,100% 2%,0 2%);
    }
    8%{
         clip-path: polygon(0 33%,100% 33%,100% 33%,0 33%);
    }
    10%{
         clip-path: polygon(0 44%,100% 44%,100% 44%,0 44%);
    }
    12%{
         clip-path: polygon(0 50%,100% 50%,100% 20%,0 20%);
    }
    14%{
         clip-path: polygon(0 70%,100% 70%,100% 70%,0 70%);
    }
    16%{
         clip-path: polygon(0 80%,100% 80%,100% 80%,0 80%);
    }
    18%{
         clip-path: polygon(0 50%,100% 50%,100% 55%,0 55%);
    }
    20%{
         clip-path: polygon(0 70%,100% 70%,100% 80%,0 80%);
    }
    21.9%{
        opacity: 1;
        transform: translate3d(var(--gap-horizontal),0,0);
    }
    22%, 100%{
    opacity: 0;
    transform: translate3d(0,0,0);
    clip-path: polygon(0 0,0 0,0 0,0 0);
    }
}

@keyframes glitch-anim-2 {
    0% {
        opacity: 1;
        transform: translate3d(calc(-1 * var(--gap-horizontal)),0,0);
        -webkit-clip-path: polygon(0 25%, 100% 25%, 100% 30%, 0 30%);
        clip-path: polygon(0 25%, 100% 25%, 100% 30%, 0 30%);
    }
    3% {
        -webkit-clip-path: polygon(0 3%, 100% 3%, 100% 3%, 0 3%);
        clip-path: polygon(0 3%, 100% 3%, 100% 3%, 0 3%);
    }
    5% {
        -webkit-clip-path: polygon(0 5%, 100% 5%, 100% 20%, 0 20%);
        clip-path: polygon(0 5%, 100% 5%, 100% 20%, 0 20%);
    }
    7% {
        -webkit-clip-path: polygon(0 20%, 100% 20%, 100% 20%, 0 20%);
        clip-path: polygon(0 20%, 100% 20%, 100% 20%, 0 20%);
    }
    9% {
        -webkit-clip-path: polygon(0 40%, 100% 40%, 100% 40%, 0 40%);
        clip-path: polygon(0 40%, 100% 40%, 100% 40%, 0 40%);
    }
    11% {
        -webkit-clip-path: polygon(0 52%, 100% 52%, 100% 59%, 0 59%);
        clip-path: polygon(0 52%, 100% 52%, 100% 59%, 0 59%);
    }
    13% {
        -webkit-clip-path: polygon(0 60%, 100% 60%, 100% 60%, 0 60%);
        clip-path: polygon(0 60%, 100% 60%, 100% 60%, 0 60%);
    }
    15% {
        -webkit-clip-path: polygon(0 75%, 100% 75%, 100% 75%, 0 75%);
        clip-path: polygon(0 75%, 100% 75%, 100% 75%, 0 75%);
    }
    17% {
        -webkit-clip-path: polygon(0 65%, 100% 65%, 100% 40%, 0 40%);
        clip-path: polygon(0 65%, 100% 65%, 100% 40%, 0 40%);
    }
    19% {
        -webkit-clip-path: polygon(0 45%, 100% 45%, 100% 50%, 0 50%);
        clip-path: polygon(0 45%, 100% 45%, 100% 50%, 0 50%);
    }
    20% {
        -webkit-clip-path: polygon(0 14%, 100% 14%, 100% 33%, 0 33%);
        clip-path: polygon(0 14%, 100% 14%, 100% 33%, 0 33%);
    }
    21.9% {
        opacity: 1;
        transform: translate3d(calc(-1 * var(--gap-horizontal)),0,0);
    }
    22%, 100% {
        opacity: 0;
        transform: translate3d(0,0,0);
        -webkit-clip-path: polygon(0 0, 0 0, 0 0, 0 0);
        clip-path: polygon(0 0, 0 0, 0 0, 0 0);
    }
}

@keyframes glitch-anim-3 {
    0% {
        opacity: 1;
        transform: translate3d(0, calc(-1 * var(--gap-vertical)), 0) scale3d(-1,-1,1);
        -webkit-clip-path: polygon(0 1%, 100% 1%, 100% 3%, 0 3%);
        clip-path: polygon(0 1%, 100% 1%, 100% 3%, 0 3%);
    }
    1.5% {
        -webkit-clip-path: polygon(0 10%, 100% 10%, 100% 9%, 0 9%);
        clip-path: polygon(0 10%, 100% 10%, 100% 9%, 0 9%);
    }
    2% {
        -webkit-clip-path: polygon(0 5%, 100% 5%, 100% 6%, 0 6%);
        clip-path: polygon(0 5%, 100% 5%, 100% 6%, 0 6%);
    }
    2.5% {
        -webkit-clip-path: polygon(0 20%, 100% 20%, 100% 20%, 0 20%);
        clip-path: polygon(0 20%, 100% 20%, 100% 20%, 0 20%);
    }
    3% {
        -webkit-clip-path: polygon(0 10%, 100% 10%, 100% 10%, 0 10%);
        clip-path: polygon(0 10%, 100% 10%, 100% 10%, 0 10%);
    }
    5% {
        -webkit-clip-path: polygon(0 30%, 100% 30%, 100% 25%, 0 25%);
        clip-path: polygon(0 30%, 100% 30%, 100% 25%, 0 25%);
    }
    5.5% {
        -webkit-clip-path: polygon(0 15%, 100% 15%, 100% 16%, 0 16%);
        clip-path: polygon(0 15%, 100% 15%, 100% 16%, 0 16%);
    }
    7% {
        -webkit-clip-path: polygon(0 40%, 100% 40%, 100% 39%, 0 39%);
        clip-path: polygon(0 40%, 100% 40%, 100% 39%, 0 39%);
    }
    8% {
        -webkit-clip-path: polygon(0 20%, 100% 20%, 100% 21%, 0 21%);
        clip-path: polygon(0 20%, 100% 20%, 100% 21%, 0 21%);
    }
    9% {
        -webkit-clip-path: polygon(0 60%, 100% 60%, 100% 55%, 0 55%);
        clip-path: polygon(0 60%, 100% 60%, 100% 55%, 0 55%);
    }
    10.5% {
        -webkit-clip-path: polygon(0 30%, 100% 30%, 100% 31%, 0 31%);
        clip-path: polygon(0 30%, 100% 30%, 100% 31%, 0 31%);
    }
    11% {
        -webkit-clip-path: polygon(0 70%, 100% 70%, 100% 69%, 0 69%);
        clip-path: polygon(0 70%, 100% 70%, 100% 69%, 0 69%);
    }
    13% {
        -webkit-clip-path: polygon(0 40%, 100% 40%, 100% 41%, 0 41%);
        clip-path: polygon(0 40%, 100% 40%, 100% 41%, 0 41%);
    }
    14% {
        -webkit-clip-path: polygon(0 80%, 100% 80%, 100% 75%, 0 75%);
        clip-path: polygon(0 80%, 100% 80%, 100% 75%, 0 75%);
    }
    14.5% {
        -webkit-clip-path: polygon(0 50%, 100% 50%, 100% 51%, 0 51%);
        clip-path: polygon(0 50%, 100% 50%, 100% 51%, 0 51%);
    }
    15% {
        -webkit-clip-path: polygon(0 90%, 100% 90%, 100% 90%, 0 90%);
        clip-path: polygon(0 90%, 100% 90%, 100% 90%, 0 90%);
    }
    16% {
        -webkit-clip-path: polygon(0 60%, 100% 60%, 100% 60%, 0 60%);
        clip-path: polygon(0 60%, 100% 60%, 100% 60%, 0 60%);
    }
    18% {
        -webkit-clip-path: polygon(0 100%, 100% 100%, 100% 99%, 0 99%);
        clip-path: polygon(0 100%, 100% 100%, 100% 99%, 0 99%);
    }
    20% {
        -webkit-clip-path: polygon(0 70%, 100% 70%, 100% 71%, 0 71%);
        clip-path: polygon(0 70%, 100% 70%, 100% 71%, 0 71%);
    }
    21.9% {
        opacity: 1;
        transform: translate3d(0, calc(-1 * var(--gap-vertical)), 0) scale3d(-1,-1,1);
    }
    22%, 100% {
        opacity: 0;
        transform: translate3d(0,0,0);
        -webkit-clip-path: polygon(0 0, 0 0, 0 0, 0 0);
        clip-path: polygon(0 0, 0 0, 0 0, 0 0);
    }
}

/* Flash */
@keyframes glitch-anim-flash {
    0%, 5% {
        opacity: 0.2;
        transform: translate3d(var(--gap-horizontal), var(--gap-vertical), 0);
    }
    5.5%, 100% {
        opacity: 0;
        transform: translate3d(0, 0, 0);
    }
}


.background{
    width: 100%;
    height: 100%;
    background-image:url('../Helpers/img/abstract.jpg');
    background-size: cover;
    filter:brightness(1.2);
    position: relative;
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
