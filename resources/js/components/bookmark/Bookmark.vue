<template>
  <div class="entire_wrapper">
      <!-- <draggable element="div" class="entire_draggable" v-model="bookmarks" :options="dragOptions"> -->
          <transition-group tag="div" class="entire_transition">
               <div class="outer_bookmark" v-for="(element,index) in bookmarks" :key="element.id">
                 <div class="tasks">

                     <div class="header_wrapper">
                     <div class="task_header">
                         <h4 class="task_title">{{element.name}}</h4>
                     </div>

                     <button class="delete_button" @click.prevent="deleteBookmark(element.id,index)">delete</button>
                     </div>

                     <div class="task_body">
                        <draggable :options="dragOptions" element="div" @end="changeOrder" v-model="element.tasks">
                            <transition-group :id="element.id" tag="div" class="small_transition">
                               <div v-for="task in element.tasks" :key="task.bookmark_id+','+task.order" class="single_task" :id="task.id">
                                   <router-link :to="task.path">
                                     {{task.title}}
                                  </router-link>
                               </div>
                            </transition-group>

                        </draggable>
                     </div>
                 </div>
               </div>
          </transition-group>

　　　　　　<input type="text" v-model="addname">
          <button @click.prevent="addNewList">addNewList</button>

      <!-- </draggable> -->

  </div>
</template>

<script>
import draggable from 'vuedraggable'
export default {
    components: {
             draggable
         },
    data(){
      return{
          bookmarks:[],
          addname:""
        //   tasks:[]
      }
    },
    created(){
        // this.listen();

      this.getBookmark();

    },
    methods:{
        // listen(){

        // this.$eventBus.$on('Bookmark_add_task',(bookmark,question_id) => {
        //         console.log("mmmmmmmmmmmm")
        //         console.log(bookmark,question_id)
        //       //taskを追加するときはbookmark_id、order、question_idが必要でquestion_idはemitから持ってきて、bookmark_idもemit、orderはbookmark_idからそのbookmarkのtaskの数+1
        //       let bookmark_id = bookmark.id
        //       let order = bookmark.tasks.length
        //     //   let question_id = question_id

        //         axios.post('/api/task',{"bookmark_id":bookmark_id,"order":order,"question_id":question_id })
        //         .then(res => {
        //               let id = res.data.bookmark_id
        //               this.bookmarks.foreach(function(bookmark){
        //                   if(bookmark.id === id){
        //                       bookmark.tasks.push(res.data)
        //                   }else{}
        //               })
        //         })
        //         .catch(error => console.log(error.response.data))
        //   })
        // },
        deleteBookmark(element_id,index){
             axios.delete('/api/bookmark/'+element_id)
             .then(res => {
                 this.bookmarks.splice(index,1);
             })
             .catch(error => error.response.data)
        },
        // sortArrays(array){
        //     console.log(_.orderBy(array,'order','asc')
        //     )
        //    return _.orderBy(array,'order','asc');
        //    //なんかおかしくなってしまうとおもったらこのsort自体はいいんだけど変更前のorderが適用されてしまう例えば移動前のorderが1とかで変更後のorder0として変更後のorder0でsortされてほしいのに変更前のorder1でsortされてしまう
        //   //それならcomputedで変化したあとに適用すればいい？
        //   //あとは選択ミス?みたいなバグもある、AをつかんだのにBをつかんだことになって、それでもデータ上はAを移動したことになっている・・・

        // },
        addNewList(){

            axios.post('/api/bookmark',{"name":this.addname})
            .then(res => {
                //tasksは空でよくてあとのidとnameをthis.bookmarksにpush
                console.log(res)
                this.bookmarks.push(res.data.data)
                this.addname=""
            })
        },
        getBookmark(){
            var self = this
           axios.get('/api/bookmark')
           .then(res => {
               console.log(res.data.data)
               this.bookmarks = res.data.data;
            //    this.bookmarks.foreach(function(bookmark){
            //        self.tasks.push(bookmark.tasks)
            //    })
           })
           .catch(error => console.log(error.response.data))
        },
        changeOrder(data){
            //draggable特有のものでそこから返ってくるdataからto,fromを取り出す、toはどにいったか、fromはどこからか
            // console.log(data.to)//変更後の列htmlの情報
            // console.log(data.from)//変更後の列htmlの情報
            //fromからtoに移動したとして移動前fromが3つ,
            //toが2つだとしたら
            //移動後はfromが二つ、toが3つになる
            // console.log(data.item)//これは動かした奴の情報,情報というかただの動かした枠のhtmlの情報、別にphpのobjectじゃない、なのでhtmlから情報をとるため、あらかじめidをセットしておく、そこからupdate
            let toTask = data.to
            let fromTask = data.from
            let task_id = data.item.id
            //これだと動かしたものしかorderが変わらなくて他のが変わらないとダメなんじゃ...要素が二つだとしても１から0に片方が行くならもう片方が0から1にならなくてはいけないし...
            let fromTask_id = fromTask.id;
            let toTask_id = toTask.id;

            //bookmark自体が変わる(横移動)
            //あらかじめセットしてあったhtmlの情報を使う
            let bookmark_id = fromTask.id == toTask.id ? fromTask.id : toTask.id

            //orderが変わる(縦移動)
            let order = data.newIndex == data.oldIndex ? false : data.newIndex
            console.log(data.newIndex,data.oldIndex)
            let newPosition = data.newIndex
            let oldPosition = data.oldIndex

            //やっぱりこれ欠陥品だと思う、横移動でもorderが変わらない場合もあるから正しくはどっちも変わらない場合動かず、どちらか一つでも変われば動くだと思う
            //そこそこいいんだけど上の問題に加えorderの重複問題が残っている
            //さらにorder順に並んでいないというのも問題
            //order順に並べるにはcomputedを使って:key順にしてあげればよくて、重複問題は移動したときに元の所では自分より下のtaskを-1、移動先では自分より下のtaskを+1してあげればいいはず,でもそれだとめちゃくちゃアップデートがいる気が...foreachでやるのが確実だろうけどupdate回数多すぎない？,なのでaxios呼び出し回数を1回にしてその中でupdateをやるとか

            //やることはfromTask.idのbookmark.idで動いたやつより下のorderを-1、
            //toTask.idのbookmark.idで動いたやつより下のorderを+1

            if(order === data.newIndex || bookmark_id === toTask.id){
                //bookmark_idがnullの時nullで上書きされないか、とおもったがやっぱりされてたので
                axios.patch('/api/task/'+task_id,{order,bookmark_id,fromTask_id,toTask_id,newPosition,oldPosition})
                .then(res => {

                })
                .catch(error => error.response.data)
            }
        }
    },
    computed:{
        // comp_Bookmark(){
        //      this.bookmarks.foreach(function(bookmark){
        //         bookmark.tasks.sort((a,b) => {
        //             return (a.order < b.order) ? -1 :(a.order < b.order) ? 1 :0;
        //         })
        //     })

        //     return this.bookmarks;
        // },
        dragOptions(){
            return{
                animation:1,
                group:'description',
                ghostClass:'ghost'
            }
        }
        //  sortArrays(){
        //    return element.tasks.sort((a,b) => {
        //        return (a.order < b.order) ? -1 :(a.order < b.order) ? 1 :0;
        //    });
        //    //なんかおかしくなってしまうとおもったらこのsort自体はいいんだけど変更前のorderが適用されてしまう例えば移動前のorderが1とかで変更後のorder0として変更後のorder0でsortされてほしいのに変更前のorder1でsortされてしまう
        //   //それならcomputedで変化したあとに適用すればいい？
        // }
    },
    beforeRouteEnter(to,from,next){
      next(vm => {
          var  self = vm;
          //これでgridを全域にまで伸ばす
          self.$eventBus.$emit("changeGridUser",true)
      })
    },
   beforeRouteLeave(to,from,next){

      this.$eventBus.$emit("changeGridUser",false)
      next()

   }
}
</script>

<style lang="scss" scoped>
$background-color: #EDE9E3;

.entire_wrapper{
    height: 100%;
    width: 100%;
    background-color: $background-color;

}

.entire_draggable{
     height: 100%;
    width: 100%;
}

.entire_transition{
    display:grid;
    margin-left:25rem;
    padding:15rem 15rem 15rem 10rem;
    grid-template-columns: repeat(auto-fit,minmax(10rem,1fr));
    grid-row-gap: 1rem;
    grid-column-gap: 1rem;

    grid-auto-rows:minmax(min-content,max-content);

}

.tasks{
    width: 100%;
    height: 100%;
    padding: 2rem 5rem 5rem 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.task_header{
     font-size: 3rem;
     margin-bottom: 2rem;
}

.task_body{
    height: 70%;
    width: 70%;
}

.single_task{
    height: 5rem;
    width: 100%;
     margin-bottom: 2rem;
}

.header_wrapper{
    display: flex;
}

</style>
