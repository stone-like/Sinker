<template>
    <div class="modal-mask">
      <div class="modal-wrapper">
        <transition tag="div" name="modal" appear>

        <div :class="comp_modal">
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
                <button class="modal-delete-button" @click.prevent="DeleteOn('bookmark')">
                      Delete
                </button>
                <button class="modal-cancel-button" @click.prevent="Cancel">
                   Cancel
                </button>
          </div>
        </div>

        </transition>
      </div>
   </div>
  <!-- </transition> -->
<!-- </script> -->

</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
// import Slot_Modules from "./Slot_Modules"
export default {
    props:['header','body','success','failure'],
    data(){
        return{
          closeflag:false,
        //   text_close_flag:false
        }
    },
    methods:{

       DeleteOn(){
        //    this.$eventBus.$emit('startDeleting',deletetype)
          this.closeflag = true;
          this.success();
          this.dismount();
       },
       Cancel(){
           this.closeflag = true;
           this.failure();
           this.dismount();
       },
      dismount(){
          console.log(this.$el)//ここのこと

          console.log(this.$el.parentNode)//ここのparent,つまりapp
         setTimeout(() => {
             this.$el.parentNode.removeChild(this.$el);
             this.$destroy();
         },300)
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


</style>
