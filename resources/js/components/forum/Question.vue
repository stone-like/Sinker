<template>


  <v-layout class="entire_card">
      <v-card class="width_comp mt-2"  @click="openCover">
        <div class="question__cover" ref="question__cover" :style="{'background-color':cover__background}"></div>
        <v-card-title primary-title>
          <div>
            <h3 class="headline mb-0">
                <router-link :to="data.path">
                    {{data.title}}
                </router-link>
            </h3>
            <div>
                 {{data.created_at}}
            </div>
          </div>
        </v-card-title>
        <v-card-text ref="card__text">
            {{text__body}}
        </v-card-text>


      </v-card>
  </v-layout>

</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
 data(){
     return{
       text__title:"",
       text__body:"",
       cover__background:""
     }
 },
 props:['data'],
 methods:{
     openCover(){
         var self = this;
         var tm_cover = new TimelineMax();
         tm_cover.to(self.$refs.question__cover,.6,{rotationX:180})
         .add("scene1")
        //  .to(self.$refs.question__cover,.5,{y:-100})
        //  .to(self.$refs.question__cover,.5,{css:{'clip-path': circle(50% at 50% 50%)}});
     }
 },
 created(){
     //bodyに表示する文字数を制限する
     if(this.data.body.length > 170){
         this.text__body = this.data.body
        this.text__body = this.text__body.slice(0,167)+'...'

     }
     else{
         this.text__body = this.data.body
     }
     switch(this.data.categoly_id){

        case 1:
         this.cover__background = "red"
         break;
        case 2:
        this.cover__background = "blue"
         break;
        case 3:
        this.cover__background = "yellow"
         break;
        case 4:
        this.cover__background = "green"
         break;
        case 5:
        this.cover__background = "purple"
         break;
        case 6:
        this.cover__background = "black"
         break;
        case 7:
        this.cover__background = "teel"
         break;
        default:
        this.cover__background = "skyblue"
         break
     }


 },
 computed:{
     isClone(){
         return this.clone;
     }
 }
}//data.pathでそれぞれ個別の質問へ行けるようにしてあげる

</script>

<style lang="scss">
.width_comp{
    width:100%;

}
.question__cover{
    width:100%;
    height: 20%;
  //vueでstyleをいじるときは元の所に同じものを書くと上書きされてしまう
    clip-path: polygon(100% 0, 0 0, 50% 90%);
    transform: rotateX(0);
    transition: transform .8s cubic-bezier(0,1.27,1,.93);
    transform-origin: top center;
}
// .entire_card:hover .question__cover{
//    transform: rotateX(160deg);
// }

</style>
