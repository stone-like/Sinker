<template>
  <div class="entire_user_profile">
        <div :class="comp_upper_area" ref="upper_area-profile" id="upper_triagle-profile">
            <span id="upper_message-profile" class="items">
              {{this.$store.getters.getUser}}
            </span>

            <router-link to="/welcome" class="link_to">
              Home
            </router-link>
            <router-link to="/forum" class="link_to">
              Forum
            </router-link>
            <span id="upper_message-timenow">
                {{comp_timenow}}
            </span>
        </div>


        <div class="main_menu">

        <transition-group tag="div" name="list" :class="comp_list">

              <div class="items main_menu_message" key="message">
                   {{current_route}}
              </div>
              <div class="notification_count" key="notification_count" v-if="this.current_route =='Notification'">
                  {{unreadCount + (unreadCount > 0 ? "unreads" : "unread")}}
              </div>

               <div class="items route_current" key="route_current">

                    <svg class="route_icon">
                         <use xlink:href="../Helpers/img/sprite4.svg#icon-triangle-right"></use>
                    </svg>
                    <span class="route_text">
                      {{current_route}}
                    </span>
              </div>

              <div class="items route_next" @click="route_change_next" key=route_next>

                    <!-- <svg class="route_icon">
                         <use xlink:href="../Helpers/img/sprite4.svg#icon-triangle-right"></use>
                    </svg> -->
                    <span class="route_text">
                      {{next_route}}
                    </span>
              </div>

              <div class="items route_from" @click="route_change_prev" key=route_from>

                    <!-- <svg class="route_icon">
                         <use xlink:href="../Helpers/img/sprite3.svg#icon-triangle-right"></use>
                    </svg> -->
                    <span class="route_text">
                      {{prev_route}}
                    </span>
              </div>

              <div class="items main_menu_body" key="body">
                  <transition-group tag="ul" name="notification_list" class="notification" v-if='current_route =="Notification"'>
                       <li v-for="(item,index) in unread" :key="item.id" class="unread_item">
                           <div class="unread_color">
                           </div>
                           <router-link :to="item.path" class="unread_link">
                               <div @click="readIt(item,index)" key="unread">
                                <div class="item_class">
                                 {{item.class}}
                                 {{item.created_at['date']}}
                                </div>
                                <div class="item_title">
                                 {{item.question}}
                                </div>
                                <div class="item_replyBy">
                                 replyBy {{item.replyBy}}
                                </div>
                               </div>
                           </router-link>
                       </li>
                  </transition-group>
                  <transition-group tag="ul" name="notification_list" class="notification" v-if='current_route =="Notification"'>
                       <li v-for="item in read" :key="item.id" class="read_item">
                               <div class="read_color">
                               </div>
                               <div key="read">
                                <div class="item_class">
                                 {{item.class}}
                                {{item.created_at['date']}}
                                </div>
                                <div class="item_title">
                                 {{item.question}}
                                </div>
                                <div class="item_replyBy">
                                replyBy {{item.replyBy}}
                                </div>
                               </div>
                       </li>
                  </transition-group>

                  <div v-if='current_route =="User_status"'>


                      <div class="user_items">
                          <div class="user_pre">
                              entered
                          </div>
                          <div class="user_post">
                           {{user.created_at['date']}}
                          </div>
                      </div>
                      <div class="user_items">
                          <div class="user_pre">
                              email
                          </div>
                          <div class="user_post">
                          {{user.email}}
                          </div>
                      </div>
                      <div class="user_items">
                          <div class="user_pre">
                              Total_Likes
                          </div>
                          <div class="user_post">
                          {{user.total_likes}}
                          </div>
                      </div>
                      <div class="user_items">
                          <div class="user_pre">
                              Total_Posts
                          </div>
                          <div class="user_post">
                          {{user.total_posts}}
                          </div>
                      </div>
                      <div class="user_items">
                          <div class="user_pre">
                              Total_Replies
                          </div>
                          <div class="user_post">
                          {{user.total_replies}}
                          </div>
                      </div>


                  </div>

                  <div class="recent_activity" v-if='current_route =="Activity"'>
                       <ul class="recent_posts">
                              <span>Recent_Posts</span>
                          <li v-for="post in recent_posts" :key="post.id">
                              <div class="post_title">
                               {{post.title}}
                              </div>
                              <div class="post_body">
                               {{post.body}}
                              </div>
                          </li>
                       </ul>
                       <ul class="recent_replies">
                           <span>Recent_Reolies</span>
                           <li v-for="reply in recent_replies" :key="reply.id">
                               <div class="reply_title">
                                {{reply.title}}
                               </div>
                               <div class="reply_body">
                                {{reply.body}}
                               </div>
                           </li>
                       </ul>
                  </div>
              </div>

        </transition-group>


        </div>







  </div>
</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
    data(){
        return{
            timenow:"",
            unread:"",
            read:"",
            unreadCount:0,
            user:{},
            next_route:"User_status",
            prev_route:"Activity",
            current_route:"Notification",
            recent_replies:{},
            recent_posts:{}
        }
    },
    mounted(){
       this.getNotifications();
       this.starttime_val();
       this.getUser();
       this.getRecent();

        Echo.private('App.User.' + this.$store.getters.getId)
        .notification((notification) => {
           this.unread.unshift(notification)
           this.unreadCount++
    });
    },
    methods:{

        set_timeval(){
            console.log("count")
            let time = new Date();
            this.timenow = time.toLocaleTimeString();
        },
        starttime_val(){
            var self = this;
            let time = new Date();
            this.timenow = time.toLocaleTimeString();
             self.intervalid = setInterval(function(){self.set_timeval()},1000);//clearIntervalをbeforeleaveにつけること
        },
        getNotifications(){
          axios.post("/api/notifications")
          .then(res => {
              console.log(res.data)
              if(res.data.read.length > 12){
                  this.read = res.data.read.slice(0,12);
              }else{
                  this.read = res.data.read
              }
              if(res.data.unread.length > 12){
                  this.unread = res.data.unread.slice(0,12);
              }else{
                  this.unread = res.data.unread
              }
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
      },
      getUser(){
          axios.get("/api/user")
          .then(res => {
              console.log(res.data)
              this.user = res.data
          })
      },
      route_change_next(){
          //routeは全部で3つあってどこか1つには絶対いるので常に2ルート表示できるようにする
          //初期状態をnotificationとして、最初はnotificationにいるようにする、そのときはuser_statusとactivityを表示

          if(this.current_route == "Notification"){
              this.current_route = "User_status";
               this.next_route = "Activity";
              this.prev_route = "Notification";
          }else if(this.current_route == "User_status"){
              this.current_route = "Activity";
              this.next_route = "Notification";
              this.prev_route = "User_status";
          }else if(this.current_route == "Activity"){
               this.current_route = "Notification";
              this.next_route = "User_status";
              this.prev_route = "Activity";
          }
      },
      route_change_prev(){
          //routeは全部で3つあってどこか1つには絶対いるので常に2ルート表示できるようにする
          //初期状態をnotificationとして、最初はnotificationにいるようにする、そのときはuser_statusとactivityを表示

          if(this.current_route == "Notification"){
               this.current_route = "Activity";
              this.next_route = "Notification";
              this.prev_route = "User_status";
          }else if(this.current_route == "User_status"){
               this.current_route = "Notification";
              this.next_route = "User_status";
              this.prev_route = "Activity";
          }else if(this.current_route == "Activity"){
                this.current_route = "User_status";
               this.next_route = "Activity";
              this.prev_route = "Notification";
          }
      },
      getRecent(){
          //questionと
          axios.post("/api/user")
          .then(res => {
              this.recent_posts = res.data.recent_posts
              this.recent_replies = res.data.recent_replies
          })
          .catch(error => error.response.data)

          //replyでそれぞれ最新5つまでとってくる、別にこっちで5つに絞ってもいいけど
      }
    },
    computed:{
       comp_list(){
           if(this.current_route == "Notification"){
              return "list_position notification";
           }else if(this.current_route =="Activity"){
               return "list_position activity";
           }else if(this.current_route == "User_status"){
               return "list_position user_status";
           }
       },
       comp_timenow(){
           return this.timenow;
       },
       comp_upper_area(){
           if(this.current_route == "Notification"){
              return "upper_area-profile notification";
           }else if(this.current_route =="Activity"){
               return "upper_area-profile activity";
           }else if(this.current_route == "User_status"){
               return "upper_area-profile user_status";
           }
       }
    },
    beforeRouteEnter(to,from,next){
        next(vm => {
            console.log("enter")
          var  self = vm;
        self.$eventBus.$emit("changeGridUser",true)
        self.$store.dispatch("changeToolRead",false)

        var tm_user_profile = new TimelineMax();
        tm_user_profile
        .add("scene1")
        })
   },
   beforeRouteLeave(to,from,next){
    this.$eventBus.$emit("changeGridUser",false);
     this.$store.dispatch("changeToolRead",true)
    clearInterval(this.intervalid)
    console.log("clearinterval")
    next();
   }
}
</script>

<style lang="scss" scoped>
*{
    font-family: 'Geostar', cursive;
}
$background-color: #EDE9E3;
$search-bg-color: #242628;

.entire_user_profile{
    height: 100%;
    width: 100%;
    background-color: $background-color;
    position: relative;
    display: flex;
    flex-direction: column;
}

.upper_area-profile{
  height: 10rem;
  width: 95vw;
  margin-left: 2.5vw;
  margin-right: 2.5vw;
  margin-top: 3rem;
  border:1px solid #090D03;
  padding: 2rem;

  display: flex;
  align-items: center;

  font-size: 2rem;


}

#upper_message-profile{
  margin-right: auto;
}

.main_menu{
    flex-grow: 1;
    width: 95vw;
    margin-left: 2.5vw;
    margin-right: 2.5vw;
    margin-top: 3rem;
    border:1px solid #090D03;

    position: relative;


}


// .user_image{
//     position: absolute;
//     bottom:0;
//     right: 0;
//     overflow: hidden;

//     img{
//         object-fit: cover;
//     }
// }

.list-move,
.items{
  transition:all .4s ease-in-out;
}

.list_position{
    position: absolute;
    width: 100%;
    height: 100%;

}

.main_menu_message{
    // height: 10rem;
    // width: 40rem;
     position: absolute;
      top: 0;
      left: 0;
      margin-top: 3rem;
      margin-left: 2rem;
      font-size: 5rem;

}

.notification_count{
     position: absolute;
      top: 5%;
      left:0;
      margin-top: 3rem;
       margin-left: 2rem;
      font-size: 2rem;
      background-color: #4E4B42;
      color:#EDE9E3;
      padding: 1rem;

    //   height: 6rem;
    //   width: 6rem;


      }

.link_to{
    text-decoration: none;
    color:#090D03;
    margin-right: 2rem;

    &:hover{
        border-bottom: 1px solid #090D03;
    }
}

.main_menu_body{

    position: absolute;
    top: 13%;
    left: 0;
    display: flex;

    .notification{
        list-style: none;




        .read_item{
            display: flex;
            margin-bottom: 1rem;
            font-size: 1rem;

           .read_color{
             height: 1rem;
             width: 1rem;
             margin-right: .8rem;
             background-color: #57544A;
             margin-top: .3rem;
           }

        }

        .unread_item{
            display: flex;
            margin-bottom: 1rem;
            font-size: 1rem;

            .unread_link{
                text-decoration: none;
                color:#090D03;
            }
           .unread_color{
               height: 1rem;
             width: 1rem;
              margin-right: .8rem;
              background-color:#CD664D;
              margin-top: .3rem;
           }
        }
    }
}

.user_items{
    height: 5rem;
    widows: 10rem;
    margin-bottom: 4rem;

    .user_pre{
        font-size:2.2rem;
        margin-bottom: 2rem;
        padding: 0.8rem;
        display: inline-block;
          background-color: #4E4B42;
      color:#EDE9E3;
    }

    .user_post{
        font-size: 3rem;
    }
}

.route_icon{
    height: 3rem;
    width: 3rem;
    color:currentColor;
}
.route_next{
    position: absolute;
    top:8%;
    right: 6%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;

    .route_text{
      font-size: 2rem;
    }

}

.route_current{
   position: absolute;
    top:2%;
    right: 6%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #B4AF9A;
    color: #4E4B42;


    .route_text{
      font-size: 2rem;
    }
}

.route_from{
    position: absolute;
     top:14%;
    right: 6%;
     display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;

    .route_text{
      font-size: 2rem;
    }
}



.user_status{
    .main_menu_message{
        transform: translate3d(0,40rem,0);
    }
    #upper_message-profile{
        transform: translate3d(75rem,16rem,0) scale(2);
    }
    .main_menu_body{
        transform: translate3d(65rem,10rem,0);
    }
}

.activity{
     .main_menu_message{
        transform: translate3d(0,40rem,0);
    }
    #upper_message-profile{
        transform: translate3d(75rem,16rem,0) scale(2);
    }
    .main_menu_body{
        transform: translate3d(65rem,10rem,0);
    }
}

.recent_activity{

     display: flex;
     flex-direction: row;
    .recent_posts{
     list-style: none;
    }
    .recent_replies{
    list-style: none;
    }
}

// .lower_area{
//     background-color: #BF0000;
//      height: 100%;
//     width: 100%;
//     bottom: 0;
//     right: 0;
//      z-index: 1;
//     //  clip-path: polygon(100% 0, 100% 100%, 0 100%);
//      position: absolute;
// }


</style>
