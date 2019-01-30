<template>
    <div>
        <v-card>
            <v-card-title>
                <div class="headline">{{data.user}}</div>
                <div class="ml-2">said {{data.created_at}}</div>

                <v-spacer></v-spacer>
                <like :content="data"></like>
            </v-card-title>

            <v-divider></v-divider>

            <edit-reply v-if="editting" @canceleditting="cancel" :reply="data"></edit-reply>

            <div v-else>

             <v-card-text v-html="reply"></v-card-text>

             <v-divider></v-divider>

             <v-card-actions v-if="own">
                <v-btn icon small @click="edit">
                    <v-icon color="orange">edit</v-icon>
                </v-btn>
                <v-btn icon small @click="destroy">
                    <v-icon color="black">delete</v-icon>
                </v-btn>
             </v-card-actions>
            </div>
        </v-card>
    </div>
</template>

<script>
import EditReply from "./EditReply"
import Like from "../likes/Like"
export default {
props:['data','index'],
components:{EditReply,Like},
data(){
    return{
        own:this.$store.getters.checkown(this.data.user_id),
        editting:false
    }
},
methods:{
    destroy(){
        this.$emit("deleteReply",this.index)
    },
    edit(){
      this.editting = true
    },
    cancel(){
      this.editting = false
    }
},
computed:{
    reply(){
        return md.parse(this.data.reply)
    }
}
}
</script>

<style>

</style>
