<template>
    <div>
        <v-btn icon @click="LikeIt">
           <v-icon :color="color">favorite</v-icon> {{count}}
        </v-btn>
    </div>
</template>

<script>
export default {
  props:['content'],
  data(){
      return{
          liked:this.content.liked,
          count:this.content.like_count
      }
  },
  created(){
      //ここでcontentはあるリプライのこと
   Echo.channel('likeChannel')
   .listen('LikeEvent',(e) => {

       if(this.content.id == e.id){
           //つまり今likeが押されて、その押されたリプライと受け取り側のlikeが一緒だった場合に（ここではリプライが押されてその同じ記事にリプライしたすべてリプライに受け取らせているみたい）
           //そしてtypeが1ならlike、違えばunlike
           e.type == 1 ? this.count ++ : this.count--
       }
   });
  },
  methods:{
      LikeIt(){
          if(this.$store.getters.userLoggedIn){
              this.liked ? this.decr() : this.incr()
              this.liked = !this.liked
             }
          else{
              this.$store.dispatch("changeModalFlag",["Notification","you have to login to use like button","like"])
          }
      },
      incr(){
          axios.post("/api/like/"+this.content.id)
          .then(res => this.count++)
      },
      decr(){
          axios.delete("/api/like/"+this.content.id)
          .then(res => this.count--)
      }
  },
  computed:{
      color(){
          return this.liked ? "pink" : "pink lighten-4";
      }
  }
}
</script>

<style>

</style>
