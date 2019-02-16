<template>
  <div>
      <select class="DownUpDay" v-model="mode_sub">
          <option value="hide" selected>--SelectSearchType--</option>
          <option value="ascending">ascending day</option>
          <option value="descending">descending day</option>
      </select>
      <select class="TagOrCat" v-model="mode_main">
          <option value="hide" selected>--SearchBy--</option>
          <option value="tags">By Tags</option>
          <option value="categories">By Categories</option>
      </select>

      <input class="input_key" type="text" v-model="keywords">

      <v-spacer></v-spacer>

      <div class="result-view" v-if="this.$store.getters.getSearch_List">
          <div class="result-detail">
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
          </nav>
          <transition-group tag="ul" name="move_fade" mode="out-in" class="post_list">
              <li v-for="post in displayItems" :key="post.path" class="post_item">
                  <router-link :to="post.path" class="post_link">
                    {{post.title}}
                  </router-link>
                  <div class="post_body">
                    {{post.body}}
                  </div>
              </li>
          </transition-group>

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
   beforeRouteLeave(to,from,next){
      this.$store.dispatch('setSearchBoxFlag',true)
      next()
   }
}
</script>

<style lang="scss" scoped>
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
    font-size: 2rem;
    margin-bottom: 1.3rem;
}

.result-searchBy{
    font-size: 2rem;

}
.result-searchBy-main{
    margin-bottom: 1.3rem;
}

.DownUpDay{
  background-color: white;

}

.TagOrCat{
    background-color:  white;
}

.input_key{
    background-color:  white;
}

.result-view{
    background-color:  white;


}

.pagenation{
    display: flex;
    flex-grow: 1;
    justify-content: space-around;
}
.page-item{
  list-style: none;
  height:2.5rem;
  width:2.5rem;

}

.page-link{
  text-decoration: none;
  height:2.5rem;
  width:2.5rem;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 2rem;
}

.post_list{
    list-style: none;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    height: 100%;
    width: 100%;



}
.post_item{

    display: flex;
    flex-direction: column;
    border-bottom: 1px solid grey;

}
.post_link{
    text-decoration: none;
    font-size: 1.6rem;
    color:blue;
    margin-bottom: .4rem;

}
.post_body{
   color: black;
}

.result-view{
    height: 100%;
    width: 100%;
}
</style>
