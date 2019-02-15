<template>
  <div>
      <h2>yeah</h2>

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
          <ul>
              <li v-for="post in this.$store.getters.getSearch_List" :key="post.id">
                  <router-link :to="post.path">
                    {{post.title}}
                  </router-link>
              </li>
          </ul>
      </div>

      <!-- <router-link to="/entire_search">more result</router-link> -->
  </div>

</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
  data(){
      return{
        //   QueryParams:{
        //       questions:{},
        //       categories:{},
        //       keywords:"",
        //       mode_main:"",
        //       mode_sub:""
        //   }
        //   keywords:"",
        //   tags:{},//タグで検索した場合そのタグがあるquestionを表示する
        //   tags_question:{},
        //   categories_question:{},
        //   mode_sub:"",
        //   mode_main:"",//defaultだとそのままquestionで検索するようにする
        //   lists:{},
        // flag:false,
      }
  },
  mounted(){
    //   this.load()

    //initial value
    this.$store.commit('setModeSub',{mode_sub:""})
    this.$store.commit('setModeMain',{mode_main:""})
    this.$store.commit('setKeywords',{keywords:""})
    this.$store.dispatch('changeSearchList',{})
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

            // if(this.QueryParams.mode_sub.mode_sub == "ascending"){

            //    this.$store.dispatch("changeApplyList",_.orderBy(this.$store.getters.getSearch_List,"created_at",'asc'))

            // }else if(this.QueryParams.mode_sub.mode_sub == "descending"){
            //    this.$store.dispatch("changeApplyList",_.orderBy(this.$store.getters.getSearch_List,"created_at",'desc'))
            // }else{
            //      console.log(this.$store.getters.getSearch_List)
            //      this.$store.dispatch("changeApplyList",this.$store.getters.getSearch_List)
            // }
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
      }
    //   getQueryParams(){
    //       return this.QueryParams;
    //   }
  },
  watch:{
     QueryParams:{
         handler:function(val,oldval){
              this.load()
          },
         deep:true//deepによりQueryParamsのkeywordとかまで監視可能
     }
   }
}
</script>

<style lang="scss">
.DownUpDay{
  background-color: green;

}

.TagOrCat{
    background-color: green;
}

.input_key{
    background-color: green;
}

.result-view{
    background-color: green;
    height: 30rem;

}
</style>
