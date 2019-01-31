<template>
  <div>

   <edit-question :data="question" v-if="editting" @startcanceling="cancel"></edit-question>

   <div v-else>
    <show-question :data="question" v-if="question" @starteditting="edit"></show-question>
   </div>

   <v-container>
    <replies :question="question" v-if="question"></replies>
    <new-reply :questionSlug="question.slug" v-if="question"></new-reply>
   </v-container>
  </div>
</template>

<script>
import ShowQuestion from './ShowQuestion'
import EditQuestion from './EditQuestion'
import Replies from '../reply/Replies'
import NewReply from '../reply/NewReply'
export default {
  components:{ShowQuestion,EditQuestion,Replies,NewReply},
  data(){
      return{
          question:null,
          editting:false
      }
  },
  created(){
      this.getQuestion()//createdの時はデータならどこでも使えるでいい？data（）はcomputatedとか参照できなかったけど
      },
  methods:{
      cancel(){
        this.editting=false;
      },
      edit(){
              this.editting = true;
      },
      getQuestion(){

          axios.get("/api/question/" + this.$route.params.slug)
          .then(res => this.question = res.data.data)
      }
  },
  computed:{
      LoggedIn(){
          return this.$store.getters.userLoggedIn;
      }
  }
}
</script>

<style>

</style>
