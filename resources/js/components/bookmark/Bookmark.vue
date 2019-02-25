<template>
  <div class="entire_wrapper">
      <!-- <draggable element="div" class="entire_draggable" v-model="bookmarks" :options="dragOptions"> -->
       <transition-group tag="div" :class="comp_upper_wrapper" name="upper">
         <div class="message_wrapper items" key="message_wrapper">
           <h1 class="route_message">Bookmark</h1>

           <div class="bottom_line items"></div>
         </div>

         <div class="input_wrapper items" key="input_wrapper">
          <input class="input_list items" type="text" placeholder="add new list..." v-model="addname" @focus="inputmodeOn" @blur="inputmodeOff">

          <div :class="comp_button_wrapper">
            <svg class="input_icon">
                         <use xlink:href="../../Helpers/img/sprite4.svg#icon-triangle-right"></use>
            </svg>
            <button @mousedown.prevent="addNewList"  class="input_button" :disabled='addname === ""'>addNewList</button>
           </div>
          </div>
       </transition-group>

          <transition-group tag="div" class="entire_transition" name="list">
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
          addname:"",
          isActive:false
        //   tasks:[]
      }
    },
    created(){
        // this.listen();

      this.getBookmark();

    },
    methods:{
        inputmodeOn(){
             this.isActive = true;
        },
        inputmodeOff(){
             this.isActive = false;
        },
        deleteBookmark(element_id,index){
             axios.delete('/api/bookmark/'+element_id)
             .then(res => {
                 this.bookmarks.splice(index,1);
             })
             .catch(error => error.response.data)
        },
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
        comp_button_wrapper(){
           return (this.addname === "") ? "button_wrapper" :"button_wrapper activate"
        },
        comp_upper_wrapper(){
            return this.isActive ? "upper_wrapper active" :"upper_wrapper";
        },
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

//動かしたい要素にitemsつけてあげる
.upper-move,
.items,
.list-move{
    transition:all .4s ease-in-out;
}

.active{
    .message_wrapper{
        transform: translate3d(0,2rem,0);
    }
    .input_wrapper{
        transform: translate3d(57rem,-3.6rem,0);
    }
    .bottom_line{
        width: 28rem;
        background-color: rgb(100, 50, 46);
    }
    .input_wrapper{
        //こういう風に入れ子で書いたときはtransitionさせたいときも入れ子で

       .input_list{
         height:4rem;
         width: 50rem;
         font-size: 2rem;

         &::-webkit-input-placeholder{
        font-size: 2rem;

       }
       }
    }

    // .button_wrapper{

    //     .input_button{
    //         color:#CD664D;
    //     }
    //     .input_icon{
    //           fill:#CD664D;
    //     }

    //        }
}

.activate{
       .input_button{
            color:#CD664D;
        }
        .input_icon{
              fill:#CD664D;
        }

}

.entire_wrapper{
    height: 100%;
    width: 100%;
    background-color: $background-color;

}

.message_wrapper{
    margin-left: 3rem;
    margin-top: 3rem;
   display: flex;
   flex-direction: column;
}

.route_message{
    font-size: 4rem;
     font-family: 'Geostar', cursive;
}

.bottom_line{
    height: 1px;
    background-color: rgb(100, 70, 46);
    margin-top: 1rem;
    width: 40rem;
}

.input_wrapper{
     margin-left: 3rem;
    margin-top: 2rem;
    display: flex;
    align-items: center;


    .input_list{
         border:1px solid black;
         height:2.5rem;
         width: 30rem;
         outline:none;
         font-size: 1.6rem;

         &::-webkit-input-placeholder{
        font-size: 1.6rem;
        color: black;
    }
    }

    .button_wrapper{
        margin-left: 2rem;
        display: flex;
        justify-content: center;
        align-items: center;

        .input_icon{
              height: 3rem;
              width: 3rem;
              color:currentColor;
        }

        .input_button{
          margin-top: .2rem;
        }

        .bottom_line2{
             height: 1px;
    background-color: #333;
        }
    }
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
