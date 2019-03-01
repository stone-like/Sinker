<template>
        <div class="question_wrapper">
         <div class="upper_wrapper">
            <div class="upper_title_wrapper">
                <div class="title_shape">
                </div>
                <div class="headline">
                    {{data.title}}
                </div>
            </div>
            <div class="reply_section">{{replyCount}} replies</div>

            <div class="AddOrDeleteContainer">

            <button @click.prevent="AddOrDelete" class="bookmark_botton">{{isBookmarked ? "deleteBookmark" : "addBookmark"}}</button>
            <div v-if="bookmark_flag&&!isBookmarked">
                <transition-group tag="ul" v-model="selected_bookmark" class="bookmark_ul">]
                    <li v-for="bookmark in bookmarks" :key="bookmark.id" @click.prevent="selected_bookmark=bookmark" class="bookmark_li">          {{bookmark.name}}
                    </li>

                    <button class="to_button" @click="passBookmarkData" :disabled="comp_disable"  key="to_button" v-if="!isBookmarked">Add question to {{selected_bookmark.name}}</button>
                </transition-group>
            </div>
            </div>
         </div>

         <div class="question_body" v-html="body"></div>

         <edit-tag :tags_array="tags" :question="data" v-if="editTag"></edit-tag>
         <app-tag :question="data" v-else></app-tag>

         <div class="question_footer_wrapper">
             <div class="edit_destroy_wrapper">

                 <v-card-actions v-if="own">
                    <v-btn icon small @click="edit">
                       <v-icon color="#CD664D">
                         edit
                       </v-icon>
                    </v-btn>
                    <v-btn icon small @click="destroy">
                        <v-icon color="#363636">
                            delete
                        </v-icon>
                     </v-btn>
                 </v-card-actions>
             </div>

           <div class="user_profile">
                <div class="image_wrapper">
                    <img :src="data.user_img" class="image_content">
                </div>
                <div class="user_name">
                     {{data.user}}
                </div>
            </div>

         </div>
        </div>
</template>

<script>
import AppTag from "./AppTag"
import EditTag from "./EditTag"
import BookmarkModal from "../bookmark/BookmarkModal"
export default {
 components:{AppTag,EditTag,BookmarkModal},
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

        this.$eventBus.$on('startDeleting',(deletetype) => {
               if(deletetype === "task"){

                    axios.delete('/api/task/'+this.task_id)
         .then(res => {
             //データベース上で削除するときにそれが属していたbookmarkの他のtaskのorderもしっかりいじる
             this.isBookmarked = false;
             //delete時にbookmarkのtaskのorderもいじらないといけない、例えば一番下に追加されるようにしているがそこから動かした場合は周りのorderをきちんといじってあげないとダメ
             //push notification
         })
         .catch(error => console.log(error.response.data))
               }
           })

        this.$eventBus.$on("startAddTask",(extracted_bookmark)      => {

        this.$eventBus.$emit('Bookmark_add_task',extracted_bookmark,this.data.question_id);

         let bookmark_id = extracted_bookmark.id
         let order = extracted_bookmark.tasks.length
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

           })
    },
    AddOrDelete(){
         return this.isBookmarked ? this.deleteBookmark() : this.addBookmark();
    },
    deleteBookmark(){
          this.$store.dispatch("changeModalFlag",["Notification","If you click delete,tasks inside bookmark vanish are you sure to delete?","delete_task"])
        //  axios.delete('/api/task/'+this.task_id)
        //  .then(res => {
        //      //データベース上で削除するときにそれが属していたbookmarkの他のtaskのorderもしっかりいじる
        //      this.isBookmarked = false;
        //      //delete時にbookmarkのtaskのorderもいじらないといけない、例えば一番下に追加されるようにしているがそこから動かした場合は周りのorderをきちんといじってあげないとダメ
        //      //push notification
        //  })
        //  .catch(error => console.log(error.response.data))
    },
    // extractModal(){
    //     let self = this;
    //    return new Promise((resolve,reject) => {
    //      const addModalConstructor = Vue.extend(BookmarkModal);//コンストラクタ化

    //     const vm = new addModalConstructor({
    //         propsData:{
    //             bookmarks:self.bookmarks,
    //             resolve:resolve
    //         }
    //     });//インスタンスを生成
    //     vm.$mount();//一旦マウントして
    //     document.getElementById('app').appendChild(vm.$el);//appの子として追加する
    //    })
    // },
    addBookmark(){
        let self = this;
        // this.bookmark_flag = true;
        //新しくmodalを作る(これはselectしなきゃいけないし、今までのmodalとは違うので)
        // let addModal = new BookmarkModal().$mount();
        //もう上のほうでcomponentとして BookmarkModalをインスタンスとして登録してあるので使用するときは BookmarkModalでいい
        //↑は間違いで<component>としたときにインスタンスが生成されているのであった
        // const result = await this.extractModal();
        const addModalConstructor = Vue.extend(BookmarkModal);//コンストラクタ化

        const vm = new addModalConstructor({
            propsData:{
                bookmarks:self.bookmarks
                // success:(string) => resolve(string),
                // failure:(string) => resolve(string)
            }
        });//インスタンスを生成
        vm.$mount();//一旦マウントして
        document.getElementById('app').appendChild(vm.$el);//appの子として追加する
        // vm.pop()
        // .then(res => {
        //     console.log(res)
        //     })
        // .catch(error => console.log(error.response.data))
        //thenに入る前にmodal側で破棄？
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

<style lang="scss" scoped>
 .question_wrapper{
     display: flex;
     flex-direction: column;
     border:1px solid black;
     padding: 0 0 0 3rem;
 }

 .upper_wrapper{
     margin-top: 2rem;
     display: flex;
     align-items: center;
 }

 .upper_title_wrapper{
   display: flex;
   margin-right: auto;
//    margin-left: 3rem;
   background-color: #4B483F;
   padding: 0.5rem 1rem;
   align-items: center;
 }

 .title_shape{
     width: 3rem;
     height: 3rem;
     background-color: #EDE9E3;
     margin-right: 2rem;
 }
 .headline{
     color: #EDE9E3;
 }

 .bookmark_botton{
     margin-right: 6rem;
 }

 .AddOrDeleteContainer{
     display: flex;
     flex-direction: column;
 }

 .bookmark_ul{
   list-style: none;
 }

 .bookmark_li{

 }

 .reply_section{
     background-color:  #B4AF9A;
     color: #4E4B42;
     font-size: 2rem;
     padding: 1rem 2rem;
     border-radius: 5px;
     box-shadow: 1px 1px 1px 0 #333;
     margin-right: 1rem;

 }

 .question_body{
     margin-top: 2rem;
     font-size: 2rem;
     color: #363636;
 }

 .image_wrapper{
   width:7rem;
   height: 7rem;
   border-radius: 50%;
   display: flex;
   justify-content: center;
   align-items: center;
}

.image_content{
     width:5rem;
   height: 5rem;
   border-radius: 50%;
   object-fit: cover;
}

.user_profile{
    display: flex;
    align-items: center;
    margin-right:6rem;

}

.user_name{
    margin-left: 1.5rem;
  font-size: 2rem;
  color: #363636;
}

.question_footer_wrapper{
    display: flex;
    align-items: center;
}

.edit_destroy_wrapper{
    margin-right: auto;
}
</style>
