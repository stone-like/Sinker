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
                   {{main_menu_massage}}
              </div>
              <div class="notification_count" key="notification_count" v-if="!active">
                  {{unreadCount + (unreadCount > 0 ? "unreads" : "unread")}}
              </div>
              <div class="items main_menu_body" key="body">
                  <transition-group tag="ul" name="notification_list" class="notification" v-if="!active">
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
                  <transition-group tag="ul" name="notification_list" class="notification" v-if="!active">
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

                  <div v-else>
                      <div class="user_created_at">
                          {{user.created_at}}
                      </div>
                      <div class="user_email">
                          {{user.email}}
                      </div>
                      <div class="user_total_likes">
                          {{user.total_likes}}
                      </div>
                      <div class="user_total_post">
                          {{user.total_post}}
                      </div>
                      <div class="user_total_reply">
                          {{user_total_reply}}
                      </div>
                  </div>
              </div>

        </transition-group>

        <v-btn class="dummy" @click="change_shapes">pushToActive</v-btn>
        </div>







  </div>
</template>

<script>
import {TweenMax,bezier,DirectionalRotationPlugin,CSSPlugin} from "gsap"
export default {
    data(){
        return{
            active:false,
            timenow:"",
            main_menu_massage:"Notification",
            unread:"",
            read:"",
            unreadCount:0,
            user:{}
        }
    },
    mounted(){
       this.getNotifications();
       this.starttime_val();
       this.getUser();

        Echo.private('App.User.' + this.$store.getters.getId)
        .notification((notification) => {
           this.unread.unshift(notification)
           this.unreadCount++
    });
    },
    methods:{
        change_shapes(){
             this.active=!this.active
             this.main_menu_massage = "User status"
        },
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
          axios.get()
      }
    },
    computed:{
       comp_list(){
           return this.active ? "list_position active" : "list_position"
       },
       comp_timenow(){
           return this.timenow;
       },
       comp_upper_area(){
           return this.active ? "upper_area-profile active" : "upper_area-profile";
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


        // clip-path: polygon(100% 0, 0 0, 0 100%)　upperstartpoints、これをarrayに落とし込むと[100,0,0,0,0,100]

        //clip-path: polygon(100% 0, 0 0, 0 40%); upperendpoints

        // var arr1 = [100,0,0,0,0,100];
        // var arr2 = [100,0,0,0,0,40];

        // tm_user_profile.to(arr1, 3, { endArray: arr2, onUpdate: clipPath});

        // function clipPath() {

        // TweenMax.set("#upper_triagle", { webkitClipPath: 'polygon('+
        // arr1[0]+'%'+arr1[1]+'%,'+
        // arr1[2]+'%'+arr1[3]+'%,'+
        // arr1[4]+'%'+arr1[5]+'%)'
//   });
//     console.log(arr1);
// }



        // tm_user_profile
        // .add("scene1")
        // .to(self.$refs.upper_area,.8,{y:-200,},"scene1")
        // .to(self.$refs.lower_area,.8,{y:200},"scene1")
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
    //   background-color: #4E4B42;
    //   color:#EDE9E3;
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
    top: 12%;
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



.active{
    .main_menu_message{
        transform: translate3d(0,40rem,0);
    }
    #upper_message-profile{
        transform: translate3d(75rem,12rem,0) scale(2);
    }
    .main_menu_body{
        transform: translate3d(70rem,0,0);
    }
}

.dummy{
    position: absolute;
    top: 80%;
    left: 50%;
    transform: translate(-50%,-50%);
    z-index:2;
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
