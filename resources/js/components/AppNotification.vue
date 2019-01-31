<template>
   <div class="text-xs-center">

      <v-menu offset-y>
        <v-btn icon slot="activator">
           <v-icon :color="color">add_alert</v-icon>{{unreadCount}}
        </v-btn>

      <v-list>
        <v-list-tile v-for=" (item,index) in unread" :key="item.id">
         <router-link :to="item.path">
          <v-list-tile-title @click="readIt(item,index)">{{item.class}} {{item.question}}</v-list-tile-title>
         </router-link>
        </v-list-tile>

        <v-divider></v-divider>

         <v-list-tile v-for="item in read" :key="item.id">
          <v-list-tile-title>{{item.question}}</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>
   </div>
</template>

<script>
export default {
  data(){
      return{
          read:{},
          unread:{},
          unreadCount:0
      }
  },
 created(){
      if(this.$store.getters.userLoggedIn){
          this.getNotifications();
      }

       Echo.private('App.User.' + this.$store.getters.getId)
        .notification((notification) => {
           this.unread.unshift(notification)
           this.unreadCount++
    });
  },
  methods:{
      getNotifications(){
          axios.post("/api/notifications")
          .then(res => {
              console.log(res.data.unread);
              this.read = res.data.read
              this.unread = res.data.unread
              this.unreadCount = res.data.unread.length
          })
          .catch(error => Exception.handle(error))
      },
      readIt(item,index){
          axios.post("/api/markAsRead",{id:item.id})
          .then(res => {
            this.unread.splice(index,1)
            this.read.push(item)
            this.unreadCount--
          })
      }
  },
  computed:{
      color(){
          return this.unreadCount > 0?'red':'red lighten-4'
      }
  }
}
</script>

<style>

</style>
