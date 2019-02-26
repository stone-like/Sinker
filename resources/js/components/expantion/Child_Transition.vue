<template>
    <transition tag="div" name="modal" appear>

        <div class="modal-container" >
          <div class="modal-header">
            <!-- <slot name="header">
              default header
            </slot> -->
            {{header}}
          </div>

          <div class="modal-body">
            <!-- <slot name="body">
              default body
            </slot> -->
            {{body}}
          </div>

          <div class="modal-footer">
            <!-- <slot name="footer">
              default footer -->
              <button class="modal-default-button" @click.prevent="close">
                close
              </button>
            <!-- </slot> -->
          </div>
        </div>

        </transition>
</template>

<script>
export default {
 data(){
        return{
          header:"",
          body:"",
        }
    },
    created(){
         this.header = this.$store.getters.getModalHeader
         this.body = this.$store.getters.getModalBody
    },
    methods:{
        close(){
            this.$eventBus.$emit('closeModal');
        }
    }
}
</script>

<style lang="scss" scoped>
.modal-container {
  height: 30vh;
  width: 100%;
  margin: 0px;
  padding: 20px 30px;
  background-color: rgba(0, 0, 0, .5);
  /* border-radius: 2px; */
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
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
