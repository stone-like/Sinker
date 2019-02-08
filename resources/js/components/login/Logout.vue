<template>

</template>

<script>
import{mapActions} from "vuex"
export default {
    methods:{
        ...mapActions(['deleteToken','deleteId','deleteUser'])
    },
    created(){
       this.deleteToken();
       this.deleteUser();
       this.deleteId();
       localStorage.clear();
    //    this.$router.push({name:'forum'});
       window.location="/forum"
  }
}
</script>

<style>

</style>
