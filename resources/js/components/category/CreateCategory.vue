<template>
 <v-container ref="entire_category">
     <v-form @submit.prevent="submit">
      <v-text-field label="Category"
         v-model="form.name"
         type="text"
          required>
      </v-text-field>
      <span class="red--text" v-if="errors.name">{{errors.name[0]}}</span>

      <v-btn type="submit" color="pink" v-if="editFlag">Update</v-btn>
       <v-btn type="submit" color="teal" v-else>Create</v-btn>
     </v-form>

     <v-card>
         <v-toolbar color="indigo" dark dense>
             <v-toolbar-title>Categories</v-toolbar-title>
         </v-toolbar>
         <v-list>
             <div  v-for="(category,index) in categories" :key="category.id">
               <v-list-tile>
                 <v-list-tile-action>
                     <v-btn icon small @click="edit(index)">
                           <v-icon color="orange">edit</v-icon>
                     </v-btn>
                 </v-list-tile-action>

                 <v-list-tile-content>
                     <v-list-tile-title>
                         {{category.name}}
                     </v-list-tile-title>
                 </v-list-tile-content>

                 <v-list-tile-action>
                     <v-btn icon small @click="destroy(category.slug,index)">
                           <v-icon color="red">delete</v-icon>
                     </v-btn>
                 </v-list-tile-action>
             </v-list-tile>

             <v-divider></v-divider>
             </div>

         </v-list>
     </v-card>
 </v-container>
</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
 data(){
     return{
         form:{
             name:null
         },
         errors:[],
         categories:{},
         editSlug:null,
         currentindex:null,
         editFlag:false
     }
 },
 methods:{

     submit(){
         this.editFlag ? this.update() : this.create();
     },
     update(){
        this.categories.splice(this.currentindex,1);
        axios.patch("/api/category/"+this.editSlug,this.form)
        .then(res => {
             this.categories.unshift(res.data)
             this.form.name = null
         })
         .catch(error => this.errors = error.response.data.errors)
     },
     create(){

       axios.post("/api/category",this.form)
         .then(res => {
             this.categories.unshift(res.data)
             this.form.name = null
         })
         .catch(error => this.errors = error.response.data.errors)
     },
     destroy(slug,index){
        axios.delete("/api/category/"+slug)
        .then(res => this.categories.splice(index,1))
     },
     edit(index){
         this.editFlag = !this.editFlag;
         if(this.editFlag){
             this.form.name = this.categories[index].name;
             this.editSlug = this.categories[index].slug;
             this.currentindex = index;
         }
         else{
             this.form.name = null;
         }

     }
 },
 created(){
      axios.get("/api/category")
      .then(res => this.categories = res.data.data)
  },
  beforeRouteEnter(to,from,next){
      next(vm => {
          var  self = vm;
          //sidebarのactiveを外したりつけたり処理するここはforumなのでactiveをforumにつけてfrom.pathの所を外す
          if(from.path == "/forum" || from.path == "/ask"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_enter")
            self.$store.dispatch("changeTransition_Tool","wipe")

         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_category,0.0001,{
             onStart:function(){
                 self.$eventBus.$emit("wipeEffectRemove")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_category,0.0001,{
             onStart:function(){
                 //覆いを外したらsidebarをだす
            self.$eventBus.$emit("changeSidebarMode","/category")

             }
         },"scene1+=0.00001")


        }else if(from.path == "/welcome"){
            self.$store.dispatch("changeTransition_Router","HomeToRead")
            self.$store.dispatch("changeTransition_Tool","fade-side")

         self.$store.dispatch("changeToolRead",true)
         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_category,0.0001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode","/category")
                 self.$eventBus.$emit("entireFade","visible")

             }
         })
         .add("scene1")
        }

      })
  },
  beforeRouteLeave(to,from,next){
      var self = this;

       if(to.path == "/ask" ||  to.path == "/forum"){
            self.$store.dispatch("changeTransition_Router","ReadToRead_leave")
            self.$store.dispatch("changeTransition_Tool","wipe")

            var tm0 = new TimelineMax();

            tm0.to(self.$refs.entire_category,0.00000001,{
             onStart:function(){

                 self.$eventBus.$emit("changeSidebarMode",to.path)
                 //まずこれでsidebarをどける、これが最初
                //  self.$eventBus.$emit("entireFade","invisible")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_category,0.000001,{
             onStart:function(){
                   //ここでwipeするときの色と文字を送る
            if(to.path == "/ask"){
                var wipe_array = {name:"QUESTION",color:"#c0392b"}
            }else if(to.path == "/forum"){
                var wipe_array = {name:"FORUM",color:"#3498db"}
            }
                  self.$eventBus.$emit("wipeEffectStart",wipe_array)
                //sidebarが引っ込んだらこれをapphomeに送って一面を覆う
             }
         },"scene1+=0.000001")
         .add("scene2")
         .to(self.$refs.entire_category,0.00000001,{
             onStart:function(){
                 //画面を覆うまで一秒にしている
                 //完全にアニメーションが終わったらv-ifでsidebarを消す
                 //ReadToReadだから別に消してもいいはず

                //  self.$store.dispatch("changeToolRead",false)
                 next()

             }
         },"scene2+=1")
        }else if(to.path == "/welcome"){
            self.$store.dispatch("changeTransition_Router","ReadToHome")
            self.$store.dispatch("changeTransition_Tool","fade-side")

         var tm0 = new TimelineMax();

         tm0.to(self.$refs.entire_category,0.00000001,{
             onStart:function(){
                 self.$eventBus.$emit("changeSidebarMode")
                 self.$eventBus.$emit("entireFade","invisible")

             }
         })
         .add("scene1")
         .to(self.$refs.entire_category,0.00000001,{
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

