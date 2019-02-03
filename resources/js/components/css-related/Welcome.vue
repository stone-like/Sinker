<template>
  <section id="slideshow" @click.self="hide">
    <div class="entire-content" @click.self="hide">
      <div :class="comp_content_carrousel" @wheel.prevent="rotar" :style="{transform:'rotateZ(15deg) rotateX(-7deg) rotateY(' + rotate_per_60 + 'deg)'}">
       <figure class="shadow shadow--1"   @click="openDescription(1)">
           <svg class="shadow__icon shadow__icon--1">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-brush" class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Forum</span>
       </figure>
       <figure class="shadow shadow--2"  @click="userLoggedIn?openDescription(2):openDescription(7)">
           <svg class="shadow__icon shadow__icon--2">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-chat" v-if="userLoggedIn" class="shadow__icon--use"></use>
               <use xlink:href="../../Helpers/img/sprite.svg#icon-lock" v-else class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Ask Question</span>
       </figure>
       <figure class="shadow shadow--3" @click="userLoggedIn?openDescription(3):openDescription(7)">
            <svg class="shadow__icon shadow__icon--3">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-cog" v-if="userLoggedIn" class="shadow__icon--use"></use>
               <use xlink:href="../../Helpers/img/sprite.svg#icon-lock" v-else class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Setting</span>
       </figure>
       <figure class="shadow shadow--4"  @click="openDescription(4)">
            <svg class="shadow__icon shadow__icon--4">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-home" class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">Home</span>
        </figure>
       <figure class="shadow shadow--5" @click="userLoggedIn?openDescription(5):openDescription(7)">
           <svg class="shadow__icon shadow__icon--5">
               <use xlink:href="../../Helpers/img/sprite.svg#icon-v-card" v-if="userLoggedIn" class="shadow__icon--use"></use>
               <use xlink:href="../../Helpers/img/sprite.svg#icon-lock" v-else class="shadow__icon--use"></use>
           </svg>
           <span class="shadow__text">User Profile</span>
       </figure>
       <figure class="shadow shadow--6" @click="userLoggedIn?openDescription(6):openDescription(7)">
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
// import ClickOutside from 'vue-click-outside'
export default {
     data(){
        return{
            rotate_per_60:0,
            push_right:false
        }
     },
     computed:{

      ...mapGetters({
           userLoggedIn:'userLoggedIn'//これでログイン状態を監視している、ログインしていなかったらfalse、していればtrue

     }),
     comp_content_carrousel(){
        return this.push_right ? "content-carrousel right-mv" : "content-carrousel";
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
     }
//      directives: {
//        ClickOutside
//   }
}
</script>

<style lang="scss" scoped>
#slideshow{
    margin: 0;
    padding-top:5rem;
    height:100rem;
    width:100%;
    background:linear-gradient(#abbabb,#fff);
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
    
    &--1:hover{
        box-shadow: 0px 0px 10px #00ff27;
        text-shadow: 0px 0px 10px #00ff27;
        color:white;

        .shadow__icon--1{
            filter:drop-shadow(0px 0px 10px #00ff27);//svgにはbox-shadowが効かないので
        }
    }

    &--2:hover{
         box-shadow: 0px 0px 10px #ff294d;
         text-shadow: 0px 0px 10px #ff294d;
         color:white;

        .shadow__icon--2{
            filter:drop-shadow(0px 0px 10px #ff294d);//svgにはbox-shadowが効かないので
        }
    }

     &--3:hover{
         box-shadow: 0px 0px 10px #17f1bb;
         text-shadow: 0px 0px 10px #17f1bb;
         color:white;

        .shadow__icon--3{
            filter:drop-shadow(0px 0px 10px #17f1bb);//svgにはbox-shadowが効かないので
        }
    }

      &--4:hover{
         box-shadow: 0px 0px 10px #fffb11;
         text-shadow: 0px 0px 10px #fffb11;
         color:white;

        .shadow__icon--4{
            filter:drop-shadow(0px 0px 10px #fffb11);//svgにはbox-shadowが効かないので
        }
    }

    &--5:hover{
         box-shadow: 0px 0px 10px #1234f7;
         text-shadow: 0px 0px 10px #1234f7;
         color:white;

        .shadow__icon--5{
            filter:drop-shadow(0px 0px 10px #1234f7);//svgにはbox-shadowが効かないので
        }
    }

    &--6:hover{
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
