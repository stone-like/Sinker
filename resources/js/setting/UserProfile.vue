<template>
  <div class="entire_user_profile">
        <div class="upper_area-profile" ref="upper_area-profile" id="upper_triagle-profile">
            <span id="upper_message-profile">
              User Profile
            </span>
        </div>

        <div class="upper_area-name" ref="upper_area-name" id="upper_triagle-name">
            <div id="upper_message-name">
              User Profile
            </div>
        </div>

        <div class="center_infomation">

        </div>


        <div class="user_image">
            <img src="../Helpers/img/sks.jpeg">
        </div>


  </div>
</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {

    beforeRouteEnter(to,from,next){
        next(vm => {
            console.log("enter")
          var  self = vm;
        self.$eventBus.$emit("changeGridUser",true);
        // self.$eventBus.$emit("invisivleSidebar",true);

        var tm_user_profile = new TimelineMax();
        tm_user_profile
        .add("scene1")


        // clip-path: polygon(100% 0, 0 0, 0 100%)　upperstartpoints、これをarrayに落とし込むと[100,0,0,0,0,100]

        //clip-path: polygon(100% 0, 0 0, 0 40%); upperendpoints

        // var arr1 = [100,0,0,0,0,100];
        // var arr2 = [100,0,0,0,0,40];

        // tm_user_profile.to(arr1, 3, { endArray: arr2, onUpdate: clipPath});

        // function clipPath() {

        // TweenMax.set("#upper_triagle", { webkitClipPath: 'polygon('+
        // arr1[0]+'%'+arr1[1]+'%,'+
        // arr1[2]+'%'+arr1[3]+'%,'+
        // arr1[4]+'%'+arr1[5]+'%)'
//   });
//     console.log(arr1);
// }



        // tm_user_profile
        // .add("scene1")
        // .to(self.$refs.upper_area,.8,{y:-200,},"scene1")
        // .to(self.$refs.lower_area,.8,{y:200},"scene1")
        })
   },
   beforeRouteLeave(to,from,next){
    this.$eventBus.$emit("changeGridUser",false);
    next();
   }
}
</script>

<style lang="scss" scoped>

$background-color: #2A2E37;
$search-bg-color: #242628;

.entire_user_profile{
    height: 100%;
    width: 100%;
    background-color: $background-color;
    position: relative;
}

.upper_area-profile{

     background-color: #BF0000;
     height: 7rem;
     width: 60rem;
     top:5%;
     left:-5%;
     z-index: 1;
    //  clip-path: polygon(100% 0, 0 0, 0 100%);
     position: absolute;
     display: flex;
     justify-content: center;
     align-items: center;
}

#upper_message-profile{
     font-size: 4rem;
     color: white;
     font-family: 'Montserrat', sans-serif;
     position: relative;
     text-transform: uppercase;

}

.user_image{
    position: absolute;
    bottom:0;
    right: 0;
    overflow: hidden;

    img{
        object-fit: cover;
    }
}

// .lower_area{
//     background-color: #BF0000;
//      height: 100%;
//     width: 100%;
//     bottom: 0;
//     right: 0;
//      z-index: 1;
//     //  clip-path: polygon(100% 0, 100% 100%, 0 100%);
//      position: absolute;
// }


</style>
