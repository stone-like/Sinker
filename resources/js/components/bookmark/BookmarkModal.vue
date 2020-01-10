<template>

   <div class="modal-mask">
      <div class="modal-wrapper">
        <transition tag="div" name="modal" appear>

        <div :class="comp_modal"  ref="clickToClose">
          <div class="modal-header">
            <!-- <slot name="header">
              default header
            </slot> -->
             Select Bookmark
          </div>

          <div class="modal-body">
            <ul class="bookmark_ul" v-model="selected_bookmark">
              <li v-for="bookmark in bookmarks" :key="bookmark.id" @click.prevent="selected_bookmark=bookmark" class="bookmark_li">
                <button class="bookmark_button">
                 {{bookmark.name}}
                 </button>
              </li>
            </ul>

          </div>

          <div class="modal-footer">
                <button class="modal-delete-button" @click.prevent="Confirm()">
                      AddBookmark
                </button>
                <button class="modal-cancel-button" @click.prevent="Close()">
                   Cancel
                </button>
          </div>
        </div>

        </transition>
      </div>
   </div>
</template>

<script>
export default {
  props:['bookmarks','success','failure'],
  data(){
    return{
        active:false,
        resolve:null,
        selected_bookmark:""
    }
  },
  computed:{
    comp_modal(){
        return this.active ? "modal-container active" : "modal-container";
    }
  },
  methods:{
    //   pop(){
    //       var self = this;
    //       //activeはtransitionの時にcssをいじる用？
    //      self.active = false;
    //      return new Promise(function(resolve,reject){
    //          //ここに.then以降で使いたい値を入れる？
    //         //  this.resolve = resolve;
    //           self.resolve(self.selected_bookmark)
    //           self.resolve = resolve
    //      })
    //   },
      Confirm(){
          this.$eventBus.$emit("startAddTask",this.selected_bookmark);
         this.active = true;
         this.dismount();
      },
      Close(){
        this.active = true;//このactiveを変えるとtransitioneffectで.3sかかる
        // で、closeを呼ぶとdismountをするのでそれをするとすぐ消えてしまうので.3s後にdismountしたい

        // this.failure(false);
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
  }
}
</script>

<style lang="scss" scoped>
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
  height: 70vh;
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

 .modal-enter,
 .modal-leave-to{
   opacity: 0;
   height: 0vh;
 }

 .modal-enter-active,
 .modal-leave-active{
      transition: all .3s ease;
 }

 .bookmark_ul{
   list-style: none;
 }

 .bookmark_li{
   
 }

 .bookmark_button{
   cursor: pointer;
   margin-bottom: 1rem;
    padding: 2rem 3rem;
   outline:none;
   &:focus{
     border: 2px solid azure;
   }
 }
</style>
