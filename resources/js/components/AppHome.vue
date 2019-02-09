<template>
 <div class="entiregrid" ref="grid_master">
  <modal v-if="modalflag" @close="changeModalFlag"></modal>

  <awesome-sidebar class="toolbar-read" v-if="isRead"></awesome-sidebar>
  <toolbar class="toolbar" ref="toolbar_grid" v-if="isHome"></toolbar>


   <router-view :class="comp_view" ref="router-grid"></router-view>


   <transition name="slide-fade">
     <home-description v-if="homeflag && descriptionflag && number" :number="number" class="homedescription"></home-description>
   </transition>
   <app-footer :class="comp_foot"></app-footer>
 </div>
</template>


<script>
import toolbar from "./Toolbar";
import AwesomeSidebar from "./AwesomeSidebar";
import AppFooter from "./AppFooter";
import Login from "./login/Login";
import Modal from "../components/expantion/Modal";
import HomeDescription from "../components/expantion/HomeDescription"
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"



//既にrouter-viewの中にLogincomponentが入っていてその中でroutertoをしてもその行き先がrouter-viewに入るみたい
export default {
 data(){
        return{
            number:"",
            transition_name_router:"",
            transition_name_tool:"",
            isHidden:"",
            Duration:1000
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

 components:{toolbar,AppFooter,Login,Modal,HomeDescription,AwesomeSidebar},//toolbarはtoolbar:toolbarの略

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
    },
    isRead(){
        //Readから出るときはfalseでoff、isHomeはまだtrueにしない、Readに入るときにtrue
        console.log("changed_mode")
        return this.$store.getters.getTool_Read_Mode;
    },
    isHome(){
      //Homeから出るときにfalseでoff、だがisReadはまだtrueにしない、Homeに入るときにtrue,なのでHomeから出るときは両方offで入るときにReadだけonになる、逆も同じ
        console.log("changed_mode")
        return this.$store.getters.getTool_Home_Mode;
    }

},
methods:{
    // ///Entering
    // beforeEnter:function(el){
    //   if(this.$store.getters.getTransitionRouter == "ReadToHome"){
    //       opacity:0
    //   }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
    //       opacity:0
    //   }
    // },
    // enter:function(el,done){
    //   if(this.$store.getters.getTransitionRouter == "ReadToHome"){
    //       let particles = new Particles('.entiregrid');
    //       particles.integrate();
    //   }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
    //       let particles = new Particles('.entiregrid');
    //       particles.integrate();
    //   }
    //   done()
    // },
    // afterEnter:function(el){
    //   if(this.$store.getters.getTransitionRouter == "ReadToHome"){
    //      opacity:1;
    //   }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
    //     opacity:1;
    //   }
    // },
    // enterCancelled:function(el){

    // },//////Leaving
    // beforeLeave:function(el){
    //     console.log("ok")
    //    if(this.$store.getters.getTransitionRouter == "ReadToHome"){
    //      opacity:1;
    //   }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
    //     opacity:1;
    //   }
    // },
    // leave:function(el){
    //     console.log("wow")
    //     if(this.$store.getters.getTransitionRouter == "ReadToHome"){
    //          let particles = new Particles('.entiregrid');
    //       particles.disintegrate();
    //   }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
    //          let particles = new Particles('.entiregrid');
    //       particles.disintegrate();
    //   }
    //     done()
    // },
    // afterLeave:function(el){
    // if(this.$store.getters.getTransitionRouter == "ReadToHome"){
    //      opacity:0;
    //   }else if(this.$store.getters.getTransitionRouter == "HomeToRead"){
    //      opacity:0;
    //   }
    // },
    // leaveCancelled:function(el){

    // },

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
       this.$eventBus.$on("entireFade",(is_visible) => {
           var ToolMode = this.$store.getters.getTransitionTool;
           var ReadMode = this.$store.getters.getTransitionRouter;

           if(is_visible == "invisible" ){
               var tm_fade_invisible = new TimelineMax();
               tm_fade_invisible.to(this.$refs.grid_master,1,{autoAlpha:0})
           }else if(is_visible == "visible"){
                var tm_fade_visible = new TimelineMax();
                // tm_fade.from(this.$refs.grid_master,1,{autoAlpha:0})
                tm_fade_visible.fromTo(this.$refs.grid_master,1,{autoAlpha:0},{autoAlpha:1})

           }
       })
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
    grid-template-columns: 19rem 1fr;
    grid-template-rows: 8rem minmax(100rem,max-content) 10rem;
    position:relative;
    background-image: linear-gradient(to top, #c4c5c7 0%, #dcdddf 52%, #ebebeb 100%);

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
       display:flex;
      flex-direction: column;

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
