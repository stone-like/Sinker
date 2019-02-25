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
            <button @click.prevent="AddOrDelete" >{{isBookmarked ? "deleteBookmark" : "addBookmark"}}</button>
            <div v-if="bookmark_flag&&!isBookmarked">
                <transition-group tag="ul" v-model="selected_bookmark">]
                    <li v-for="bookmark in bookmarks" :key="bookmark.id" @click.prevent="selected_bookmark=bookmark">          {{bookmark.name}}
                    </li>

                    <button class="to_button" @click="passBookmarkData" :disabled="comp_disable"  key="to_button" v-if="!isBookmarked">Add question to {{selected_bookmark.name}}</button>
                </transition-group>

            </div>
         </v-card-title>

         <v-card-text v-html="body"></v-card-text>

         <edit-tag :tags_array="tags" :question="data" v-if="editTag"></edit-tag>
         <app-tag :question="data" v-else></app-tag>

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
import AppTag from "./AppTag"
import EditTag from "./EditTag"
export default {
 components:{AppTag,EditTag},
 props:['data'],
 data(){
     return{
          own:this.$store.getters.checkown(this.data.user_id),
          replyCount:this.data.reply_count,//updateするならいじっていいけどこれは別にupdateじゃないのでpropsからこっちに移した
          editTag:false,
          tags:{},
          bookmark_flag:false,
          selected_bookmark:"",
          bookmarks:[],
          isBookmarked:false,
          task_id:""
     }
 },
 computed:{
     body(){
        return md.parse(this.data.body);//dataのほうが変わったら即座に反映するためcomputed？
     },
     comp_disable(){
         if(this.selected_bookmark === ""){
             return true;
         }else{
            return false;
         }
     }
 },
 created(){
    //  console.log(this.data);
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

    this.listen();
     this.getBookmark();
 },
 methods:{
    destroy(){
        axios.delete("/api/question/"+this.data.slug)
        .then(res => this.$router.push("/forum"))
        .catch(error => console.log(error.response.data))
    },
    edit(){
        this.$emit("starteditting");
    },
    listen(){
        this.$eventBus.$on("openEditTag",(from_tags) => {
            this.tags = from_tags//[anime,manbo,game]としたい

            this.editTag = true
        })

        this.$eventBus.$on("cancelEditTag",() => {
            this.editTag = false;
        })
    },
    AddOrDelete(){
         return this.isBookmarked ? this.deleteBookmark() : this.addBookmark();
    },
    deleteBookmark(){

         axios.delete('/api/task/'+this.task_id)
         .then(res => {
             //データベース上で削除するときにそれが属していたbookmarkの他のtaskのorderもしっかりいじる
             this.isBookmarked = false;
             //delete時にbookmarkのtaskのorderもいじらないといけない、例えば一番下に追加されるようにしているがそこから動かした場合は周りのorderをきちんといじってあげないとダメ
             //push notification
         })
         .catch(error => console.log(error.response.data))
    },
    addBookmark(){
        this.bookmark_flag = true;
    },
     getBookmark(){
            var self = this
           axios.get('/api/bookmark')
           .then(res => {
            //    console.log(res.data.data)
               this.bookmarks = res.data.data;

               this.bookmarks.forEach(function(bookmark){
                   bookmark.tasks.forEach(function(task){
                      if(task.title === self.data.title){
                          self.isBookmarked = true;
                          self.task_id = task.id;
                      }else{

                      }
                   })
               })
           })
           .catch(error => console.log(error.response.data))
        },
    passBookmarkData(){
        this.$eventBus.$emit('Bookmark_add_task',this.selected_bookmark,this.data.question_id);

         let bookmark_id = this.selected_bookmark.id
         let order = this.selected_bookmark.tasks.length
         let question_id = this.data.question_id

          axios.post('/api/task',{"bookmark_id":bookmark_id,"order":order,"question_id":question_id })
                .then(res => {
                    console.log(res)
                    this.isBookmarked = true;
                    this.task_id = res.data.id
                    this.bookmark_flag = false;
                    //push notification
                })
                .catch(error => console.log(error.response.data))
    }
 }
}
</script>

<style>

</style>
