<template>
  <v-container>
        <v-textarea
          box
          name="input-7-4"
          label="Please update or delete your tags,make sure comma per one tag *limit 10*"
          v-model="form.tags_string"
        ></v-textarea>

        <v-btn @click="update">Update</v-btn>
        <v-btn @click="cancel">Cancel</v-btn>
  </v-container>
</template>

<script>
export default {
    props:['tags_array','question'],
    data(){
        return{
          form:{
              tags_string:"",
              category_id:this.question.categoly_id
          }
        }
    },
    methods:{
        update(){
            axios.patch("/api/"+this.question.slug+"/tag",this.form)
            .then(res => this.cancel())
            .catch(error => console.log(error.response.data))
        },
        cancel(){
            this.$eventBus.$emit("cancelEditTag")
        }
    },
    created(){
        this.form.tags_string = this.tags_array.join(',')
        //formの子の値をそのまま入れたいときはthis.tags_stringじゃなくてしっかりthis.form.tags_stringとする

    }
}
</script>

<style>

</style>
