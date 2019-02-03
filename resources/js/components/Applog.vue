<template>
   <router-view></router-view>
</template>

<script>
import Login from "./login/Login"
import Signup from "./login/Signup"
export default {
 components:{Login,Signup},
 created(){
     this.$router.push("/login")
 }
}
</script>

<style>

</style>
