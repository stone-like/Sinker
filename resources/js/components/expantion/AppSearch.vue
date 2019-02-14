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

      <input class="input_key" type="text" v-model="keywords" @focus="display">

      <v-spacer></v-spacer>

      <div class="result-view" v-if="questions">
          <ul>
              <li v-for="post in filteredList" :key="post.id">
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
export default {
  data(){
      return{
          keywords:"",
          questions:{},
        //   tags:{},//タグで検索した場合そのタグがあるquestionを表示する
          categories:{},
        //   tags_question:{},
        //   categories_question:{},
          mode_sub:"",
          mode_main:"",//defaultだとそのままquestionで検索するようにする
        //   lists:{},
        // flag:false
      }
  },
  created(){
      var self = this;
      axios.get("/api/question")
      .then(res => {
          self.questions = res.data.data
      })
      .catch(error => console.log(error.response.data))

    //   axios.get("/api/tag")
    //   .then(res => {
    //       self.tags = res.data
    //   })
    //   .catch(error => console.log(error.response.data))

       axios.get("/api/category")
      .then(res => this.categories = res.data.data)
  },
  methods:{
       display(){
          console.log("activated")
       },
       tagsFilter(){
            // let filterList={}
            var self = this;
            axios.get("/api/tag",{ params: { keywords:self.keywords} })
               .then(res => {
                  console.log(res.data)
                  self.$store.dispatch('changeSearchList',res.data)

               })
               .catch(error => error.response.data)

            // return filterList;
       },
       categoriesFilter(query){
           var target_category_id = null;
            this.categories.forEach(function(category){
                  if(category.name.toLowerCase().includes(query.toLowerCase())){
                      target_category_id = category.id
                  }
                  else{

                  }
              })

              if(target_category_id === null){
                   this.$store.dispatch('changeSearchList',{})
                  //categoryがqueryと一致しなかったらlistを空に
                  return
              }


               let categoryList = this.questions.filter(function(question){

                   return question.categoly_id === parseInt(target_category_id,10);
              })

              this.$store.dispatch('changeSearchList',categoryList)

       }
  },
  computed:{

     filteredList(){

          if(this.keywords == ""){
               this.$store.dispatch('changeSearchList',{})
              return
          }
           var query = this.keywords;//ここが変化するたびに再計算されるでいい？
        //    self.lists = null;

        //    var target_category_id = null;
        //    var lists = {};
           if(this.mode_main == "tags"){
               //毎回apiしなきゃいけないのは少しつらい・・・中間テーブルでもapi抜きでとってこれればいいけど...なんか一回遅れになって挙動が変だし/tag/と反応しない

               this.tagsFilter()
            //     console.log(lists)
            //    axios.get("/api/tag",{ params: { keywords:this.keywords} })
            //    .then(res => {

            //        self.lists = res.data
            //    })
            //    .catch(error => error.response.data)
           }else if(this.mode_main == "categories"){

              this.categoriesFilter(query)
            //   self.categories.forEach(function(category){
            //       if(category.name.toLowerCase().includes(query.toLowerCase())){
            //           target_category_id = category.id
            //       }
            //       else{

            //       }
            //   })

            //   if(target_category_id === null){
            //       //categoryがqueryと一致しなかったらなにもしない
            //       return
            //   }


            //    self.lists = self.questions.filter(function(question){

            //        return question.categoly_id === parseInt(target_category_id,10);
            //   })
           }else{
               //like検索みたいにしている

               var lists = this.questions.filter(function(question){
                   return question.title.toLowerCase().includes(query.toLowerCase()) || question.body.toLowerCase().includes(query.toLowerCase());
               })

               this.$store.dispatch('changeSearchList',lists)
           }
           //ここまででquestion、tags、categoriesで大元の検索結果は作った、あとは日付、降順、昇順
            //    console.log(lists)
            //ここからまたmethodに移す
           if(this.mode_sub == "ascending"){

              return _.orderBy(this.$store.getters.getSearch_List,"created_at",'asc')

           }else if(this.mode_sub == "descending"){

               return _.orderBy(this.$store.getters.getSearch_List,"created_at",'desc')
           }else{

               return this.$store.getters.getSearch_List
           }

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
