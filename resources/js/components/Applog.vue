<template>
  <div class="box">

         <div class="main" :style="{left:left}"></div>
         <login :log_visibility="log_visibility" :log_left="log_left"></login>

         <signup :signup_visibility="signup_visibility" :signup_left="signup_left"></signup>

         <div class="login_msg">Have an account?</div>
         <div class="signup_msg">Don't have an account?</div>


         <button class="login_btn" @click="bounce_right">Login</button>

         <button class="signup_btn" @click="bounce_left">signup</button>
  </div>
</template>

<script>
import Login from "./login/Login"
import Signup from "./login/Signup"
export default {
 data(){
     return{
         left:'70%',
         log_left:"",
         log_visibility:"",
         signup_left:"",
         signup_visibility:""

     }
 },
 components:{Login,Signup},
 created(){
     this.$router.push("/login")
 },
 methods:{
     bounce_left(){
        this.left = "30%";

        this.log_left = "25%";
        this.log_visibility = "hidden";

        this.signup_left = "30%";
        this.signup_visibility = "visible";
     },
     bounce_right(){
         this.left = "70%";

         this.log_left = "70%";
         this.log_visibility = "visible";

         this.signup_left = "75%";
         this.signup_visibility = "hidden";
     }
 }
//  computed:{
//      signup(){
//          return this.$store.getters.getSignupFlag;
//      }
//  }
}
</script>

<style lang="scss" scoped>


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
     height: 60rem;
     width: 67.5rem;
     background-color: white;
     border-radius: 5px;
     position: absolute;
     top: 50%;
     left: 70%;
     transform: translate(-50%,-50%);
     z-index: 99;

     transition: left .3s cubic-bezier(.31,1.91,.85,.15);
 }

 .login_btn, .signup_btn{
      height:5.2rem;
      width: 18rem;
      font-size: 2.1rem;
      background-color: transparent;
      color: white;
      border: 1px solid white;
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
