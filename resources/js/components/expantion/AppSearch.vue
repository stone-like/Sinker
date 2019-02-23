<template>
  <div class="entire_search">
      <div class="main_menu_wrapper">

           <!-- <div class="input_shape" v-if="isInput">
           </div> -->
          <input class="input_key" type="text" v-model="keywords" placeholder="enter your searchwords..." @focus="onFocusInput" @blur="onBlurInput">

          <div class="nav_detail">
                 <div class="result-number">
                  {{this.$store.getters.getSearch_List.length}}results
               </div>

              <nav v-if="pages" class="page_wrapper">
                <ul class="pagenation">
                    <li class="page-item">
                        <a @click="first" class="page-link" href="#">&laquo;</a>
                    </li>
                    <li class="page-item">
                        <a @click="prev" class="page-link" href="#">&lt;</a>
                    </li>

                    <li
                        v-for="i in displayPageRange"
                        class="page-item"
                        :class="{active: i-1 === currentPage}" :key=i>
                     <a @click="pageSelect(i)" class="page-link" href="#">{{i}}</a>
                    </li>

                    <li class="page-item">
                        <a @click="next" class="page-link" href="#">&gt;</a>
                    </li>
                    <li class="page-item">
                        <a @click="last" class="page-link" href="#">&raquo;</a>
                    </li>
                </ul>
          </nav>

          </div>

      <div class="result-view" v-if="this.$store.getters.getSearch_List">
          <!-- <div class="result-detail">
              <div class="result-keywords">
                     <div>
                       Searchword
                     </div>
                     <h2>
                       {{this.QueryParams.keywords.keywords}}
                     </h2>
              </div>
              <div class="result-number">
                  {{this.$store.getters.getSearch_List.length}}results
              </div>
              <div class="result-searchBy">
                  <div class="result-searchBy-main">
                    searchBy {{this.QueryParams.mode_main.mode_main}}
                  </div>
                  <div class="result-searchBy-sub">
                    filter {{this.QueryParams.mode_sub.mode_sub}}
                  </div>
              </div>
          </div>
          <nav v-if="pages">
                <ul class="pagenation">
                    <li class="page-item">
                        <a @click="first" class="page-link" href="#">&laquo;</a>
                    </li>
                    <li class="page-item">
                        <a @click="prev" class="page-link" href="#">&lt;</a>
                    </li>

                    <li
                        v-for="i in displayPageRange"
                        class="page-item"
                        :class="{active: i-1 === currentPage}" :key=i>
                     <a @click="pageSelect(i)" class="page-link" href="#">{{i}}</a>
                    </li>

                    <li class="page-item">
                        <a @click="next" class="page-link" href="#">&gt;</a>
                    </li>
                    <li class="page-item">
                        <a @click="last" class="page-link" href="#">&raquo;</a>
                    </li>
                </ul>
          </nav> -->

          <div class="main_result">
              <div class="side_shape"></div>
          <transition-group tag="ul" name="move_fade" mode="out-in" class="post_list">
              <li v-for="post in displayItems" :key="post.path" class="post_item">
                  <div class="hexagon">
                      <div class="hexagon_inner">

                      </div>
                  </div>

                  <div class="post_main_wrapper">
                  <router-link :to="post.path" class="post_link">
                    {{post.title}}
                  </router-link>
                  <div class="post_body">
                    {{post.body}}
                  </div>
                  </div>
              </li>
          </transition-group>
          </div>

      </div>
    </div>

    <div class="side_menu_wrapper">
      <h1 class="route_message">Search</h1>

      <div class="bottom_line"></div>

       <div class="result-detail">
              <div class="result-keywords">
                     <div>
                       [Searchword]
                     </div>
                     <h2>
                       {{this.QueryParams.keywords.keywords}}
                     </h2>
              </div>
              <!-- <div class="result-number">
                  {{this.$store.getters.getSearch_List.length}}results
              </div> -->
              <div class="result-searchBy">
                  <div class="result-searchBy-main">
                    [searchBy] {{this.QueryParams.mode_main.mode_main}}
                  </div>
                  <div class="result-searchBy-sub">
                    [filter] {{this.QueryParams.mode_sub.mode_sub}}
                  </div>
              </div>
          </div>

     <div class="select_wrapper">
      <!-- <select class="DownUpDay" v-model="mode_sub" ref="DownUpDay">
          <option value="" selected disable hidden >--SelectSearchType--</option>
          <option value="" class="option">By default</option>
          <option value="ascending" class="option">ascending day</option>
          <option value="descending" class="option">descending day</option>
      </select> -->
      <!-- <select class="TagOrCat" v-model="mode_main">
          <option value="" selected disable hidden>--SearchBy--</option>
           <option value="" class="option">By Questions</option>
          <option value="tags" class="option">By Tags</option>
          <option value="categories" class="option">By Categories</option>
      </select> -->
       <transition-group tag="ul" class="DownUpDay" v-model="mode_sub"  name="list_time_lag">
       <li key="dummy_sub" @click="openLi_sub" ref=dummy_sub class="li_title">--SelectSearchType--</li>
       <li @click.prevent="mode_sub='';closeAndTextchange_sub($event)"v-if="li_open_sub" key="option_default" class="li_options li_anime0">By default</li>
       <li @click.prevent="mode_sub='ascending';closeAndTextchange_sub($event)"  v-if="li_open_sub" key="option_ascending" class="li_options li_anime1">ascending day</li>
       <li 
       @click.prevent="mode_sub='descending';closeAndTextchange_sub($event)"  v-if="li_open_sub" key="option_descending" class="li_options li_anime2">descending day</li>
      </transition-group>

      <transition-group tag="ul" class="TagOrCat" v-model="mode_main"  name="list_time_lag">
       <li key="dummy" @click="openLi" ref=dummy class="li_title">--SearchBy--</li>
       <li @click.prevent="mode_main='';closeAndTextchange($event)"v-if="li_open" key="option_question" class="li_options li_anime0">By Questions</li>
       <li @click.prevent="mode_main='tags';closeAndTextchange($event)"  v-if="li_open" key="option_tag" class="li_options li_anime1">By Tags</li>
       <li 
       @click.prevent="mode_main='categories';closeAndTextchange($event)"  v-if="li_open" key="option_category" class="li_options li_anime2">By Categories</li>
      </transition-group>
     </div>
    </div>



      <!-- <router-link to="/entire_search">more result</router-link> -->
  </div>

</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
  data(){
      return{
        currentPage:0,
        size:12,       //1ページ当たりの個数
        pageRange:10, //一回に表示されるのはのは10個まで(1,2,...10)とか(2,3,....11)とか
        isInput:false,
        select_style_flag:false,
        li_open:false,
        li_open_sub:false
      }
  },
  mounted(){
    //   this.load()

    //initial value
    this.$store.commit('setModeSub',{mode_sub:""})
    this.$store.commit('setModeMain',{mode_main:""})
    // this.$store.commit('setKeywords',{keywords:""})
    // this.$store.dispatch('changeSearchList',{})
    //この二つは小さいsearchboxから引っ張ってくる
  },
  methods:{
//       changeSelect(e){
//           var selected = e.target;
//          var numberOfOptions = e.target.children.length;
//           selected.className += " select-hidden"
//           selected.outerHTML = '<div class="select">' +   selected.outerHTML + '</div>';

//           selected.parentNode.insertBefore()
     
//           var styledSelect = this.$refs.styledSelect;

//           //やっていることは元のselectからoption含めて情報を引き出して,
//           それを一旦新しいdivで囲ってからそのすぐ下にstyledselectを作って要素をコピーしている
//           //innerHTMLでもいいが全ブラウザ対応のtextContentを使う
//           styledSelect.textContent = selected.children[0].textContent;

//         //   this.select_options_flag = true;//新たなstyleのul
// 　　　　　// this.option_li_flag = true;//li
        
//       }, 
      closeAndTextchange(e){
          this.li_open = ! this.li_open
          this.$refs.dummy.textContent = e.target.textContent;
      },
      closeAndTextchange_sub(e){
          this.li_open_sub = ! this.li_open_sub
          this.$refs.dummy_sub.textContent = e.target.textContent;
      },
      openLi(){
          this.li_open = ! this.li_open
      },
      openLi_sub(){
          this.li_open_sub = ! this.li_open_sub
      },
      onFocusInput(){
       this.isInput = true;
      },
       onBlurInput(){
       this.isInput = false;
      },
       load(){
           console.log(this.QueryParams.keywords.keywords)
           //loadで場合分けしてしまうか？
        //   var self = this;

          //getQueryParamsの中身はkeywords、mode_main、mode_subで、これはv-modelで繋がっていてここが変更されたらwatchが起動して変更された値をqueryとして送る...なんか最後だけlistをvuexに入れればいいだけな気が...,試してみたところQueryParamはcreatedのときに参照できない問題がでるのでこれはvuexに、でもそうなると値をセットしてあげなければならない

      if(this.QueryParams.keywords.keywords == ""){
                this.$store.dispatch('changeSearchList',{})
              return
        }else{

            if(this.QueryParams.mode_main.mode_main == "tags"){


                this.tagsFilter()


            }else if(this.QueryParams.mode_main.mode_main == "categories"){

               this.categoriesFilter(this.QueryParams.keywords.keywords)

            }else{

               this.questionFilter(this.QueryParams.keywords.keywords)

            }

        }

       },
       tagsFilter(){
            // let filterList={}
            var self = this;

            axios.get("/api/tag",{ params: { keywords:self.QueryParams.keywords.keywords }})
               .then(res => {
                  console.log(res.data)
                  //ここでres.dataにfilterかけてしまうか・・・？
                  if(this.QueryParams.mode_sub.mode_sub == "ascending"){

                       var lists = _.orderBy(res.data,"created_at",'asc')

                 }else if(this.QueryParams.mode_sub.mode_sub == "descending"){
                       var lists = _.orderBy(res.data,"created_at",'desc')

            }else{
                 var lists = res.data

            }
                  self.$store.dispatch('changeSearchList',lists)

               })
               .catch(error => {
                   error.response.data
                //    this.$store.dispatch('changeApplyList',{})
                   })

            // return filterList;

       },
       categoriesFilter(query){
           var self = this;
              axios.get("/api/category")
             .then(res => self.$store.dispatch('setCategoryArray',res.data.data))//questionとかcategoryをQueryParamsにいれてそれをcomputedにおいておくとここでkeywordsから発火してきてここについたのにquestionも変化して二重発火になりそうだったのでquestionとかをQueryParamsからはずした

             axios.get("/api/question")
            .then(res => {
            self.$store.dispatch('setQuestionArray',res.data.data)
            })
            .catch(error => console.log(error.response.data))

           var target_category_id = null;
            self.$store.getters.getCategory_Array.forEach(function(category){
                  if(category.name.toLowerCase().includes(query.toLowerCase())){
                      //target_category_idの精製
                      target_category_id = category.id
                  }
                  else{

                  }
              })

              if(target_category_id === null){
                   self.$store.dispatch('changeSearchList',{})
                  //categoryがqueryと一致しなかったらlistを空に
                  return
              }


               let categoryList = self.$store.getters.getQuestion_Array.filter(function(question){

                   return question.categoly_id === parseInt(target_category_id,10);
              })

              if(this.QueryParams.mode_sub.mode_sub == "ascending"){

                       var lists = _.orderBy(categoryList,"created_at",'asc')

                 }else if(this.QueryParams.mode_sub.mode_sub == "descending"){
                       var lists = _.orderBy(categoryList,"created_at",'desc')

            }else{
                 var lists = categoryList

            }

              self.$store.dispatch('changeSearchList',lists)

       },
       questionFilter(query){
           var self = this
            axios.get("/api/question")
            .then(res => {
            self.$store.dispatch('setQuestionArray',res.data.data)
            })
            .catch(error => console.log(error.response.data))

           var question_lists = self.$store.getters.getQuestion_Array.filter(function(question){
                   return question.title.toLowerCase().includes(query.toLowerCase()) || question.body.toLowerCase().includes(query.toLowerCase());
               })

            if(this.QueryParams.mode_sub.mode_sub == "ascending"){

                       var lists = _.orderBy(question_lists,"created_at",'asc')

                 }else if(this.QueryParams.mode_sub.mode_sub == "descending"){
                       var lists = _.orderBy(question_lists,"created_at",'desc')

            }else{
                 var lists = question_lists

            }

               this.$store.dispatch('changeSearchList',lists)
       },


        first(){
          //pageの先頭へ
          this.currentPage = 0;
          this.selectHandler();
      },
      last(){
          //最後尾へ
          this.currentPage = this.pages - 1;//全体のpageが1~10としたらcurtrentPageは0から始まるのでpageとcurrentPageのずれをなくす
           this.selectHandler();
      },
      prev(){
          if(0 < this.currentPage){
              //最初のページでなければ
              this.currentPage--;
              this.selectHandler();
          }
      },
      next(){
          if(this.currentPage < this.pages - 1){
              //次のページがあるなら
              this.currentPage++;
              this.selectHandler();
          }
      },
      pageSelect(index){
          //指定したページへ
          this.currentPage = index - 1;
          this.selectHandler();
      },
      selectHandler(){
          //ページを移動したときの処理
      }
  },
  computed:{
      ...mapGetters({
           //最初createdでloadを参照するときv-modelでhtmlのほうからkeywordsとかとってこれないのでvuexにしてあげる
        //   getSearch_List,
        //   getIsLoading
          //ここでlist、QueryParam（keywords、mode_main、mode_sub）、Loadingを監視して、変更があればwatchが起動、QueryParam(keyword,mode_main,mode_sub)が変わるとwatchで発火するようにする
           QueryParams:'getQueryParams'//↓がsetでここでgetみたいなもの
      }),
      mode_sub:{
          set(mode_sub){
              this.$store.commit('setModeSub',{mode_sub})
          },
          get(){
             return this.QueryParams.mode_sub.mode_sub
          }
      },
      mode_main:{
          set(mode_main){
              this.$store.commit('setModeMain',{mode_main})
          },
          get(){
              return this.QueryParams.mode_main.mode_main
          }
      },
      keywords:{
         set(keywords){
              this.$store.commit('setKeywords',{keywords})
          },
          get(){
             return this.QueryParams.keywords.keywords
          }
      },
      //多分apiリクエストをcomputedで飛ばしているわけじゃないから無限送信はされないと思うけど
      pages(){
          //何ページになるか取得

          if(this.$store.getters.getSearch_List.length > 0){

              return Math.ceil(this.$store.getters.getSearch_List.length / this.size);
          }else{

              return false;
          }
          //search_listが変わるたびにページ数も変わる
      },
      displayPageRange(){
          //currentpageをaタグによるクリックから変えることによりそれに合わせてcomputedで再計算され描画も自動で変わる
          //表示するページ番号の配列(1~10までとか3~12までとか)
          const half = Math.ceil(this.pageRange / 2);
          //一回に表示できる限界の半分

          let start,end;

          if(this.pages < this.pageRange){
              //もしpaginationのrangeよりページ数が少なかったら
              start = 1;
              end = this.pages;
          }else if(this.currentPage < half){
              //10ページの半分5までは普通に進み、一回当たりの表示限界の半分を超えたら常にそのページが中央に来るようにする、つまりstartがどんどん2,3...とずれていく、最後の5個もそのまま中央には来ない,最初と最後の5個以外は常に中央

              //最初の5個はstartが１
              start = 1;
              end = start + this.pageRange-1;
          }else if(this.pages - half < this.currentPage){
              //last5ページ

              end = this.pages;
              start = end - this.pageRange+1;
          } else {
              //それ以外はcurrentpageが中心でstratとendはそれに合わせる
              start = this.currentPage - half + 1;
              end = this.currentPage + half;
          }

          let indexes=[];
          for(let i = start; i<=end; i++){
              indexes.push(i);
          }

          return indexes;
      },
       displayItems(){
          //currentpageをaタグによるクリックから変えることによりそれに合わせてcomputedで再計算され描画も自動で変わる
          //1ページ当たりで表示する質問を取得,最初はcurrentPage0だからquestion全体を0~11まで切り取り、次はcurrentPage1で12~23まで切り取る
          const head = this.currentPage *this.size;
          return this.$store.getters.getSearch_List.length > 0 ? this.$store.getters.getSearch_List.slice(head,head+this.size) : {};
          //最初、初期値の配列が{}となっていてこれに対してsliceをしようとするとerrorがでるので
          //(0,12)だと0から12個目の前、つまり11個なので0,1,2~11までを切り取る
      },
      isSelected(page){
          //今いるページか判定
          return page-1 === this.currentPage;
      }

  },
  watch:{
     QueryParams:{
         handler:function(val,oldval){
              this.load()
          },
         deep:true//deepによりQueryParamsのkeywordとかまで監視可能
     }
   },
    beforeRouteEnter(to,from,next){
      next(vm => {
          var  self = vm;
          //これでgridを全域にまで伸ばす
          self.$eventBus.$emit("changeGridUser",true)
      })
    },
   beforeRouteLeave(to,from,next){

      this.$store.dispatch('setSearchBoxFlag',true)
      self.$eventBus.$emit("changeGridUser",false)
      next()
   }
}
</script>

<style lang="scss" scoped>
$background-color: #EDE9E3;

.entire_search{
    width:100%;
    height: 100%;
    background-color: $background-color;
    display: flex;

}
.result-detail{
    padding:1.4rem 0 1.4rem 2.4rem;
}
.result-keywords{
  display: flex;
  font-size: 2rem;
  margin-bottom: 1.3rem;

  h2{
      margin-left: 2rem;
  }
}
.result-number{
    font-size: 1.6rem;
    // margin-bottom: 1.3rem;
     font-family: 'Geostar', cursive;
     background-color: #363636;
     color: #EDE9E3;
     padding: .5rem;
     align-self: flex-start;
     margin-right: auto;
}

.result-searchBy{
    font-size: 2rem;

}
.result-searchBy-main{
    margin-bottom: 1.3rem;
}

.select_wrapper{
    display: flex;
    flex-direction: column;
    margin-top: 7rem;
}
.DownUpDay{
//   background-color: #4E4B42;
//   color:#EDE9E3;
//    font-family: 'Geostar', cursive;
//    align-self: center;
   font-size: 2rem;
   padding:0;

   margin-bottom: 4rem;

    outline:none;

    &:focus{
        outline:none;
    }


}

.TagOrCat{
//       background-color: #4E4B42;
//   color:#EDE9E3;
//    font-family: 'Geostar', cursive;
//    align-self: center;
   font-size: 2rem;
   padding:0;

    outline:none;

    &:focus{
        outline:none;
    }




}




.main_menu_wrapper{
    margin-top: 3rem;
    margin-left: 25rem;

    display: flex;
    flex-direction: column;


    width: 90rem;
}
.side_menu_wrapper{
    margin-left: 10rem;
    margin-top: 3rem;
    display: flex;
    flex-direction: column;

    flex-grow: 1;

    .route_message{
      font-size: 4rem;
      margin-left: 20rem;
      font-family: 'Geostar', cursive;

    }
}

.bottom_line{
    height: 1px;
    background-color: #2E4A64;
    margin-top: 1rem;
}

// ::-webkit-input-placeholder{
//         font-size: 1.5rem;
//         color: black;
//     }

.input_key{
    border:1px solid black;
    height:5rem;
    width: 70rem;
     align-self: center;

    outline:none;


    transition: all .4s ease;

    &::-webkit-input-placeholder{
        font-size: 1.5rem;
        color: black;
    }

    // &:focus{
    //     outline:none;
    //     height: 8rem;
    //     width: 90rem;

    //     .input_key::-webkit-input-placeholder{
    //     font-size: 1.8rem;
    //     color: black;
    //   }
    // }
}

.input_shape{
    height: 5rem;
    width: 5rem;
    background-color:#CD664D;
    display: inline-block;
     transition: all .4s ease;
}

.result-view{

display: flex;
flex-direction: column;
}

.main_result{
    display: flex;
}

.side_shape{
    // height: 100%;
    width:2rem;
    background-color:  rgb(77, 79, 205);
    align-self: stretch;
}

.nav_detail{
    display: flex;
    margin-top: 1.8rem;
    margin-bottom: 1.8rem;
}
.page_wrapper{

}
.pagenation{
    display: flex;
    // flex-grow: 1;
    // justify-content: space-around;
}
.page-item{
  list-style: none;
  height:2.5rem;
  width:2.5rem;

  &:not(:last-child){
      margin-right: 1.8rem;
  }

}

.page-link{
  outline: none;
  display: inline-flex;
  text-decoration: none;
  color: #4C4940;
  width: 2.5rem;
  height: 2.5rem;
  line-height: 2.5rem;
  border-radius: 50%;
  border: double 4px #4C4940;
//   text-align: center;
//   vertical-align: middle;
justify-content: center;
align-items: center;
  overflow: hidden;

  font-size: 1.3rem;
}

.post_list{
    list-style: none;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    height: 100%;
    width: 100%;
    padding: 0;



}
.post_item{

    display: flex;
    border-bottom: 1px solid grey;

    &:not(:last-child){

    margin-bottom: 2rem;
    }

}

.hexagon{
   height: 3rem;
   width: 3rem;
   clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
   background-color:rgb(241, 241, 241);
   display: flex;
   justify-content: center;
   align-items: center;


   .hexagon_inner{
   height: 1.5rem;
   width: 1.5rem;
   clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
   background-color:#363636;
   }
}

.post_main_wrapper{
  display: flex;
  flex-direction: column;
}

.post_link{
    text-decoration: none;
    font-size: 1.6rem;
    color:rgb(77, 79, 205);
    margin-bottom: .4rem;

}
.post_body{
   color: black;
}

.li_options{
    background-color:darken(#EDE9E3,5);
    padding:1rem;
     color:#363636;
     list-style:none;
     cursor:pointer;

     &:hover{
         background-color:#fff;
         
     }
}

.li_title{
    padding:1rem;
    background-color: #4E4B42;
    color:#EDE9E3;
   font-family: 'Geostar', cursive;
}


@for $i from 0 through 2{
    $enter-delay:.2s;
    .list_time_lag-enter-active{
        opacity:0;

        animation:fade-in .5s;

        &.li_anime#{$i}{
            animation-delay:#{.2s*$i+$enter-delay};
        }
    }
}
.list_time_lag-leave-active{
   transition: all .3s;
    transform: translateX(-100px);
    opacity: 0;
    position:absolute;
}

@keyframes fade-in{
    0%{
        opacity: 0;
        transform: translateY(-15px);
    }
    100%{
        opacity: 1;
        transform: translateY(0);
    }
}

.move_fade-enter-active{
    transition: all .3s;
    opacity:0;
    transform:translateX(-200px);
}

.move_fade-leave-active{
    transition: all .3s;
    opacity:0;
    transform:translateX(-200px);
}

.move_fade-move{
    transition: all .5s cubic-bezier(0.77, 0, 0.175, 1);
}



</style>
