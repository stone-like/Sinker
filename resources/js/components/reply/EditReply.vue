<template>
   <div>
    <markdown-editor v-model="reply.reply"></markdown-editor>

    <v-btn icon small @click="update">
        <v-icon color="blue">save</v-icon>
    </v-btn>

    <v-btn icon small @click="cancel">
        <v-icon color="black">cancel</v-icon>
    </v-btn>

   </div>
</template>

<script>
export default {
    //わかりにくいがpropで持ってきたのはreply一つ分の全部でreply.replyはreplyの本文,ここpropsいじってるけどupdateのところだからいいのか・・・？
  props:['reply'],
  methods:{
      cancel(){
          this.$emit("canceleditting")
      },
      update(){
          //ここではquestionのslugは入手できていないし、親にも存在しない、vuexを使うしかないか？と思っていたがreplyresourceの返り値でreplyからquestionをたどればいい
          axios.patch("/api/question/"+this.reply.question_slug+"/reply/"+this.reply.id,{body:this.reply.reply})
          .then(res => this.cancel())
      }
  }
}
</script>

<style>

</style>
