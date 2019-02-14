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

      <div class="result-view" v-if="flag">
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
        flag:false
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
  computed:{

     filteredList(){
           var self = this;
           var query = self.keywords;//ここが変化するたびに再計算されるでいい？
        //    self.lists = null;

           var target_category_id = null;
        //    var lists = {};
           if(self.mode_main == "tags"){
               //毎回apiしなきゃいけないのは少しつらい・・・中間テーブルでもapi抜きでとってこれればいいけど...なんか一回遅れになって挙動が変だし/tag/と反応しない
               axios.get("/api/tag",{ params: { keywords:self.keywords} })
               .then(res => {

                   self.lists = res.data
               })
               .catch(error => error.response.data)
           }else if(self.mode_main == "categories"){
              self.categories.forEach(function(category){
                  if(category.name.toLowerCase().includes(query.toLowerCase())){
                      target_category_id = category.id
                  }
                  else{

                  }
              })

              if(target_category_id === null){
                  //categoryがqueryと一致しなかったらなにもしない
                  return
              }


               self.lists = self.questions.filter(function(question){

                   return question.categoly_id === parseInt(target_category_id,10);
              })
           }else{
               //like検索みたいにしている

               self.lists = self.questions.filter(function(question){
                   return question.title.toLowerCase().includes(query.toLowerCase()) || question.body.toLowerCase().includes(query.toLowerCase());
               })
           }
           //ここまででquestion、tags、categoriesで大元の検索結果は作った、あとは日付、降順、昇順
               console.log(self.lists)
           if(self.mode_sub == "ascending"){
              self.flag=true
              return _.orderBy(self.lists,"created_at",'asc')

           }else if(self.mode_sub == "descending"){
               self.flag=true
               return _.orderBy(self.lists,"created_at",'desc')
           }else{
               self.flag=true
               return self.lists
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
