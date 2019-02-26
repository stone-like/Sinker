<template>
    <div class="entire_create_container">
        <form @submit.prevent="create" ref="entire_create" class="entire_create">

        <div :class="comp_wrapper_title">
          <div class="message_wrapper">
            <div class="message_shape" v-if="!title_flag"></div>
            <span class="form_message" v-if="!title_flag">Title:</span>
            <span class="focus_message" v-else>Now entering...</span>
          </div>
        <input
          v-model="form.title"
          type="text"
          required class="create_title" @focus="onFocusTitle" @blur="onBlurTitle">
        </div>

        <div :class="comp_wrapper_category">
        　　<div class="message_wrapper">
            <div class="message_shape"  v-if="!category_flag"></div>
            <span class="form_message"  v-if="!category_flag">Category:</span>
            <span class="focus_message" v-else>Now selecting...</span>
          </div>
         <select v-model="form.category_id"  class="create_select" @focus="onFocusCategory"   @blur="onBlurCategory" @click="changeOpen">
           <option :value="null" disabled selected hidden class="option_placeholder">Select Category</option>
          <option v-for="option in categories" :value="option.id" :key="option.id" class="create_option">
             {{option.name}}
          </option>
         </select>

        </div>

        <div :class="comp_wrapper_tag">
        <div class="message_wrapper">
            <div class="message_shape" v-if="!tag_flag"></div>
            <span class="form_message" v-if="!tag_flag">Tags:</span>
            <span class="focus_message" v-else>Now entering...</span>
          </div>
        <textarea
          name="input"
          v-model="form.tags_string"
          cols="60" rows="5"
          class="create_textarea"
        placeholder="Please enter your tags,make sure comma per one tag" @focus="onFocusTextArea" @blur="onBlurTextArea"></textarea>
        </div>

        <div :class="comp_wrapper_body">
        <div class="message_wrapper">
            <div class="message_shape" v-if="!body_flag"></div>
            <span class="form_message" v-if="!body_flag">Body:</span>
            <span class="focus_message" v-else>Now entering...</span>
          </div>
        <markdown-editor v-model="form.body" @focus.prevent="onFocusBody" @blur.prevent="onBlurBody"></markdown-editor>
        </div>

        <button type="submit"  class="create_button">Create</button>


       </form>
    </div>
</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
  data(){
      return{
          form:{
              title:null,
              category_id:null,
              body:null,
              tags_string:""
          },
          categories:{},
          errors:{},
          tag_flag:false,
          title_flag:false,
          body_flag:false,
          category_flag:false,
          isOpen:false
      }
  },
  created(){
      //categoryをとってきてlaravelのcategoryResouceで設定した通り返ってくるのはidとname、それをitem-valueとかにした
      axios.get("/api/category")
      .then(res => this.categories = res.data.data)
  },
  methods:{
      create(){
        axios.post("/api/question",this.form)
        .then(res => this.$router.push(res.data.path))
        .catch(error => this.errors = error.response.data.error)
      },
      onFocusTextArea(){
           this.tag_flag = true;
      },
      onBlurTextArea(){
           this.tag_flag = false;
      },
      onFocusTitle(){
           this.title_flag = true;
      },
      onBlurTitle(){
           this.title_flag = false;
      },
      onFocusBody(){
           this.body_flag = true;
      },
      onBlurBody(){
           this.body_flag = false;
      },
      onFocusCategory(){
           this.category_flag = true;
      },
      onBlurCategory(){
           this.category_flag = false;
      },
      changeOpen(){
          this.isOpen = !this.isOpen;
      }
  },
  computed:{
      comp_wrapper_title(){
        return this.title_flag ?  "form_wrapper active" :"form_wrapper";
      },
       comp_wrapper_tag(){
        return this.tag_flag ?  "form_wrapper active" :"form_wrapper";
      },
      comp_wrapper_body(){
        return this.body_flag ?  "form_wrapper active" :"form_wrapper";
      },
       comp_wrapper_category(){
        return this.category_flag ?  "form_wrapper active" :"form_wrapper";
      }
  },
   beforeRouteEnter(to,from,next){
      next(vm => {
          var  self = vm;
          //これでgridを全域にまで伸ばす
          self.$eventBus.$emit("changeGridUser",true)

          //sidebarのactiveを外したりつけたり処理するここはforumなのでactiveをforumにつけてfrom.pathの所を外す
          if(from.path == "/forum" || from.path == "/category" || from.path == "/setting" || from.path == "/userprofile" || from.path == "/bookmark"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_enter")
            self.$store.dispatch("changeTransition_Tool","wipe")

         //ReadToReadだからsidebarのchangeはいらない
         self.$store.dispatch("changeToolRead",true)
         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_create,0.0001,{
             onStart:function(){
                 self.$eventBus.$emit("wipeEffectRemove")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_create,0.0001,{
             onStart:function(){
                 //覆いを外したらsidebarをだす
            self.$eventBus.$emit("changeSidebarMode","/ask")

             }
         },"scene1+=0.00001")


        }else if(from.path == "/welcome"){
            self.$store.dispatch("changeTransition_Router","HomeToRead")
            self.$store.dispatch("changeTransition_Tool","fade-side")

         self.$store.dispatch("changeToolRead",true)
         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_create,0.0001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode","/ask")
                 self.$eventBus.$emit("entireFade","visible")

             }
         })
         .add("scene1")
        }

      })
  },
  beforeRouteLeave(to,from,next){
      var self = this;
         self.$eventBus.$emit("changeGridUser",false);

       if(to.path == "/category" ||  to.path == "/forum" || to.path == "/setting" || to.path == "/userprofile" || to.path == "/bookmark"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_leave")
            self.$store.dispatch("changeTransition_Tool","wipe")

            var tm0 = new TimelineMax();

            tm0.to(self.$refs.entire_create,0.00000001,{
             onStart:function(){

                 self.$eventBus.$emit("changeSidebarMode",to.path)
                 //まずこれでsidebarをどける、これが最初
                //  self.$eventBus.$emit("entireFade","invisible")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_create,0.000001,{
             onStart:function(){
                   //ここでwipeするときの色と文字を送る
            if(to.path == "/category"){
                var wipe_array = {name:"CATEGORY",color:"#f39c12"}
            }else if(to.path == "/forum"){
                var wipe_array = {name:"FORUM",color:"#3498db"}
            }else if(to.path == "/setting"){
                var wipe_array = {name:"SETTING",color:"#FDA7DF"}
            }else if(to.path == "/userprofile"){
                 var wipe_array = {name:"USERPROFILE",color:"#8e44ad"}
            }else if(to.path == "/bookmark"){
                 var wipe_array = {name:"BOOKMARK",color:"#2ecc71"}
            }
                  self.$eventBus.$emit("wipeEffectStart",wipe_array)
                //sidebarが引っ込んだらこれをapphomeに送って一面を覆う
             }
         },"scene1+=0.000001")
         .add("scene2")
         .to(self.$refs.entire_create,0.00000001,{
             onStart:function(){
                 //画面を覆うまで一秒にしている
                 //完全にアニメーションが終わったらv-ifでsidebarを消す
                 //ReadToReadだから別に消してもいいはず

                 self.$store.dispatch("changeToolRead",false)
                 next()

             }
         },"scene2+=1")
        }else if(to.path == "/welcome"){
            self.$store.dispatch("changeTransition_Router","ReadToHome")
            self.$store.dispatch("changeTransition_Tool","fade-side")

         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_create,0.00000001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode")
                 self.$eventBus.$emit("entireFade","invisible")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_create,0.00000001,{
             onStart:function(){
                 self.$store.dispatch("changeToolRead",false)
                 next()

             }
         },"scene1+=1")

        }else{
            next()
        }


        next(false)
  }
}
</script>

<style lang="scss" scoped>
// *{
//     font-family: 'Geostar', cursive;
// }
$background-color: #EDE9E3;
$search-bg-color: #242628;

.entire_create_container{
    height: 100%;
    width: 100%;
    background-color: $background-color;
}

.entire_create{
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    padding: 10rem 20rem 5rem 22rem;
}

.create_title{
    font-size: 1.6rem;
    padding: 1rem;
    background-color: #fff;
    outline: none;

}

.create_select{
   font-size: 1.6rem;
    background-color: #fff;
    outline: none;
    height: 5rem;

}

.create_option{
   font-size: 1.6rem;
   outline: none;
}

// .option_placeholder{
//   display: none;
//   font-size: 1.6rem;
//   color:black;
// }

.create_textarea{
   font-size: 1.6rem;
   background-color: #fff;
   outline: none;
}

.create_button{
  font-size: 3rem;
  font-family: 'Geostar', cursive;
  padding: 1rem;
  background-color: #B4AF9A;
    color: #4E4B42;
    width: 18rem;
    align-self: flex-end;
    box-shadow:1px 1px 3px 2px rgb(71, 68, 60);
    transition: all .2s ease;
    &:hover{
         transform: translateY(-10px);
    }
}

.form_wrapper{
    display: flex;
    flex-direction: column;
    margin-bottom: 3rem;
}

.message_wrapper{
    display: flex;
    background-color: #4E4B42;
     color:#EDE9E3;
     padding: 1rem;
     justify-content: center;
}

.message_shape{
    height: 2rem;
    width: 2rem;
    transform: rotate(45deg);
    background-color: #363636;
    margin-right: 2rem;
}

.form_message{
    font-family: 'Geostar', cursive;
    font-size: 3rem;

}

.active{

   .message_wrapper{
       background-color: #363636;

   }
   .focus_message{
       font-family: 'Geostar', cursive;
       color: #CD664D;
       font-size: 3rem;

       animation: Flash 1s infinite;
   }



}

@keyframes Flash{
    50%{
        opacity:0;
    }
}

.message_wrapper{
    transition: all .4s ease-in-out;
}


</style>
