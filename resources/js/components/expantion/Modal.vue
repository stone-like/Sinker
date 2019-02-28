<template>
   <!-- <script type="text/x-template" id="modal-template"> -->
  <!-- <transition name="modal"> -->
    <div class="modal-mask">
      <div class="modal-wrapper">
        <transition tag="div" name="modal" appear>

        <div :class="comp_modal"  ref="clickToClose">
          <div class="modal-header" v-if="!closeflag">
            <!-- <slot name="header">
              default header
            </slot> -->
            {{header}}
          </div>

          <div class="modal-body" v-if="!closeflag">
            <!-- <slot name="body">
              default body
            </slot> -->
            {{body}}
          </div>

          <div class="modal-footer" v-if="!closeflag">
            <div v-if="footer==='like'" class="login_button">
                <a href="/login" class="modal_link">
                   login
                </a>
            </div>
            <div v-if="footer==='delete'">
                <button class="modal-delete-button" @click.prevent="DeleteOn('bookmark')">
                      Delete
                </button>
                <button class="modal-cancel-button" @click.prevent="closeEffect">
                   Cancel
                </button>
            </div>
            <div v-if="footer==='delete_task'">
                <button class="modal-delete-button" @click.prevent="DeleteOn('task')">
                      Delete
                </button>
                <button class="modal-cancel-button" @click.prevent="closeEffect">
                   Cancel
                </button>
            </div>
          </div>

          <button class="modal-default-button" @click.prevent="closeEffect"  v-if="!closeflag&&footer==='like'">
                close
         </button>
        </div>

        </transition>
      </div>
   </div>
  <!-- </transition> -->
<!-- </script> -->

</template>

<script>
import Child_Transition from "./Child_Transition"
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
// import Slot_Modules from "./Slot_Modules"
export default {
    components:{Child_Transition},
    data(){
        return{
          header:"",
          body:"",
          footer:"",
          closeflag:false,
        //   text_close_flag:false
        }
    },
    created(){
        // this.text_close_flag = false
        this.closeflag = false
         this.header = this.$store.getters.getModalHeader
         this.body = this.$store.getters.getModalBody
         this.footer = this.$store.getters.getModalFooter
    },
    methods:{
       closeEffect(){
        //   var  self = this
        //   let tm_modal = new TimelineMax();

        //   , ease: newEase(BezierEasing(.17,.67,.83,.67) )これでcssのeaseと同じにできる
        //   CustomEase.create("custom",".17,.67,.83,.67")
        //   tm_modal.to(self.$refs.clickToClose,.3,{css:{height:'0px'}, ease: "custom"})
        //   .to("html",0.00000000001,{onStart:function(){
        //       self.$emit('close');
        //   }})

        //本文がある中でスムーズに閉じる処理を行うために本文をv-ifで消し終わったらheightを小さくしたい,今文字を消すのもheightを小さくするのもcloseflagでやってしまっているからダメ,
        //v-ifの処理は前もやったんだけど結構遅い？っぽくて順番関係なく時間も遅くしてもだめっぽいだったらcss側でdisplay：noneにしてみる？
        //そういうのじゃなく原因はpaddingがあったからだった、その分が突っかかってしまっていた
        setTimeout(this.emitClose,300)
        this.closeflag = true;
       },
       emitClose(){
           this.$emit('close')
       },
    //    CancelDelete(){
    //        this.$emit('close')
    //    },
       DeleteOn(deletetype){
           this.$eventBus.$emit('startDeleting',deletetype)
           setTimeout(this.emitClose,300)
        this.closeflag = true;
       }
    },
    computed:{
        comp_modal(){
           return this.closeflag ? "modal-container active" :"modal-container";
        }
    }
}
</script>

<style>
.modal-mask {
  position: fixed;
  z-index: 9998;/* これで背景クリックできないように */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: transparent;
  display: table;
  /* transition: opacity .3s ease; */
}


.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  height: 30vh;
  width: 100%;
  margin: 0px;
  /* padding: 3rem 3rem; */
  background-color: rgba(0, 0, 0, .5);
  /* border-radius: 2px; */
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;

  position: relative;
}

.active{
    height: 0;
}

.none{
    display: none;
}

.modal-header{

    position: absolute;
    left:10%;
    top: 10%;
   font-size: 3rem;
   color: azure;
    font-family: 'Geostar', cursive;

}

.modal-body {
  /* margin: 20px 0; */
  position: absolute;
    left:10%;
    top: 30%;
  font-size: 2rem;
  color: azure;
}

.modal-default-button {
   position: absolute;
    right:5%;
    top: 10%;
}

.modal-delete-button{
 position: absolute;
    top: 80%;
    left: 30%;
    transform: translate(-50%,-50%);
    color: azure;
    border: 1px solid azure;
    padding: 2rem 3rem;
}

.modal-cancel-button{
 position: absolute;
    top: 80%;
    left: 70%;
    transform: translate(-50%,-50%);
    color: azure;
    border: 1px solid azure;
    padding: 2rem 3rem;
}

.login_button{
    position: absolute;
    top: 80%;
    left: 50%;
    transform: translate(-50%,-50%);
    color: azure;
    border: 1px solid azure;
    padding: 2rem 3rem;
}

.modal_link{
  text-decoration: none;
  color: azure;
  font-size: 2rem;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

 .modal-enter,
 .modal-leave-to{
   opacity: 0;
   height: 0vh;
 }

 .modal-enter-active,
 .modal-leave-active{
      transition: all .3s ease;
 }

/* .modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;

}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
} */
</style>
