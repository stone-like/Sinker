<template>
<div>
 <div :class="comp_search">
  <input type="search" class="search-box" v-model="keywords"/>
  <span class="search-button" @click='changeOpen'>
    <span class="search-icon"></span>
  </span>
 </div>


 <transition name="fade_list" mode="out-in">
 <div v-if="this.$store.getters.getSearch_List.length && isOpen" :key="this.$store.getters.getSearch_List.length" class="search_result">
     <ul class="post_list">
       <li v-for="post in displayItems" :key="post.path" class="post_item">
        <router-link :to="post.path" class="post_link">
            {{post.title}}
        </router-link>
        <div class="post_body">
            {{post.body}}
        </div>
       </li>
     </ul>

   <div  @click="offbox" class="search_wrapper">

  <router-link to="/search" class="search_more">
            search more...
  </router-link>
  </div>
 </div>
</transition>

</div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
 data(){
     return{
         isOpen:false
     }
 },
 mounted(){
     this.$store.commit('setKeywords',{keywords:""})
    this.$store.dispatch('changeSearchList',{})
 },
 computed:{
     comp_search(){
         return this.isOpen ? "search open" : "search";
     },
     ...mapGetters({
           QueryParams:'getQueryParams'
      }),
     keywords:{
         set(keywords){
             this.$store.commit("setKeywords",{keywords})
         },
         get(){
             return this.QueryParams.keywords.keywords
         }
     },
     displayItems(){
          return this.$store.getters.getSearch_List.length > 0 ? this.$store.getters.getSearch_List.slice(0,8) : {};
      }
 },
  watch:{
     QueryParams:{
         handler:function(val,oldval){
              this.load(this.QueryParams.keywords.keywords)
          },
         deep:true//deepによりQueryParamsのkeywordとかまで監視可能
     }
   },
 methods:{
     changeOpen(){
         this.isOpen = !this.isOpen
         this.$store.commit('setKeywords',{keywords:""})
         this.$store.dispatch('changeSearchList',{})
     },
     load(query){
        if(this.QueryParams.keywords.keywords == ""){
                this.$store.dispatch('changeSearchList',{})
              return
        }else{
             var self = this
            axios.get("/api/question")
            .then(res => {
            self.$store.dispatch('setQuestionArray',res.data.data)
            })
            .catch(error => console.log(error.response.data))

           var question_lists = self.$store.getters.getQuestion_Array.filter(function(question){
                   return question.title.toLowerCase().includes(query.toLowerCase()) || question.body.toLowerCase().includes(query.toLowerCase());
               })

            }

        this.$store.dispatch('changeSearchList',question_lists)

        },
        offbox(){

            this.$store.dispatch('setSearchBoxFlag',false)
        }

  }
}
</script>

<style lang="scss" scoped>
$background-color: #2A2E37;
$search-bg-color: #363636;
// $icon-color: #00FEDE;
$icon-color:#E4E5DA;
$transition: all .5s ease;

.search_wrapper{
 display: flex;
 justify-content: center;
}
.search_more{
  font-size: 1.6rem;
  text-decoration: none;
  color: white;
  padding: 1rem 0 1rem 0;
}

.search_result{
    margin-right: .5rem;
    background-color:lighten($search-bg-color,5%);
}

.post_list{
    width:42rem;
    list-style: none;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    padding: 0;

}
.post_item{
    padding:1rem 1rem 1rem 2.4rem;
    display: flex;
    flex-direction: column;
    border-bottom: 1px solid grey;

}
.post_link{
    text-decoration: none;
    font-size: 1.6rem;
    color:$icon-color;
    margin-bottom: .4rem;

}
.post_body{
   color: white;
}

.fade_list-enter-active{
    //activeがついてるのはその期間中のenterからenter-toまでの間すべて、なのでその期間中変化していくことになるのでここにtransitionを書く
    opacity: 0;
    transform:translateY(-50px);
    transition: all .6s cubic-bezier(0.77, 0, 0.175, 1);
}

.fade_list-enter-to{
    opacity:1;
    transform: translateY(0);
}

.fade_list-leave-active{
    opacity: 1;
    transform: translateY(0);
    transition: all .6s cubic-bezier(0.77, 0, 0.175, 1);
}

.fade_list-leave-to{
    opacity: 0;
    transform: translateY(-50px);
}

.fade_list-move{
    //要素が追加されたり削除されたりするときはtransformのみ適用
    transition: transform .6s cubic-bezier(0.77, 0, 0.175, 1);
}
.search{
    width:6rem;
    height: 6rem;
    background-color: $search-bg-color;
    margin-top: 2rem;
    margin-right: .5rem;
    position: relative;
    overflow: hidden;
    transition: $transition;
    z-index: 99;


    &::before{
        content:"";
        display: block;
        width: 3px;
        height: 100%;
        margin: 0;
        position: relative;
        background-color: $icon-color;
        transition: $transition;
    }

    &.open{
       width:42rem;

       &::before{
           height:4rem;
           width: 1.5px;
           margin: 1rem 0 1rem 2rem;
           position: absolute;
       }
    }

}

.search-box{
    width: 100%;
    height: 100%;
    box-shadow: none;
    border: none;
    background-color: transparent;
    color: #fff;
    padding: 1rem 5rem 1rem 2.25rem;
    font-size: 2rem;

    &:focus{
        outline: none;
    }
}

.search-button{
    width: 6rem;//ここで親と同じにしたいからって100%にしてはいけない、何故ならsearchbuttonとこうして@clickを分けたのはclickして大きくなった時に、元の大きさの所だけクリック要素を残したかったから、100%にするとここまで大きくなるので、ここの大きさは元の6remとする
    height: 6rem;
    display: block;
    position: absolute;
    right: 0;
    top: 0;
    padding: 1rem;
    cursor: pointer;
}

.search-icon{
    width: 2.4rem;
    height: 2.4rem;//これはただの大きさ
    border-radius: 4rem;
    border: 3px solid $icon-color;//本線
    display: block;
    position: relative;
    margin-left: .5rem;
    transition: $transition;
    &::before{
        content:"";
        width: 3px;//borderが虫眼鏡の線みたいなものでwidthも同じく虫眼鏡の線だからborderと同じにする
        height: .9rem;
        position: absolute;
        right: -1.2px;
        top: 1.8rem;
        display: block;
        background-color: $icon-color;
        transform: rotate(-45deg);
        transition: $transition;
    }
    &::after{
        content:"";
         content:"";
        width: 3px;
        height: .9rem;
        position: absolute;
        right: -7.2px;
        top: 2.4rem;
        display: block;
        background-color: $icon-color;
        transform: rotate(-45deg);
        transition: $transition;
    }

    .open &{
        margin: 0;
        width: 3.6rem;
        height: 3.6rem;
        border-radius: 6rem;
        &::before{
            transform: rotate(52deg);
            right:1.32rem;
            top:1.38rem;
            height:1.08rem;
        }
        &::after{
            transform: rotate(-230deg);
            right:1.32rem;
            top:0.78rem;
            height:1.08rem;
        }
    }
}
</style>
