<template>
  <section id="slideshow" @click.self="hide">
    <div class="entire-content" @click.self="hide">
      <div :class="comp_content_carrousel" @wheel.prevent="rotar" :style="{transform:'rotateZ(15deg) rotateX(-7deg) rotateY(' + rotate_60_now + 'deg)'}"  ref="carrousel">

       <figure class="shadow shadow_1"  @click="openDescription(1)" ref="shadow_1">
           <svg class="shadow__icon shadow__icon--1">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-brush" class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Forum</span>
       </figure>

       <figure class="shadow shadow_2"   @click="userLoggedIn?openDescription(2):openDescription(7)" ref="shadow_2">
           <svg class="shadow__icon shadow__icon--2">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-chat" v-if="userLoggedIn" class="shadow__icon--use"></use>
               <use xlink:href="../../Helpers/img/sprite.svg#icon-lock" v-else class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Ask Question</span>
       </figure>
       <figure class="shadow shadow_3"  @click="userLoggedIn?openDescription(3):openDescription(7)" ref="shadow_3">
            <svg class="shadow__icon shadow__icon--3">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-cog" v-if="userLoggedIn" class="shadow__icon--use"></use>
               <use xlink:href="../../Helpers/img/sprite.svg#icon-lock" v-else class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Setting</span>
       </figure>
       <figure class="shadow shadow_4"   @click="openDescription(4)" ref="shadow_4">
            <svg class="shadow__icon shadow__icon--4">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-home" class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Home</span>
        </figure>
       <figure class="shadow shadow_5"  @click="userLoggedIn?openDescription(5):openDescription(7)" ref="shadow_5">
           <svg class="shadow__icon shadow__icon--5">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-v-card" v-if="userLoggedIn" class="shadow__icon--use"></use>
               <use xlink:href="../../Helpers/img/sprite.svg#icon-lock" v-else class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">User Profile</span>
       </figure>
       <figure class="shadow shadow_6"  @click="userLoggedIn?openDescription(6):openDescription(7)" ref="shadow_6">
            <svg class="shadow__icon shadow__icon--6">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-text-document" v-if="userLoggedIn" class="shadow__icon--use"></use>
               <use xlink:href="../../Helpers/img/sprite.svg#icon-lock" v-else class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Category</span>
       </figure>
      </div>
    </div>
  </section>
</template>

<script>
import {mapGetters} from 'vuex'
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
// import ClickOutside from 'vue-click-outside'
export default {
     data(){
        return{
            rotate_per_60:0,
            push_right:false,
            leaveflag:false,
            carrousel_1:"",
            carrousel_2:"",
            carrousel_3:"",
            carrousel_4:"",
            carrousel_5:"",
            carrousel_6:""
        }
     },
     computed:{

      ...mapGetters({
           userLoggedIn:'userLoggedIn'//これでログイン状態を監視している、ログインしていなかったらfalse、していればtrue

     }),
     comp_content_carrousel(){
        return this.push_right ? "content-carrousel right-mv" : "content-carrousel";
     },
     rotate_60_now(){
         return this.rotate_per_60;
     }
     },
     methods:{
         rotar(){
             this.rotate_per_60+=-60
            if(this.rotate_per_60 == -360){
             this.rotate_per_60 = 0
            }
         },
         openDescription(number){
             this.$eventBus.$emit("openDescription",number);
             this.push_right = true;
         },
         hide(){
             this.$eventBus.$emit("closeDescription");
             this.push_right = false;
         }
     },
     beforeRouteLeave(to,from,next){
        //  this.leaveflag=true;
        var self = this;//こうしないとthisがずれてしまう
        if(to.path == "/forum" || to.path == "/ask" || to.path == "/category"){
            self.$store.dispatch("changeTransition_Router","HomeToRead")
            self.$store.dispatch("changeTransition_Tool","fade-up")
           self.$eventBus.$emit("changeToolbarMode")
           self.$eventBus.$emit("entireFade","invisible")
        //    self.$store.getters.getTransitionTool
        }

        var tm0 = new TimelineMax();
        tm0.to(self.$refs.carrousel,0.0001,{onStart:function(){
            // switch(parseInt(self.rotate_per_60)){
            //     case 0:
            //      self.carrousel_1 = self.$refs.shadow_1;
            //      self.carrousel_2 = self.$refs.shadow_2;
            //      self.carrousel_3 = self.$refs.shadow_3;
            //      self.carrousel_4 = self.$refs.shadow_4;
            //      self.carrousel_5= self.$refs.shadow_5;
            //      self.carrousel_6 = self.$refs.shadow_6;
            //        break;
            //     case -60:
            //      self.carrousel_1 = self.$refs.shadow_2;
            //      self.carrousel_6 = self.$refs.shadow_1;
            //      self.carrousel_5 = self.$refs.shadow_6;
            //      self.carrousel_4 = self.$refs.shadow_5;
            //      self.carrousel_3 = self.$refs.shadow_4;
            //      self.carrousel_2 = self.$refs.shadow_3;

            //        break;
            //     case -120:
            //      self.carrousel_1 = self.$refs.shadow_3;
            //      self.carrousel_6 = self.$refs.shadow_2;
            //      self.carrousel_5 = self.$refs.shadow_1;
            //      self.carrousel_4 = self.$refs.shadow_6;
            //      self.carrousel_3 = self.$refs.shadow_5;
            //      self.carrousel_2 = self.$refs.shadow_4;
            //        break;
            //     case -180:
            //      self.carrousel_1 = self.$refs.shadow_4;
            //      self.carrousel_6 = self.$refs.shadow_3;
            //      self.carrousel_5 = self.$refs.shadow_2;
            //      self.carrousel_4 = self.$refs.shadow_1;
            //      self.carrousel_3 = self.$refs.shadow_6;
            //      self.carrousel_2 = self.$refs.shadow_5;
            //        break;
            //     case -240:
            //      self.carrousel_1 = self.$refs.shadow_5;
            //      self.carrousel_6 = self.$refs.shadow_4;
            //      self.carrousel_5 = self.$refs.shadow_3;
            //      self.carrousel_4 = self.$refs.shadow_2;
            //      self.carrousel_3 = self.$refs.shadow_1;
            //      self.carrousel_2 = self.$refs.shadow_6;
            //        break;
            //     case -300:
            //      self.carrousel_1 = self.$refs.shadow_6;
            //      self.carrousel_6 = self.$refs.shadow_5;
            //      self.carrousel_5 = self.$refs.shadow_4;
            //      self.carrousel_4 = self.$refs.shadow_3;
            //      self.carrousel_3 = self.$refs.shadow_2;
            //      self.carrousel_2 = self.$refs.shadow_1;
            //        break;
            // }

            self.rotate_per_60-=720;

        }})
        .add("scene1")
        .to(self.$refs.shadow_1,.5,{bezier:{type:"cubic",values:[{z:273,x:0,y:0}, {z:180,x:-260,y:0}, {z:136.5,x:-380,y:-16}, {z:0,x:-380,y:-31}]}, ease:Power1.easeInOut},"scene1")
        .to(self.$refs.shadow_1,.5,{scale:0.5,rotationY:-90,ease:Power1.easeInOut},"scene1")
        .to(self.$refs.shadow_6,.5,{bezier:{type:"cubic",values:[{z:136,x:-268.8,y:-20}, {z:0,x:-386,y:-30}, {z:227.5,x:-247,y:-15}, {z:-273,x:-157,y:-10}]}, ease:Power1.easeInOut},"scene1")
        .to(self.$refs.shadow_6,.5,{scale:0.35,rotationY:-90},"scene1")
        .to(self.$refs.shadow_5,.5,{bezier:{type:"cubic",values:[{z:-137,x:-236.5,y:-18}, {z:-273,x:-147,y:-11.5}, {z:-350,x:0,y:-5}, {z:-273,x:157,y:1.5}]}, ease:Power1.easeInOut},"scene1")
        .to(self.$refs.shadow_5,.5,{scale:0.2,rotationY:90},"scene1")
        .to(self.$refs.shadow_4,.5,{bezier:{type:"cubic",values:[{z:-273,x:0,y:-5}, {z:-180,x:260,y:1.5}, {z:-137,x:385,y:12}, {z:0,x:385,y:30}]}, ease:Power1.easeInOut},"scene1")
        .to(self.$refs.shadow_4,.5,{scale:0.4,rotationY:90},"scene1")
        .to(self.$refs.shadow_3,.5,{bezier:{type:"cubic",values:[{z:-137,x:236.5,y:12}, {z:0,x:386,y:30}, {z:137,x:386,y:20}, {z:273,x:157.5,y:10}]}, ease:Power1.easeInOut},"scene1")
        .to(self.$refs.shadow_3,.5,{scale:0.5,rotationY:90},"scene1")
        .to(self.$refs.shadow_2,.5,{bezier:{type:"cubic",values:[{z:137,x:268.8,y:20}, {z:273,x:157.5,y:10}, {z:350,x:0,y:0}, {z:273,x:-157.5,y:-10}]}, ease:Power1.easeInOut},"scene1")
        .to(self.$refs.shadow_2,.5,{scale:0.6,rotationY:90},"scene1")
        .add("scene2")
        .to([self.$refs.shadow_1,self.$refs.shadow_2,self.$refs.shadow_3,self.$refs.shadow_4,self.$refs.shadow_5,self.$refs.shadow_6],.5,{opacity:0},"scene2 -=.15")
        .add("scene3")
        .to(self.$refs.carrousel,0.00000001,{onStart:function(){
            //ここでsearchboxを出す
            self.$store.dispatch('setSearchBoxFlag',true)
            self.$store.dispatch("changeToolHome",false)
            next()
        }})





        // var tm1 = new TimelineMax();
        // tm1.to(this.$refs.shadow_1,.5,{bezier:{type:"cubic",values:[{z:273,x:0,y:0}, {z:180,x:-260,y:0}, {z:136.5,x:-380,y:-16}, {z:0,x:-380,y:-31}]}, ease:Power1.easeInOut}).add("scene1").to(this.$refs.shadow_1,.5,{opacity:0},"scene1 -=.25");

        // var tm2 = new TimelineMax();
        // tm2.to(this.$refs.shadow_1,.5,{scale:0.5,rotationY:-90,ease:Power1.easeInOut})

        // tm0.add(tm1,tm2);
        //  TweenMax.to(this.$refs.shadow_1,.5,{bezier:{type:"cubic",values:[{z:273,x:0,y:0}, {z:180,x:-260,y:0}, {z:136.5,x:-380,y:-16}, {z:0,x:-380,y:-31}]}, ease:Power1.easeInOut});
        //  TweenMax.to(this.$refs.shadow_1,.5,{scale:0.5,rotationY:-90,ease:Power1.easeInOut})

        //   TweenMax.to(this.$refs.shadow_6,.5,{bezier:{type:"cubic",values:[{z:136,x:-268.8,y:-20}, {z:0,x:-386,y:-30}, {z:227.5,x:-247,y:-15}, {z:-273,x:-157,y:-10}]}, ease:Power1.easeInOut});
        //   TweenMax.to(this.$refs.shadow_6,.5,{scale:0.35,rotationY:-90})

        //   TweenMax.to(this.$refs.shadow_5,.5,{bezier:{type:"cubic",values:[{z:-137,x:-236.5,y:-18}, {z:-273,x:-147,y:-11.5}, {z:-350,x:0,y:-5}, {z:-273,x:157,y:1.5}]}, ease:Power1.easeInOut});
        //   TweenMax.to(this.$refs.shadow_5,.5,{scale:0.2,rotationY:90})

        //   TweenMax.to(this.$refs.shadow_4,.5,{bezier:{type:"cubic",values:[{z:-273,x:0,y:-5}, {z:-180,x:260,y:1.5}, {z:-137,x:385,y:12}, {z:0,x:385,y:30}]}, ease:Power1.easeInOut});
        //   TweenMax.to(this.$refs.shadow_4,.5,{scale:0.4,rotationY:90})

        //    TweenMax.to(this.$refs.shadow_3,.5,{bezier:{type:"cubic",values:[{z:-137,x:236.5,y:12}, {z:0,x:386,y:30}, {z:137,x:386,y:20}, {z:273,x:157.5,y:10}]}, ease:Power1.easeInOut});
        //   TweenMax.to(this.$refs.shadow_3,.5,{scale:0.5,rotationY:90})

        //    TweenMax.to(this.$refs.shadow_2,.5,{bezier:{type:"cubic",values:[{z:137,x:268.8,y:20}, {z:273,x:157.5,y:10}, {z:350,x:0,y:0}, {z:273,x:-157.5,y:-10}]}, ease:Power1.easeInOut});
        //   TweenMax.to(this.$refs.shadow_2,.5,{scale:0.6,rotationY:90})



        //     TweenMax.from(this.$refs.shadow_1,1,{bezier:{type:"cubic",values:[{z:273,x:0,y:0}, {z:180,x:-260,y:0}, {z:136.5,x:-380,y:-16}, {z:0,x:-380,y:-31}]}, ease:Power1.easeInOut});
        //  TweenMax.from(this.$refs.shadow_1,1,{scale:0.5,rotationY:-90})


        //   TweenMax.from(this.$refs.shadow_6,1,{bezier:{type:"cubic",values:[{z:136,x:-268.8,y:-20}, {z:0,x:-386,y:-30}, {z:227.5,x:-247,y:-15}, {z:-273,x:-157,y:-10}]}, ease:Power1.easeInOut});
        //   TweenMax.from(this.$refs.shadow_6,1,{scale:0.5,rotationY:-90})

        //   TweenMax.from(this.$refs.shadow_5,1,{bezier:{type:"cubic",values:[{z:-137,x:-236.5,y:-18}, {z:-273,x:-147,y:-11.5}, {z:-350,x:0,y:-5}, {z:-273,x:157,y:1.5}]}, ease:Power1.easeInOut});
        //   TweenMax.from(this.$refs.shadow_5,1,{scale:0.5,rotationY:90})

        //   TweenMax.from(this.$refs.shadow_4,1,{bezier:{type:"cubic",values:[{z:-273,x:0,y:-5}, {z:-180,x:260,y:1.5}, {z:-137,x:385,y:12}, {z:0,x:385,y:30}]}, ease:Power1.easeInOut});
        //   TweenMax.from(this.$refs.shadow_4,1,{scale:0.5,rotationY:90})

        //    TweenMax.from(this.$refs.shadow_3,1,{bezier:{type:"cubic",values:[{z:-137,x:236.5,y:12}, {z:0,x:386,y:30}, {z:137,x:386,y:20}, {z:273,x:157.5,y:10}]}, ease:Power1.easeInOut});
        //   TweenMax.from(this.$refs.shadow_3,1,{scale:0.5,rotationY:90})

        //    TweenMax.from(this.$refs.shadow_2,1,{bezier:{type:"cubic",values:[{z:137,x:268.8,y:20}, {z:273,x:157.5,y:10}, {z:350,x:0,y:0}, {z:273,x:-157.5,y:-10}]}, ease:Power1.easeInOut});
        //   TweenMax.from(this.$refs.shadow_2,1,{scale:0.5,rotationY:90})
          next(false)

     },
     beforeRouteEnter(to,from,next){
      next(vm => {
          var  self = vm;
          if(from.path == "/forum" || from.path == "/ask" || from.path == "/category"){
            self.$store.dispatch("changeTransition_Router","ReadToHome")
            self.$store.dispatch("changeTransition_Tool","fade-up")
        }else if(from.path == "/"){
            self.$store.dispatch("changeTransition_Router","HomeToHome")
            self.$store.dispatch("changeTransition_Tool","fade-up")
        }
             self.$store.dispatch("changeToolHome",true)

         var tm0 = new TimelineMax()
         tm0.to(self.$refs.carrousel,0.0001,{onStart:function(){
             self.$eventBus.$emit("changeToolbarMode")
             self.$eventBus.$emit("entireFade","visible")

         }})
        //  .from([self.$refs.shadow_1,self.$refs.shadow_2,self.$refs.shadow_3,self.$refs.shadow_4,self.$refs.shadow_5,self.$refs.shadow_6],.5,{opacity:0})
         .add("scene1")
         .from(self.$refs.shadow_1,1,{bezier:{type:"cubic",values:[{z:273,x:0,y:0}, {z:180,x:-260,y:0}, {z:136.5,x:-380,y:-16}, {z:0,x:-380,y:-31}]}, ease:Power1.easeInOut},"scene1")
        .from(self.$refs.shadow_1,1,{scale:0.5,rotationY:-90,ease:Power1.easeInOut},"scene1")
        .from(self.$refs.shadow_6,1,{bezier:{type:"cubic",values:[{z:136,x:-268.8,y:-20}, {z:0,x:-386,y:-30}, {z:227.5,x:-247,y:-15}, {z:-273,x:-157,y:-10}]}, ease:Power1.easeInOut},"scene1+=.15")
        .from(self.$refs.shadow_6,1,{scale:0.35,rotationY:-90},"scene1+=.15")
        .from(self.$refs.shadow_5,1,{bezier:{type:"cubic",values:[{z:-137,x:-236.5,y:-18}, {z:-273,x:-147,y:-11.5}, {z:-350,x:0,y:-5}, {z:-273,x:157,y:1.5}]}, ease:Power1.easeInOut},"scene1+=.15")
        .from(self.$refs.shadow_5,1,{scale:0.2,rotationY:90},"scene1+=.15")
        .from(self.$refs.shadow_4,1,{bezier:{type:"cubic",values:[{z:-273,x:0,y:-5}, {z:-180,x:260,y:1.5}, {z:-137,x:385,y:12}, {z:0,x:385,y:30}]}, ease:Power1.easeInOut},"scene1+=.15")
        .from(self.$refs.shadow_4,1,{scale:0.4,rotationY:90},"scene1+=.15")
        .from(self.$refs.shadow_3,1,{bezier:{type:"cubic",values:[{z:-137,x:236.5,y:12}, {z:0,x:386,y:30}, {z:137,x:386,y:20}, {z:273,x:157.5,y:10}]}, ease:Power1.easeInOut},"scene1+=.15")
        .from(self.$refs.shadow_3,1,{scale:0.5,rotationY:90},"scene1+=.15")
        .from(self.$refs.shadow_2,1,{bezier:{type:"cubic",values:[{z:137,x:268.8,y:20}, {z:273,x:157.5,y:10}, {z:350,x:0,y:0}, {z:273,x:-157.5,y:-10}]}, ease:Power1.easeInOut},"scene1+=.15")
        .from(self.$refs.shadow_2,1,{scale:0.6,rotationY:90},"scene1+=.15")
        .add("scene2")
        .to(self.$refs.carrousel,0.0000001,{onStart:function()
        {
            self.rotate_per_60-=720;
        }},"scene2")
      })
  }
//      directives: {
//        ClickOutside
//["z","x","rotationY",90,false]}
//   }
}
</script>

<style lang="scss" scoped>

#slideshow{
    margin: 0;
    padding-top:5rem;
    height:100rem;
    width:100%;
    // background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);
    background-image: linear-gradient(to top, #c4c5c7 0%, #dcdddf 52%, #ebebeb 100%);
    display:flex;
    justify-content: center;
    align-items: center;
}

.entire-content{

    width:31.5rem;
    height:31.5rem;
    perspective:100rem;
    position:relative;
    padding-top: 3rem;
}

.content-carrousel{
    width:100%;
    height: 100%;
    position:absolute;
    // float:right;
    transform-style: preserve-3d;
    // transform: skew(15deg,15deg);
    // transform: rotateZ(15deg) rotateX(-7deg);
    transition:transform .5s ease-in-out,right .2s ease-in-out;
    right:0%;

}
.content-carrousel.right-mv{
    transition:transform .5s ease-in-out,right .2s ease-in-out;
    right:35%;

    }

// @keyframes to_right{
//     0%{
//        right:0%;
//     }
//     50%{
//         right:17.5%;
//     }
//     100%{
//        right:35%;
//     }
// }


.content-carrousel figure{
    width:22.5rem;
    height:22.5rem;
    left:4.5rem;//個々のfigure間のgap一つ21rem(gap込み)で本体19rem,gapが片側1rem
    top:4.5rem;
    border: 1px solid #3b444b;
    overflow:hidden;
    position: absolute;

    @for $i from 1 through 6{
        &:nth-child(#{$i}){
            transform:rotateY((0deg+((60deg*$i)-60deg))) translateZ(27.3rem);


        }
    }
}

.rotate_720{
    animation:rotation_720 .4s ease-in-out;
}

@keyframes rotation_720{
    0%{
        transform: rotateY(0);
    }
    100%{
        transform: rotateY(-720deg);
    }
}

.shadow{
    display:flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: absolute;
    // box-shadow: 0px 0px 20px 0px #000;
    border-radius: 100%;
    overflow: hidden;
    color:black;
    // background: #a7a7a7;

    &_1:hover{
        box-shadow: 0px 0px 10px #00ff27;
        text-shadow: 0px 0px 10px #00ff27;
        color:white;

        .shadow__icon--1{
            filter:drop-shadow(0px 0px 10px #00ff27);//svgにはbox-shadowが効かないので
        }
    }

    &_2:hover{
         box-shadow: 0px 0px 10px #ff294d;
         text-shadow: 0px 0px 10px #ff294d;
         color:white;

        .shadow__icon--2{
            filter:drop-shadow(0px 0px 10px #ff294d);//svgにはbox-shadowが効かないので
        }
    }

     &_3:hover{
         box-shadow: 0px 0px 10px #17f1bb;
         text-shadow: 0px 0px 10px #17f1bb;
         color:white;

        .shadow__icon--3{
            filter:drop-shadow(0px 0px 10px #17f1bb);//svgにはbox-shadowが効かないので
        }
    }

      &_4:hover{
         box-shadow: 0px 0px 10px #fffb11;
         text-shadow: 0px 0px 10px #fffb11;
         color:white;

        .shadow__icon--4{
            filter:drop-shadow(0px 0px 10px #fffb11);//svgにはbox-shadowが効かないので
        }
    }

    &_5:hover{
         box-shadow: 0px 0px 10px #1234f7;
         text-shadow: 0px 0px 10px #1234f7;
         color:white;

        .shadow__icon--5{
            filter:drop-shadow(0px 0px 10px #1234f7);//svgにはbox-shadowが効かないので
        }
    }

    &_6:hover{
         box-shadow: 0px 0px 10px #fd0000;
         text-shadow: 0px 0px 10px #fd0000;
         color:white;

        .shadow__icon--6{
            filter:drop-shadow(0px 0px 10px #fd0000);//svgにはbox-shadowが効かないので
        }
    }





    &__icon{
        height: 7rem;
        width:7rem;
        // transform: translate(-15deg,-15deg);
        display: inline-block;
        margin-bottom: 1.6rem;
        fill:currentColor;
    }



    &__text{
       font-size:1.6rem;
    //    transform: translate(-15deg,-15deg);
    }


}




</style>
