<template>
    <div class="reply_wrapper">

            <div class="upper_wrapper">

               <div class="title_wrapper">
                <div class="image_wrapper">
                    <img :src="data.user_img" class="image_content">
                </div>
                <div class="headline">{{data.user}}</div>
                <div class="ml-2">said {{data.created_at}}</div>
               </div>

                <like :content="data"></like>
            </div>

            <v-divider></v-divider>

           <transition-expand>
            <edit-reply v-if="editting" @canceleditting="cancel" :reply="data"></edit-reply>

            <div v-else>

             <div v-html="reply" class="reply_body"></div>

             <v-divider></v-divider>

             <v-card-actions v-if="own">
                <v-btn icon small @click="edit">
                    <v-icon color="#CD664D">edit</v-icon>
                </v-btn>
                <v-btn icon small @click="destroy">
                    <v-icon color="#363636">delete</v-icon>
                </v-btn>
             </v-card-actions>
            </div>

            </transition-expand>

    </div>
</template>

<script>
import EditReply from "./EditReply"
import Like from "../likes/Like"
import TransitionExpand from '../transition-effects/TransitionExpand'
import DeleteQuestionModal from "../Modals/DeleteQuestionModal"

export default {
props:['data','index'],
components:{EditReply,Like,TransitionExpand},
data(){
    return{
        own:this.$store.getters.checkown(this.data.user_id),
        editting:false
    }
},
created(){

},
methods:{
    openModal(){
         return new Promise((resolve) => {

                const DeleteConstructor = Vue.extend(DeleteQuestionModal);//コンストラクタ化

              const vm2 = new DeleteConstructor({
               propsData:{
                "header":"Notification",
                "body":"You are going to delete this reply,Are you sure to delete?",
                success:() => resolve(true),
                failure:() => resolve(false)
                   }
              });//インスタンスを生成
             vm2.$mount();//一旦マウントして
            document.getElementById('app').appendChild(vm2.$el);
              })
    },
    async destroy(){
        const result = await this.openModal();

        if(result === true){
            this.$eventBus.$emit("deleteReply",this.index)
        }else{
            //cancelならなにもしない
        }
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

<style lang="scss" scoped>

.reply_wrapper{
    border: 1px solid #333;
}

.upper_wrapper{
    display: flex;
}

.title_wrapper{
    display: flex;
    margin-right: auto;
    align-items: center;
    margin-left: 1rem;
}

.image_wrapper{
    // height: 4rem;
    // width: 4rem;
    // border-radius: 50%;
    // object-fit: cover;
     display: flex;
   justify-content: center;
   align-items: center;
   margin-right: 1rem;
}
.image_content{
     height: 4rem;
    width: 4rem;
    border-radius: 50%;
    object-fit: cover;
}

.reply_body{
    font-size:2rem;
    color: #363636;
    padding: .5rem 0 .5rem 1rem;

}
</style>
