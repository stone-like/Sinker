<template>
    <v-container>
        <v-form @submit.prevent="create" ref="entire_create">

        <v-text-field label="Title"
          v-model="form.title"
          type="text"
          required>
        </v-text-field>

        <v-select
         :items="categories"
         item-text="name"
         item-value="id"
         v-model="form.category_id"
         label="Category"
         autocomplete>

        </v-select>

        <markdown-editor v-model="form.body" ></markdown-editor>

        <v-btn color="green" type="submit">Create</v-btn>


       </v-form>
    </v-container>
</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
  data(){
      return{
          form:{
              title:null,
              category_id:null,
              body:null
          },
          categories:{},
          errors:{}
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
      }
  },
   beforeRouteEnter(to,from,next){
      next(vm => {
          var  self = vm;
          //sidebarのactiveを外したりつけたり処理するここはforumなのでactiveをforumにつけてfrom.pathの所を外す
          if(from.path == "/forum" || from.path == "/category"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_enter")
            self.$store.dispatch("changeTransition_Tool","wipe")

        self.$store.dispatch("changeToolRead",true)
         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_create,0.0001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode","/ask")
                 self.$eventBus.$emit("entireFade","visible")

             }
         })
         .add("scene1")

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

       if(to.path == "/category" ||  to.path == "/forum"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_leave")
            self.$store.dispatch("changeTransition_Tool","wipe")

            var tm0 = new TimelineMax();

            tm0.to(self.$refs.entire_create,0.00000001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode",to.path)
                 self.$eventBus.$emit("entireFade","invisible")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_create,0.00000001,{
             onStart:function(){
                 //完全にアニメーションが終わったらv-ifでsidebarを消す
                 self.$store.dispatch("changeToolRead",false)
                 next()

             }
         },"scene1+=1")
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

        }


        next(false)
  }
}
</script>

<style>

</style>
