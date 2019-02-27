<template>


  <v-layout class="entire_card" ref="entire_card">
      <v-card class="width_comp mt-2">
        <div class="question__cover" ref="question__cover" :style="{'background-color':cover__background}"></div>
        <v-card-title primary-title>
          <div>
            <h3 class="headline mb-0" @click="openCard">
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
       w_y:"",
       card_active:true

     }
 },
 props:['data'],
 methods:{
     openCard(){

         var self = this;
         var tm_card = new TimelineMax();
         self.$eventBus.$emit("dismissOtherCards",self.data)
         var card = self.$refs.entire_card


         //要素の画面左上からの位置
         this.getAbsolutePosition(card)

         //要素の中央の位置
         var my_x = self.w_x + ((card.getBoundingClientRect().width)/2)
         var my_y = self.w_y + ((card.getBoundingClientRect().height)/2)

         console.log( self.w_x,self.w_y)



         var target_x = self.w_width/2
         var target_y = self.w_height/2

         var route_x = target_x - my_x
         var route_y = target_y - my_y

         var second_target_x = self.w_width/7
         var second_target_y = self.w_height/7

         var second_route_x = second_target_x - my_x;
         var second_route_y = second_target_y - my_y;

         var route_array = [second_route_x,second_target_y]



         //一回目で絶対座標の中心に行ってもこのx:100とかというのは各々の最初の地点からどれくらい動いたかなので次にその中心からx:100とかしても結局元の位置から１００になってしまう、移動するときは常に絶対的な値(今回使っているroute、second_route)とか使わないとダメ


         tm_card
         .to(self.$refs.entire_card,.8,{x:route_x,y:route_y})
         .to(self.$refs.entire_card,.8,{scale:1.4})
         .add("scene1")
        // .to(self.$refs.entire_card,.8,{transform:'translate3d('+route_x+'px,'+route_y+'px,0)' })

         .to(self.$refs.entire_card,.3,{x:second_route_x,y:second_route_y,rotationZ:135,scale:0.8},"scene1")

　　　　　.to(self.$refs.question__cover,.3,{rotationX:160})
       　.to(self.$refs.entire_card,.3,{rotationZ:165,repeat:2,yoyo:true})
         .add("scene2")
         .to(self.$refs.entire_card,0.0000000001,{onStart:function(){
               self.$eventBus.$emit("doneCardEvent")
         }},"scene2")
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
     },
     listen(){
         var self = this
         self.$eventBus.$on("dismissOtherCards",(card) => {
             if(self.data.title === card.title){

             }else{
                 var tm_dismiss = new TimelineMax();
                 tm_dismiss.to(self.$refs.entire_card,.7,{opacity:0,rotationY:180},"scene1 +=.3")
             }
         })
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

     this.listen()


 },

 beforeDestroy(){
     window.removeEventListener('resize',this.handleResize)
 }
//  beforeRouteLeave(to,from,next){
//       var self = this;
//       if(to.path == '/question/:slug'){
//             // self.$eventBus.$on("doneCardEvent",() => {
//             //     next()
//             // })
//             next(false)
//       }
//  }
}//data.pathでそれぞれ個別の質問へ行けるようにしてあげる

</script>

<style lang="scss">
.entire_card{
    transform: translateX(0) translateY(0) scale(1);
    transform-origin:50% 50%;
}
.rotateopac-leave-active{
    opacity:1;
    transform: rotateY(180deg);
}
.rotateopac-leave{
    opacity: 0;
}

.width_comp{
    width:100%;
     transition: transform .2s ease-in-out;

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
.width_comp:hover{
   transform: translateY(-10px);
}



</style>
