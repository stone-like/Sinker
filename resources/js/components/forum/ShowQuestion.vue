<template>
    <v-card>
        <v-container fluid>
         <v-card-title>
            <div>
                <div class="headline">
                    {{data.title}}
                </div>
                <span class="grey--text">{{data.user}} said {{data.created_at}}</span>
            </div>
            <v-spacer></v-spacer>
            <v-btn color="teal">{{replyCount}} replies</v-btn>
         </v-card-title>

         <v-card-text v-html="body"></v-card-text>

         <v-card-actions v-if="own">
             <v-btn icon small @click="edit">
                 <v-icon color="blue">
                     edit
                 </v-icon>
             </v-btn>
             <v-btn icon small @click="destroy">
                 <v-icon color="black">
                     delete
                 </v-icon>
             </v-btn>
         </v-card-actions>
        </v-container>
    </v-card>
</template>

<script>
export default {
 props:['data'],
 data(){
     return{
          own:this.$store.getters.checkown(this.data.user_id),
          replyCount:this.data.reply_count//updateするならいじっていいけどこれは別にupdateじゃないのでpropsからこっちに移した
     }
 },
 computed:{
     body(){
        return md.parse(this.data.body);//dataのほうが変わったら即座に反映するためcomputed？
     }
 },
 created(){
     console.log(this.data);
   this.$eventBus.$on("replydone",() => {
             this.replyCount++
   })

   this.$eventBus.$on("deleteReply",() => {
             this.replyCount--
   })

    Echo.channel('addReplyChannel')
        .listen('AddReplyEvent',(e) => {
          if(this.data.question_id == e.question_id){
              this.replyCount++;
          }
        });

    Echo.channel('deleteReplyChannel')
        .listen('DeleteReplyEvent',(e) => {
           if(this.data.question_id == e.question_id){
               this.replyCount--;
           }
    });
 },
 methods:{
    destroy(){
        axios.delete("/api/question/"+this.data.slug)
        .then(res => this.$router.push("/forum"))
        .catch(error => console.log(error.response.data))
    },
    edit(){
        this.$emit("starteditting");
    }
 }
}
</script>

<style>

</style>
