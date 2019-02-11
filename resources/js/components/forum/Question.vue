<template>


  <v-layout class="entire_card" ref="entire_card">
      <v-card class="width_comp mt-2"  @click="openCard">
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
       cover__background:"",
       w_width:window.innerWidth,//画面の横
       w_height:window.innerHeight,//画面の縦
       w_x:"",
       w_y:""
     }
 },
 props:['data'],
 methods:{
     openCard(){

         var self = this;
         var tm_card = new TimelineMax();
        //  self.$eventBus.$emit("dismissOtherCards",self.data)
         var card = self.$refs.entire_card

         //要素の画面左上からの位置
         this.getAbsolutePosition(card)

         //要素の中央の位置
         var my_x = self.w_x + ((card.getBoundingClientRect().width)/2)
         var my_y = self.w_y + ((card.getBoundingClientRect().height)/2)



         var target_x = self.w_width/2
         var target_y = self.w_height/2

         var root_x = target_x - my_x
         var root_y = target_y - my_y


         tm_card.to(self.$refs.entire_card,.8,{x:root_x,y:root_y})
         .add("scene1")
     },
     handleResize(){
         //windowsizeが変わるたびに毎回w_widthとw_heightを変えていく
         this.w_width = window.innerWidth;
         this.w_height = window.innerHeight;
     },
     getAbsolutePosition(elm){
         const {left,top} = elm.getBoundingClientRect();
         const {left:bleft,top:btop} = document.body.getBoundingClientRect();

         this.w_x = left -bleft;
         this.w_y = top - btop;
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

     //windowsizeが変わるたびに発火
     window.addEventListener('resize',this.handleResize)


 },

 beforeDestroy(){
     window.removeEventListener('resize',this.handleResize)
 }
}//data.pathでそれぞれ個別の質問へ行けるようにしてあげる

</script>

<style lang="scss">
.entire_card{
    transform-origin: 50% 50%;
}
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
