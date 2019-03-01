<template>
    <div v-if="question">
     <reply v-for="(reply,index) in content" :data="reply" :key="reply.id" :index=index class="mb-2"></reply>
    </div>
</template>

<script>
import Reply from "./Reply"
export default {
  props:['question'],
  data(){
      return{
       //repliesにpushしたいのだが直接いじるのはだめみたいなのでここに移す
       content:this.question.replies
      }
  },
  components:{
      Reply
  },
  created(){
    this.listen()
  },
  beforeDestroy(){
    this.$eventBus.$off("replydone")
  },
  methods:{
    listen(){
        this.$eventBus.$on("replydone",(reply) => {
            this.content.unshift(reply)
        })

        this.$eventBus.$on("deleteReply",(index) => {
             axios.delete("/api/question/"+this.question.slug+"/reply/"+this.content[index].id)
             .then(res =>{ this.content.splice(index,1)})
        })

        Echo.channel('addReplyChannel')
        .listen('AddReplyEvent',(e) => {
          if(this.question.id == e.question_id){
              this.content.unshift(e.reply);
          }
    });

    Echo.channel('deleteReplyChannel')
        .listen('DeleteReplyEvent',(e) => {
           //ある特定の質問のすべてのリプライをループでとってe.id、すなわちdeleteしたリプライのidと一緒ならそこのindexをリアルタイムdelete
           for(let index = 0;index<this.content.length;index++){
               if(this.content[index].id == e.id){
                   this.content.splice(index,1);
               }
           }
    });
    }
    // destroy(index){
    //    axios.delete("/api/question/"+this.question.slug+"/reply/"+this.content[index].id)
    //    .then(res =>{ this.content.splice(index,1)})

    // }
  }
}
</script>

<style>

</style>
