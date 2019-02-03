<template>
    　<v-container>

  <v-form @submit.prevent="signup">

    <v-text-field label="Name"
    v-model="form.name"
    type="text"
    required>
    </v-text-field>
    <span class="red--text" v-if="errors.name">{{errors.name[0]}}</span>

    <v-text-field label="E-mail"
    v-model="form.email"
    type="email"
    required>
    </v-text-field>
    <span class="red--text" v-if="errors.email">{{errors.email[0]}}</span>

     <v-text-field label="Password"
    v-model="form.password"
    type="password"
    required>
    </v-text-field>
    <span class="red--text" v-if="errors.password">{{errors.password[0]}}</span>

     <v-text-field label="Password"
    v-model="form.password_confirmation"
    type="password"
    required>
    </v-text-field>

    <v-btn color="green" type="submit">Signup</v-btn>

    <router-link to="/login">
       <v-btn flat color="blue">Login</v-btn>
     </router-link>
  </v-form>
</v-container>
</template>

<script>
import User from "../../Helpers/User.js"
export default {
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

      }
  },
  created(){
      if(this.$store.getters.userLoggedIn){
          this.$router.push({name:'forum'});
      }
  }
}
</script>

<style>

</style>
