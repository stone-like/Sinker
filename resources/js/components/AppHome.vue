<template>

 <div class="entiregrid">
  <modal v-if="modalflag" @close="changeModalFlag"></modal>

  <toolbar :class="comp_tool" ref="toolbar"></toolbar>


  <transition :name="transition_router"  @leave="leaveEl" :after-appear="leaveEl" appear>
   <router-view :class="comp_view"></router-view>
  </transition>

   <transition name="slide-fade">
     <home-description v-if="homeflag && descriptionflag && number" :number="number" class="homedescription"></home-description>
   </transition>
   <app-footer :class="comp_foot"></app-footer>
 </div>
</template>

<script src="dist/particles.min.js"></script>
<script>
import toolbar from "./Toolbar";
import AppFooter from "./AppFooter";
import Login from "./login/Login";
import Modal from "../components/expantion/Modal";
import HomeDescription from "../components/expantion/HomeDescription"
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
import anime from 'animejs'

//既にrouter-viewの中にLogincomponentが入っていてその中でroutertoをしてもその行き先がrouter-viewに入るみたい
export default {
 data(){
        return{
            number:"",
            transition_name_router:"",
            transition_name_tool:""
        }
    },
    // beforeRouteUpdate(to,from,next){
    //     console.log(to.params.name)
    //       console.log(from.params.name)
    //   if(to.params.name == ("/forum" || "/ask" || "/category") && form.params.name == "/welcome"){
    //      this.transition_name_router = "HomeToRead"
    //      this.transition_name_tool = "fadeup"

    //   } else if(from.params.name == ("/forum" || "/ask" || "/category") && to.params.name == "/welcome"){
    //       this.transition_name_router = "ReadToHome"
    //       this.transition_name_tool = "fadeleft"
    //   } else if(from.params.name == ("/forum" || "/ask" || "/category") && to.params.name == ("/forum" || "/ask" || "/category")){
    //       this.transition_name_router = "ReadToRead";
    //       this.transition_name_tool = "wipe"
    //   }

 components:{toolbar,AppFooter,Login,Modal,HomeDescription},//toolbarはtoolbar:toolbarの略

created(){
    this.listen()
},
computed:{
    modalflag(){
        return this.$store.getters.getModalFlag;
    },
    homeflag(){
        return this.$store.getters.getHomeFlag;
    },
    descriptionflag(){
       return this.$store.getters.getDescriptionFlag;
    },
    comp_tool(){
        //コンポーネントから、例えばwelcomeから出るときはfalseのままでforumに入ったらそこのbeforeenterhookでtrueに切り替える
        return this.$store.getters.getTool_Read_Mode ? "toolbar-read" :"toolbar";
    },
    comp_view(){
        return this.$store.getters.getTool_Read_Mode? "router-view-read" : "router-view";
    },
    comp_foot(){
        return this.$store.getters.getTool_Read_Mode ? "footer-read" : "footer";
    },
    transition_toolbar(){
        return this.$store.getters.getTransitionTool;
    },
    transition_router(){
        return this.$store.getters.getTransitionRouter;
    }

},
methods:{
    ///Entering
    beforeEnter:function(el){
      if(this.$store.getters.getTransitionRouter == "ReadToHome"){
          opacity:0
      }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
          opacity:0
      }
    },
    enter:function(el,done){
      if(this.$store.getters.getTransitionRouter == "ReadToHome"){
          let particles = new Particles('.entiregrid');
          particles.integrate();
      }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
          let particles = new Particles('.entiregrid');
          particles.integrate();
      }
      done()
    },
    afterEnter:function(el){
      if(this.$store.getters.getTransitionRouter == "ReadToHome"){
         opacity:1;
      }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
        opacity:1;
      }
    },
    enterCancelled:function(el){

    },//////Leaving
    beforeLeave:function(el){
       if(this.$store.getters.getTransitionRouter == "ReadToHome"){
         opacity:1;
      }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
        opacity:1;
      }
    },
    leave:function(el){
        if(this.$store.getters.getTransitionRouter == "ReadToHome"){
             let particles = new Particles('.entiregrid');
          particles.disintegrate();
      }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
             let particles = new Particles('.entiregrid');
          particles.disintegrate();
      }
        done()
    },
    afterLeave:function(el){
    if(this.$store.getters.getTransitionRouter == "ReadToHome"){
         opacity:0;
      }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
         opacity:0;
      }
    },
    leaveCancelled:function(el){

    },

    changeModalFlag(){
        this.$store.dispatch('changeModalFlag');
    },
    listen(){
        this.$eventBus.$on("openDescription",(number) =>{
             this.number=number;
             this.$store.dispatch("onDescriptionFlag");
             console.log(this.$store.getters.getDescriptionFlag);

        })

        this.$eventBus.$on("closeDescription",(number) =>{
             this.$store.dispatch("offDescriptionFlag");
             console.log(this.$store.getters.getDescriptionFlag);
    })

    this.$eventBus.$on("changeToolbarMode",() => {
               var ToolMode = this.$store.getters.getTransitionTool;
              if(ToolMode == "fade-up"){
                var tm_tool1 = new TimelineMax();
　　　　　　　　　　　tm_tool1.to(this.$refs.toolbar,1,{y:-200,opacity:0,css:{'grid-row':"1/1"}})　　　
              }
              else if(ToolMode == "fade-side"){

              }
              else if(ToolMode == "wipe"){

              }
          })
  },
  beforeDestroy(){
    this.$eventBus.$off("changeToolbarMode")
  },
  leaveEl(el){

  }

 }
}
</script>

<style lang="scss" scoped>
.slide-fade-enter-active{
    transition:all .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-leave-active{
    transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter , .slide-fade-leave-to{
   transform:translateX(150px);
   opacity:0;
}

.fade-up-enter-active{
    //これがwelcomeに来たときの挙動
    transition:all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.fade-up-enter{
    //これがwelcomeに来る前の挙動
    transform: translateY(-300px);
    opacity: 0;
}

.fade-up-leave-active{
    //これがwelcomeから出るときの挙動
    transition:all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.fade-up-leave_to{
    //これがwelcomeから出たあとの挙動
    transform: translateY(-300px);
    opacity: 0;
}

.fade-side-enter-active{
    //これがReadmodeに来たときの挙動
    transition:all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.fade-side-enter{
    //これがReadmodeに来る前の挙動
    transform: translateX(-300px);
    opacity: 0;
}

.fade-side-leave-active{
    //これがreadからhomeに出るときの挙動
    transition:all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.fade-side-leave_to{
    //これがreadからhomeに出たあとの挙動
    transform: translateX(-300px);
    opacity: 0;
}

.entiregrid{
    display:grid;
    grid-template-columns: 13rem 1fr;
    grid-template-rows: 8rem 1fr 10rem;
    position:relative;
}
.toolbar{

       grid-column: 1/3;
       grid-row:1/2;
       position: fixed;
       top: 0;
       height: 8rem;
       width:100%;
       z-index: 1;
       background-color: #fff;

}
.toolbar-read{
       grid-column:1/1;
       grid-row:1/3;

}
.router-view{
        grid-column:1/-1;
        grid-row:2/3;
}

.router-view-read{
      grid-column:2/-1;
      grid-row:1/3;
}
.footer{
        grid-column:1/-1;
        grid-row:3/4;
}
.footer-read{
      grid-column:1/-1;
      grid-row:3/4;
}
.homedescription{
    position:absolute;
    top:0;
    right:0;
    z-index:99;
    width:34vw;
    grid-row: 1/3;
}
</style>
