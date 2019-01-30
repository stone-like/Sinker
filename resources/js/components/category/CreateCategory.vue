<template>
 <v-container>
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
  }
}
</script>

<style>

</style>

