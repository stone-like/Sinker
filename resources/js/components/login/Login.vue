<template>
<v-app>
　<v-container>

  <v-form @submit.prevent="login">

    <v-text-field label="E-mail"
    v-model="form.email"
    type="email"
    required>
    </v-text-field>

    <v-text-field label="Password"
    v-model="form.password"
    type="password"
    required>
    </v-text-field>

    <v-btn color="green" type="submit">Login</v-btn>

    <router-link to="/signup">
       <v-btn flat>signup</v-btn>
     </router-link>
  </v-form>
</v-container>
</v-app>　
</template>

<script>
import User from "../../Helpers/User.js"
export default {
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
                  this.$router.push({name:'forum'})
                  })
        .catch(error => console.log(error.response.data))
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
