<template>
 <div class="mt-2">
  <markdown-editor v-model="body"></markdown-editor>
  <v-btn dark color="teal" @click="submit">
     Reply
  </v-btn>

 </div>
</template>

<script>
import Appstorage from "../../Helpers/AppStorage.js"
export default {
 props:['questionSlug'],
 data(){
     return{
         body:null
     }
 },
 methods:{
     submit(){//bodyだけ返してあとのuser_idとかquestion_idはlaravel側で
         axios.post("/api/question/"+this.questionSlug+"/reply",{body:this.body,user_id:this.$store.getters.getId})
         .then(res => {
             this.body = ""
             this.$eventBus.$emit("replydone",res.data.reply)
             window.scrollTo(0,0)//一番上へ移動
         })
     }
 }
}
</script>

<style>

</style>
