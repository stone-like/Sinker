<template>


<div class="entire_card" ref="entire_card">
    <div class="side_border_bar">
    </div>
    <div class="main_wrapper">
      <div class="right_wrapper">
           <div class="category_wrapper">
                <div class="category_color_shape" :style="{backgroundColor:cover__background}">
                </div>
                <div class="category_text">
                    {{category}}
                </div>
           </div>
            <h3 class="right_headline" @click="openCard">
                <router-link :to="throw_question.path" class="link_to">
                    {{throw_question.title}}
                </router-link>
            </h3>
            <div class="right_created_at">
                 {{throw_question.created_at}}
            </div>
            <div class="right_body">
              {{text__body}}
            </div>
      </div>

      <div class="left_wrapper">
            <div class="left_replies">
                {{throw_question.reply_count}}
                <span class="dummy">{{throw_question.reply_count > 1 ? "replies" : "reply" }}</span>
            </div>

            <div class="user_profile">
                <div class="image_wrapper">
                    <img :src="throw_question.user_img" class="image_content">
                </div>
                <div class="user_name">
                     {{throw_question.user}}
                </div>
            </div>
      </div>
    </div>

</div>

</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
 data(){
     //必要なのはuserの画像と名前
     //このquestionの情報
     return{
       text__title:"",
       text__body:"",
       cover__background:"",
       category:"",
       w_width:window.innerWidth,//画面の横
       w_height:window.innerHeight,//画面の縦
       w_x:"",
       w_y:"",
       card_active:true,
       my_image:""

     }
 },
 props:['throw_question'],
 methods:{
     openCard(){

         var self = this;
         var tm_card = new TimelineMax();
         self.$eventBus.$emit("dismissOtherCards",self.throw_question)
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

// 　　　　　.to(self.$refs.question__cover,.3,{rotationX:160})
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
             if(self.throw_question.title === card.title){
                 //これはpropsから得られたtitleを投げている、自分だったら何もせずもし他のカードから投げられたときは消える

             }else{
                 var tm_dismiss = new TimelineMax();
                 tm_dismiss.to(self.$refs.entire_card,.7,{opacity:0,rotationY:180},"scene1 +=.3")
             }
         })
     }
 },
 created(){
     //createdの時にpropにはアクセスできない..いやできるみたいだ
     //dataが被ってるのがよくないのかな・・・？
     //普通にpropsを参照するときはthisがいるだけだった...
      console.log(this.throw_question.user_img)
      //laravelはdefaultでrenderするときにpublicまでを補完するみたいなのでpublic以下のpathをとってくればいいみたい
      //C:\Users\user\Desktop\realtimeappv2\public\images\C1Ts6p33XjD2g51Az5A40lU77E5V..jpgの中から\images\C1Ts6p33XjD2g51Az5A40lU77E5V..jpgここまでほしい

    //  this.my_image = encodeURI(this.throw_question.user_img)
     //bodyに表示する文字数を制限する
     if(this.throw_question.body.length > 170){
         this.text__body = this.throw_question.body
        this.text__body = this.text__body.slice(0,167)+'...'

     }
     else{
         this.text__body = this.throw_question.body
     }
     switch(this.throw_question.categoly_id){

        case 1:
         this.category = "velos"
         this.cover__background = "red"
         break;
        case 2:
        this.cover__background = "blue"
         break;
        case 3:
        this.category = "illum"
        this.cover__background = "yellow"
         break;
        case 4:
        this.category = "quisquam"
        this.cover__background = "green"
         break;
        case 5:
        this.category = "iure"
        this.cover__background = "purple"
         break;
        case 6:
        this.category = "rocpo"
        this.cover__background = "black"
         break;
        case 7:
        this.cover__background = "teel"
         break;
         case 14:
          this.category = "nocturn"
        this.cover__background = "pink"
         break;
        default:
         this.category = "default"
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
    display: flex;
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

.side_border_bar{
    width: 3px;
    height: 100%;
    background-color: #4E4B42;
}

.main_wrapper{
    display: flex;
    flex-grow: 1;
}

.right_wrapper{
      height: 100%;
      width: 50%;
      padding: 1rem;
      display: flex;
      flex-direction: column;
}

.left_wrapper{
      height: 100%;
      width: 50%;
      padding: 1rem;
      display: flex;
      flex-direction: column;
}

.image_wrapper{
   width:7rem;
   height: 7rem;
   border-radius: 50%;
   display: flex;
   justify-content: center;
   align-items: center;
}

.image_content{
     width:5rem;
   height: 5rem;
   border-radius: 50%;
   object-fit: cover;
}

.user_profile{
    display: flex;
    align-items: center;
    margin-top: 8rem;

}

.user_name{
    margin-left: 1.5rem;
  font-size: 2rem;
  color: #363636;
}

.category_wrapper{
    display: flex;
    align-items: center;
}

.category_color_shape{
    height: 3rem;
    width: 3rem;
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);

}

.category_text{
    font-size: 1.6rem;
    margin-left: 1rem;
    color: #363636;
}

.right_headline{
    margin-top: 2rem;
    font-size: 2rem;
    // align-self:center;
    display: flex;
}

.right_created_at{
    margin-top: 2rem;
    color: #363636;
    align-self:center;
}

.link_to{
    text-decoration: none;
     color: #363636;
}

.right_body{
    color:#363636;
    margin-top: 2rem;
    font-size: 2rem;
}

.left_replies{
    justify-self: center;
    align-self: center;
    font-size: 3rem;
    color: #333;
    margin-top: 10rem;
    font-family: Arial, Helvetica, sans-serif;
}



</style>
