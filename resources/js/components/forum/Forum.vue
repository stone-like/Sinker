<template>
    <v-container fluid grid-list-md ref="entire_forum">
        <v-layout row wrap>
            <v-flex xs8>
            <question v-for="question in questions" :key="question.path" :data="question"></question>
            </v-flex>
            <v-flex xs4>
               <app-sidebar></app-sidebar>
            </v-flex>
        </v-layout>

    </v-container>
</template>

<script>
import Question from './Question'
import AppSidebar from "./AppSidebar"
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
  data(){
      return{
        questions:{}
      }
  },
  components:{Question,AppSidebar},
  created(){
      axios.get("api/question")
      .then(res => this.questions =res.data.data)
      .catch(error => console.log(error.response.data))
  },
  beforeRouteEnter(to,from,next){
      next(vm => {
          var  self = vm;
          //sidebarのactiveを外したりつけたり処理するここはforumなのでactiveをforumにつけてfrom.pathの所を外す
          if(from.path == "/ask" || from.path == "/category"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_enter")
            self.$store.dispatch("changeTransition_Tool","wipe")

         self.$store.dispatch("changeToolRead",true)
         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_forum,0.0001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode","/forum")
                 self.$eventBus.$emit("entireFade","visible")

             }
         })
         .add("scene1")

        }else if(from.path == "/welcome"){
            self.$store.dispatch("changeTransition_Router","HomeToRead")
            self.$store.dispatch("changeTransition_Tool","fade-side")

         self.$store.dispatch("changeToolRead",true)
         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_forum,0.0001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode","/forum")
                 self.$eventBus.$emit("entireFade","visible")

             }
         })
         .add("scene1")
        }

      })
  },
  beforeRouteLeave(to,from,next){
      var self = this;

       if(to.path == "/ask" || to.path == "/category"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_leave")
            self.$store.dispatch("changeTransition_Tool","wipe")

            var tm0 = new TimelineMax();

            tm0.to(self.$refs.entire_forum,0.00000001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode",to.path)
                 self.$eventBus.$emit("entireFade","invisible")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_forum,0.00000001,{
             onStart:function(){
                 //完全にアニメーションが終わったらv-ifでsidebarを消す(ただし今回はReadToReadなのでtoolbarに切り替える必要はないので消さなくていい)
                 self.$store.dispatch("changeToolRead",false)
                 next()

             }
         },"scene1+=1")
        }else if(to.path == "/welcome"){
            self.$store.dispatch("changeTransition_Router","ReadToHome")
            self.$store.dispatch("changeTransition_Tool","fade-side")

         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_forum,0.00000001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode")
                 self.$eventBus.$emit("entireFade","invisible")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_forum,0.00000001,{
             onStart:function(){
                 self.$store.dispatch("changeToolRead",false)
                 next()

             }
         },"scene1+=1")

        }


        next(false)
  }
}
</script>

<style>

</style>
