<template>

 <div class="entiregrid">
  <modal v-if="modalflag" @close="changeModalFlag"></modal>
   <toolbar :class="comp_tool"></toolbar>
   <router-view :class="comp_view"></router-view>
   <home-description v-if="homeflag && descriptionflag && number" :number="number" class="homedescription"></home-description>
   <app-footer :class="comp_foot"></app-footer>
 </div>
</template>

<script>
import toolbar from "./Toolbar";
import AppFooter from "./AppFooter";
import Login from "./login/Login";
import Modal from "../components/expantion/Modal";
import HomeDescription from "../components/expantion/HomeDescription"

//既にrouter-viewの中にLogincomponentが入っていてその中でroutertoをしてもその行き先がrouter-viewに入るみたい
export default {
 data(){
        return{
            number:""
        }
    },

 components:{toolbar,AppFooter,Login,Modal,HomeDescription},//toolbarはtoolbar:toolbarの略

created(){
    this.listen()
},
computed:{
    modalflag(){
        return this.$store.getters.getModalFlag;
    },
    homeflag(){
        return this.$store.getters.getHomeFlag;
    },
    descriptionflag(){
       return this.$store.getters.getDescriptionFlag;
    },
    comp_tool(){
        return this.$store.getters.getHomeFlag ? "toolbar" : "toolbar-read"
    },
    comp_view(){
        return this.$store.getters.getHomeFlag ? "router-view" : "router-view-read"
    },
    comp_foot(){
        return this.$store.getters.getHomeFlag ? "footer" : "footer-read"
    }
},
methods:{
    changeModalFlag(){
        this.$store.dispatch('changeModalFlag');
    },
    listen(){
        this.$eventBus.$on("openDescription",(number) =>{
             this.number=number;
             this.$store.dispatch("onDescriptionFlag");
             console.log(this.$store.getters.getDescriptionFlag);

        })

        this.$eventBus.$on("closeDescription",(number) =>{
             this.$store.dispatch("offDescriptionFlag");
             console.log(this.$store.getters.getDescriptionFlag);
    })
  }

 }
}
</script>

<style lang="scss" scoped>

.entiregrid{
    display:grid;
    grid-template-columns: 13rem 1fr;
    grid-template-rows: 10rem 1fr 10rem;
    position:relative;
}
.toolbar{

       grid-column: 1/3;
       grid-row:1/2;

}
.toolbar-read{
       grid-column:1/1;
       grid-row:1/3;

}
.router-view{
        grid-column:1/-1;
        grid-row:2/3;
}

.router-view-read{
      grid-column:2/-1;
      grid-row:1/3;
}
.footer{
        grid-column:1/-1;
        grid-row:3/4;
}
.footer-read{
      grid-column:1/-1;
      grid-row:3/4;
}
.homedescription{
    position:absolute;
    top:0;
    right:0;
    z-index:99;
    width:34vw;
    grid-row: 1/3;
}
</style>
