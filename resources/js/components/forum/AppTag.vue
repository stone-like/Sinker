<template>
  <div class="tag_wrapper">
      <span class="tag_text">Tags:</span>
      <ul class="tag_list">
          <li v-for="tag in displaytags" :key="tag.id" class="tag_item" @click="pushToSearch(tag.name)">{{tag.name}}</li>
      </ul>
      <v-btn @click="startEditTag" v-if="isQualified" class="tag_edit_button"> edit tag</v-btn>
  </div>
</template>

<script>
export default {
    data(){
        return{
          tags:{},//リアルタイムで変更するときはここに手を加える
          tags_name_array:[],
          qualify:false
        }
    },
   props:['question'],
   created(){
       this.getTags()

       this.listen()
   },
   methods:{
      pushToSearch(name){
         //やることは3つでsearchに飛ぶようにしてあげる
         //searchboxをoffに
         //飛ぶ前に検索ワードにtagの名前、検索形式をtagにするためにvuexに書き込む
         let mode_main = "tags"
         let keywords = name
          this.$store.commit("setKeywords",{keywords})
          this.$store.commit("setModeMain",{mode_main})
          this.$store.dispatch('setSearchBoxFlag',false)

          this.$router.push("/search")

      },
      getTags(){
          axios.get("/api/"+this.question.slug+"/tag")
          .then(res => {
                          this.tags = res.data
                          console.log(res.data)                })
          .catch(error => console.log(error.response.data))

      },
      startEditTag(){
          var self = this;
          //tagsから名前を取り出して配列に詰める
             self.tags.forEach(function(tag){
                self.tags_name_array.push(tag.name);//ここをそのままthisとしてしまうとthisがさすところが予想通りいかないのでself
             })
          self.$eventBus.$emit("openEditTag",this.tags_name_array)
          },
          listen(){
              var self = this;
               Echo.channel('editTagChannel')
             .listen('EditTagEvent',(e) => {
                 //配列に直接入れても感知してくれないがcomputedであれば感知できる
                 this.tags = e.tags_array;//eからeventで作ったpublicにアクセスしていく
           })

          }

   },
   computed:{
       isQualified(){
           //質問の投稿者かauthoricatedのひとだけ
           if(this.$store.getters.checkown(this.question.user_id) || this.$store.getters.checkadmin){
               this.qualify = true;
           }else{
               this.qualify = false;
           }
           return this.qualify;
       },
       displaytags(){

           return this.tags;
       }
   }
}
</script>

<style lang="scss" scoped>
.tag_list{
    list-style: none;
    display: flex;
}
.tag_item{
    &:not(:last-child){
        margin-right: 1.4rem;
    }
    cursor: pointer;
}

.tag_wrapper{
    display: flex;
    align-items: center;
    font-size: 1.3rem;
}
</style>
