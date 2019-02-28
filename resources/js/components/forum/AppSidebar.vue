<template>
  <v-card>
        <v-toolbar class="toolbar_wrapper mt-2" dense>
         <v-toolbar-title class="toolbar_title">Category</v-toolbar-title>
       </v-toolbar>

       <v-list class="toolbar_li">
           <v-list-tile v-for="category in categories" :key="category.id">
               <v-list-tile-content>
                   <v-list-tile-title>{{category.name}}</v-list-tile-title>
               </v-list-tile-content>
           </v-list-tile>
       </v-list>
  </v-card>
</template>

<script>
export default {
  data(){
      return{
         categories:{}
      }
  },
  created(){
      axios.get("/api/category")
      .then(res => this.categories = res.data.data)
  }
}
</script>

<style lang="scss" scoped>
.toolbar_wrapper{
    background-color:  #4E4B42;
    color:#EDE9E3;

}

.toolbar_title{
     font-family: 'Geostar', cursive;
}

.toolbar_li{
    background-color:  #E3DDD3;
}
</style>
