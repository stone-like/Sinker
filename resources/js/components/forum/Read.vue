<template>
 <transition-group
    name="rotate_opac" appear>

  <div key="dummy" ref="dummy" class="dummy_position">
   <edit-question :data="question" v-if="editting" @startcanceling="cancel"></edit-question>

   <div v-else>
    <show-question :data="question" v-if="question" @starteditting="edit"></show-question>
   </div>

   <v-container>
    <replies :question="question" v-if="question"></replies>
    <new-reply :questionSlug="question.slug" v-if="question"></new-reply>
   </v-container>
  </div>
 </transition-group>
</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
import ShowQuestion from './ShowQuestion'
import EditQuestion from './EditQuestion'
import Replies from '../reply/Replies'
import NewReply from '../reply/NewReply'
export default {
  components:{ShowQuestion,EditQuestion,Replies,NewReply},
  data(){
      return{
          question:null,
          editting:false,
          start_x:"",
          start_y:""
      }
  },
  created(){
      this.getQuestion()//createdの時はデータならどこでも使えるでいい？data（）はcomputatedとか参照できなかったけど

      this.listen()
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
  },
  beforeRouteEnter(to,from,next){
      next(vm => {
          var  self = vm;

          var tm_read = new TimelineMax();

          tm_read
          .to(self.$refs.dummy,1,{rotationZ:0,scale:1,x:0,y:0})
        //   .to(self.$refs.dummy,1,{x:self.start_x,y:self.start_y,ease: Power1.easeOut})
        // //   .to(self.$refs.dummy,1,{scale:1,ease: Power1.easeOut})
        //   .add("scene1")
        //   .to(self.$refs.dummy,1,{rotationZ:0,ease: Power1.easeOut},"scene1")
        //    tm_read
        //    .set(self.$refs.dummy,1,{rotationZ:-205,scale:0.1,x:-650,y:-680})
        //,{rotationZ:150,scale:0.1,x:-650,y:-680}



      })
  }


}
</script>

<style lang="scss">
.dummy_position{
//   position: absolute;
//これ選択したquestionの位置依存になってしまっている・・・？
  transform: translate3d(-60vw,-70vh) scale(0.1) rotateZ(-120deg);
}

</style>
