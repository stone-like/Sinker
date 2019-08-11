<template>

  <div key="dummy" ref="dummy" class="dummy_position">

   <!-- <transition name="open_console" mode="out-in" class="transition_wrapper"> -->
   <transition-expand>

   <edit-question :data="question" v-if="editting" @startcanceling="cancel"></edit-question>

   <div v-else>
    <show-question :data="question" v-if="question" @starteditting="edit"></show-question>
   </div>
   </transition-expand>
   <!-- </transition> -->

   <v-container>
    <replies :question="question" v-if="question"></replies>
    <new-reply :questionSlug="question.slug" v-if="question"></new-reply>
   </v-container>
  </div>
</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
import ShowQuestion from './ShowQuestion'
import EditQuestion from './EditQuestion'
import Replies from '../reply/Replies'
import NewReply from '../reply/NewReply'
import TransitionExpand from '../transition-effects/TransitionExpand'

export default {
  components:{ShowQuestion,EditQuestion,Replies,NewReply,TransitionExpand},
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
          self.$eventBus.$emit("changeGridUser",true)
          self.$eventBus.$emit("setSidebarAllFalse")
          var tm_read = new TimelineMax();
          //何らかのanimationをおこないたい(wipeではない)

        //   tm_read
        //   .to(self.$refs.dummy,1,{rotationZ:0,scale:1,x:0,y:0})
        //   .to(self.$refs.dummy,1,{x:self.start_x,y:self.start_y,ease: Power1.easeOut})
        // //   .to(self.$refs.dummy,1,{scale:1,ease: Power1.easeOut})
        //   .add("scene1")
        //   .to(self.$refs.dummy,1,{rotationZ:0,ease: Power1.easeOut},"scene1")
        //    tm_read
        //    .set(self.$refs.dummy,1,{rotationZ:-205,scale:0.1,x:-650,y:-680})
        //,{rotationZ:150,scale:0.1,x:-650,y:-680}



      })
  },
  beforeRouteLeave(to,from,next){
      var self = this
    self.$eventBus.$emit("changeGridUser",false);

    if(to.path == "/ask" || to.path == "/category"  || to.path == "/forum" || to.path == "/userprofile" || to.path == "/bookmark" || to.path == "/setting"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_leave")
            self.$store.dispatch("changeTransition_Tool","wipe")

            var tm0 = new TimelineMax();

            tm0.to("html",0.00000001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode",to.path)

             }
         })
         .add("scene1")
         .to("html",0.000001,{
             onStart:function(){
                   //ここでwipeするときの色と文字を送る
            if(to.path == "/category"){
                var wipe_array = {name:"CATEGORY",color:"#f39c12"}
            }else if(to.path == "/ask"){
                var wipe_array = {name:"QUESTION",color:"#c0392b"}
            }else if(to.path == "/forum"){
                var wipe_array = {name:"FORUM",color:"#3498db"}
            }else if(to.path == "/userprofile"){
                 var wipe_array = {name:"USERPROFILE",color:"#8e44ad"}
            }else if(to.path == "/bookmark"){
                 var wipe_array = {name:"BOOKMARK",color:"#2ecc71"}
            }else if(to.path == "/setting"){
                var wipe_array = {name:"SETTING",color:"#FDA7DF"}
            }
                  self.$eventBus.$emit("wipeEffectStart",wipe_array)
                //sidebarが引っ込んだらこれをapphomeに送って一面を覆う
             }
         },"scene1+=0.000001")
         .add("scene2")
         .to("html",0.00000001,{
             onStart:function(){
                 //完全にアニメーションが終わったらv-ifでsidebarを消す(ただし今回はReadToReadなのでtoolbarに切り替える必要はないので消さなくていい)

                  self.$store.dispatch("changeToolRead",false)
                 next()

             }
         },"scene2+=1")
        }else if(to.path == "/welcome"){
            self.$store.dispatch("changeTransition_Router","ReadToHome")
            self.$store.dispatch("changeTransition_Tool","fade-side")

         var tm0 = new TimelineMax();

         tm0.to("html",0.00000001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode")
                 self.$eventBus.$emit("entireFade","invisible")

             }
         })
         .add("scene1")
         .to("html",0.00000001,{
             onStart:function(){

                 self.$store.dispatch("changeToolRead",false)
                 next()

             }
         },"scene1+=1")

        }else{
            next()
        }


        next(false)
  }


}
</script>

<style lang="scss">
.dummy_position{
  padding: 3rem 2rem 2rem 22rem;
}

.open_console-enter,
.open_console-leave-to{
   height:0;
}

.open_console-enter-active,
.open_console-leave-active{
    transition: all .4s ease;
}



</style>
