<template>

<div class="entire_wrapper">
   <form @submit.prevent="update">
        <v-text-field label="Title"
          v-model="form.title"
          type="text"
          required>
        </v-text-field>

        <markdown-editor v-model="form.body" ></markdown-editor>

        <v-card-actions>
            <v-btn icon small type="submit">
                <v-icon color="#CD664D">save</v-icon>
            </v-btn>

             <v-btn icon small  @click="cancel">
                <v-icon color="#363636">cancel</v-icon>
            </v-btn>
        </v-card-actions>
   </form>
</div>


</template>

<script>
export default {
props:['data'],
 data(){

     return {
         form:{
             title:null,
             data:null
         }
      }
},
 methods:{
     cancel(){
         this.$emit("startcanceling");
     },
     update(){

         axios.patch("/api/question/"+this.data.slug,this.form)
         .then(res => this.cancel())
         .catch(error => console.log(error.response.data))
     }
 },
 created(){
    this.form = this.data;//editの時に今のデータを表示画面に映しておく
 }
}
</script>

<style lang="scss" scoped>
.entire_wrapper{
    background-color: transparent;
    padding: 2rem;
    border: 1px solid #333;
}

</style>
