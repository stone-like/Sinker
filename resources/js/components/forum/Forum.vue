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
        //ReadToReadだからsidebarのchangeはいらない
        //  self.$store.dispatch("changeToolRead",true)
         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_forum,0.0001,{
             onStart:function(){
                 //こっちは覆いを外す側でまず外すのが最初
                  self.$eventBus.$emit("wipeEffectRemove")
                //  self.$eventBus.$emit("entireFade","visible")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_forum,0.0001,{
             onStart:function(){
                 //覆いを外したらsidebarをだす
                 self.$eventBus.$emit("changeSidebarMode","/forum")

             }
         },"scene1+=0.00001")

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

             }
         })
         .add("scene1")
         .to(self.$refs.entire_forum,0.000001,{
             onStart:function(){
                   //ここでwipeするときの色と文字を送る
            if(to.path == "/category"){
                var wipe_array = {name:"CATEGORY",color:"#f39c12"}
            }else if(to.path == "/ask"){
                var wipe_array = {name:"QUESTION",color:"#c0392b"}
            }
                  self.$eventBus.$emit("wipeEffectStart",wipe_array)
                //sidebarが引っ込んだらこれをapphomeに送って一面を覆う
             }
         },"scene1+=0.000001")
         .add("scene2")
         .to(self.$refs.entire_forum,0.00000001,{
             onStart:function(){
                 //完全にアニメーションが終わったらv-ifでsidebarを消す(ただし今回はReadToReadなのでtoolbarに切り替える必要はないので消さなくていい)

                 next()

             }
         },"scene2+=1")
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
