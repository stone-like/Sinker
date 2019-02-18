<template>
  <div class="entire_user_profile">
        <div :class="comp_upper_area" ref="upper_area-profile"  v-if="this.current_route !='Notification'">
            <span class="items upper_message-profile">
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
                               <div @click="readIt(item,index)" key="unread" class="item_wrapper">
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
                               <div key="read" class="item_wrapper">
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
                       <div class="posts_wrapper">
                       <span class="recents">Recent_Posts</span>
                       <ul class="recent_posts">
                          <li v-for="post in recent_posts" :key="post.id" class="post_items">
                                <div class="title_wrapper">
                                   <span class="recent_title">title:</span>
                                   <div class="post_title">
                                     {{post.title}}
                                  </div>
                                </div>
                              <div class="post_body">
                               {{post.body}}
                              </div>
                          </li>
                       </ul>
                       </div>
                       <div class="replies_wrapper">
                        <span class="recents">Recent_Reolies</span>
                       <ul class="recent_replies">
                           <li v-for="reply in recent_replies" :key="reply.id" class="reply_items">
                               <div class="title_wrapper">
                                   <span class="recent_title">title:</span>
                                   <div class="reply_title">
                                     {{reply.created_at}}
                                   </div>
                                </div>
                               <div class="reply_body">
                                {{reply.body}}
                               </div>
                           </li>
                       </ul>
                       </div>
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
              if(res.data.read.length > 11){
                  this.read = res.data.read.slice(0,11);
              }else{
                  this.read = res.data.read
              }
              if(res.data.unread.length > 11){
                  this.unread = res.data.unread.slice(0,11);
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
//   height: 10rem;
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

.upper_message-profile{
  margin-right: auto;
}

.main_menu{
    flex:1;
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

        .item_class{
           margin-bottom: 1.2rem;
        }

        .item_title{
          margin-bottom: 1.2rem;
        }

        .item_replyBy{
          align-self: flex-end;
        }

        .item_wrapper{
            display: flex;
            flex-direction: column;
        }


        .read_item{
            display: flex;
            margin-bottom: 2rem;
            font-size: 1.4rem;

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
            margin-bottom: 2rem;
            font-size: 1.4rem;

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

.notification{

    // .upper_area-profile{
    //    height: 0;

    // }


    .notification_count{
         transform: translate3d(110rem,40rem,0);
    }
     .main_menu_message{
        transform: translate3d(110rem,40rem,0);
    }
    .main_menu_body{
        transform: translate3d(20rem,-10rem,0);
    }
}

.user_status{
    // .upper_area-profile{
    //     height: 10rem;
    // }
    .main_menu_message{
        transform: translate3d(0,40rem,0);
    }
    .upper_message-profile{
        transform: translate3d(75rem,16rem,0) scale(2);
    }
    .main_menu_body{
        transform: translate3d(65rem,10rem,0);
    }

    .route_current{
       transform: translate3d(-120rem,-11.5rem,0);
    }

    .route_next{
       transform: translate3d(-104rem,-16rem,0);
    }

    .route_from{
        transform: translate3d(-84rem,-21.5rem,0);
    }
}

.activity{
    // .upper_area-profile{
    //     height: 10rem;
    // }
     .main_menu_message{
        transform: translate3d(60rem,-12rem,0);
    }
    .upper_message-profile{
        transform: translate3d(0,0,0) scale(1);
    }
    .main_menu_body{
        transform: translate3d(70rem,-7rem,0);
        width: 80rem;

        .recent_activity{
            width: 100%;
            justify-content: space-between;
        }

    }

    .route_current{
       transform: translate3d(-120rem,7rem,0);
    }

    .route_next{
       transform: translate3d(-120rem,7rem,0);
    }

    .route_from{
        transform: translate3d(-120rem,7rem,0);
    }
}

.recent_activity{

     display: flex;
     flex-direction: row;
    .recents{
        font-size: 3rem;
        background-color: #4E4B42;
        color:#EDE9E3;
        padding: 1rem;
     }
     .title_wrapper{
        background-color: #B4AF9A;
        color: #4E4B42;
        padding: .5rem 0 .5rem 0;
        margin-bottom: 2rem;
     }
     .recent_title{
       margin-right: 1rem;

     }
    .recent_posts{
     list-style: none;
     margin-top: 4rem;
     padding: 0;

      .post_items{
          font-size: 2rem;
          height: 10rem;


          .post_title{
            display: inline-block;
          }
      }
    }
    .recent_replies{
    list-style: none;
    margin-top: 4rem;
    padding: 0;

    .reply_items{
         font-size: 2rem;
           height: 10rem;

           .reply_title{
                display: inline-block;
          }
      }
    }
}

// .height_effect-enter,
// .height_effect-leave-to{
//    height: 0 !important;
// }

// .height_effect-enter-active,
// .height_effect-leave-active{
//    height: 6rem !important;

//    transition: all .4s ease-in-out;
// }

// .height_effect-enter-active,
// .height_effect-leave-active{
//    height: 10rem !important;
// }




</style>
