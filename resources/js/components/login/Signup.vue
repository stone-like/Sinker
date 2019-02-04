<template>
   <div class="signupform" :style="{left:signup_left,visibility:signup_visibility}">

    <h1>Signup</h1>

    <v-text-field
    label="Name"
    v-model="form.name"
    type="text"
    required class="input">
    </v-text-field>
    <span class="red--text" v-if="errors.name">{{errors.name[0]}}</span>

    <v-text-field
    label="Email"
    v-model="form.email"
    type="email"
    required class="input">
    </v-text-field>
    <span class="red--text" v-if="errors.email">{{errors.email[0]}}</span>

     <v-text-field
     label="Password"
    v-model="form.password"
    type="password"
    required class="input">
     </v-text-field>
    <span class="red--text" v-if="errors.password">{{errors.password[0]}}</span>

     <v-text-field
     label="Confirm password"
    v-model="form.password_confirmation"
    type="password"
    required class="input">
     </v-text-field>

    <button @click.prevent="signup">Signup</button>

    <!-- <router-link to="/login">
       <button class="login_btn">Login</button>
     </router-link> -->

   </div>
</template>

<script>
import User from "../../Helpers/User.js"
export default {
  props:['signup_visibility','signup_left'],
  data(){
      return{
          form:{
              name:null,
              email:null,
              password:null,
              password_confirmation:null//ここはlaravelのvalidationでconfirmを使うために_confirmationとしなければだめ
          },
          errors:[]
      }
  },
  methods:{
      signup(){
          var self = this
         axios.post("/api/auth/signup",this.form)
         .then(res =>{
            User.responseAfterLogin(res)
            window.location="/forum"
            // self.$router.push({name:'forum'})
         })
         .catch(error => self.errors = error.response.data.errors)//ここからerrors['email']とかでアクセス、各一番目をとっていけばいいのでerrors['email'][0]

      },
      changeSignup(){
       this.$store.dispatch("changeSignupFlag");
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
.signupform{
    height:45rem;
    width:50rem;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    position:absolute;
    top:45%;
    left:75%;
    transform:translate(-50%,-50%);
    z-index:999;
    visibility: hidden;

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
