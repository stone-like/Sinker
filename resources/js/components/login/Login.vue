<template>
   <div class="loginform" :style="{left:log_left,visibility:log_visibility}">

    <h1>Login</h1>
    <v-text-field
    label="Email"
    v-model="form.email"
    type="email"
    required class="input">
    </v-text-field>

    <v-text-field
    label="Password"
    v-model="form.password"
    type="password"
    required class="input">
    </v-text-field>

    <button @click.prevent="login">Login</button>

    <!-- <router-link to="/signup">
       <button class="signup_btn">signup</button>
     </router-link> -->
  </div>
</template>

<script>
import User from "../../Helpers/User.js"
export default {
  props:['log_visibility','log_left'],
  data(){
      return{
          form:{
              email:null,
              password:null
          }
      }
  },
  methods:{
     login(){
         axios.post("api/auth/login",this.form)
        .then(res =>{
                  User.responseAfterLogin(res)
                  window.location="/forum"
                //   this.$router.push({name:'forum'})
                  })
        .catch(error => console.log(error.response.data))
   },
   changeSignup(){
       this.$store.dispatch("changeSignupFlag");
       console.log(this.$store.getters.getSignupFlag);
   }
  },
  created(){
      if(this.$store.getters.userLoggedIn){
           window.location="/forum"
        //   this.$router.push({name:'forum'});
      }
  }
}
</script>

<style lang="scss" scoped>
.loginform{
    height:45rem;
    width:50rem;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    position:absolute;
    top:50%;
    left:70%;
    transform:translate(-50%,-50%);
    z-index:999;
    visibility: visible;

    // transition: left .3s cubic-bezier(.31,1.91,.85,.15);
    transition: left .3s ease-in-out;

    h1{
        font-family: arial;
        font-size: 2.5rem;
        color:black;
    }

    .input{
        height: 4rem;
        width: 30rem;
        border: 0;
        outline: none;
    }

    button{
        height: 3.5rem;
        width: 13rem;
        background-color: aqua;
        font-family: monospace;
        font-size: 1.6rem;
        color: white;
        border: none;
        outline: none;
        border-radius: 5px;
        margin-top: 3rem;
        margin-left: 17.5rem;
    }
}

.v-input{
    flex:0 0 0;
}
</style>
