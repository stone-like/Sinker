<template>
 <div class="entiregrid">
  <modal v-if="modalflag" @close="changeModalFlag"></modal>
  <toolbar :class="comp_tool"></toolbar>
  <router-view :class="comp_view"></router-view>
  <app-footer :class="comp_foot"></app-footer>
 </div>
</template>

<script>
import toolbar from "./Toolbar";
import AppFooter from "./AppFooter";
import Login from "./login/Login";
import Modal from "../components/expantion/Modal";

//既にrouter-viewの中にLogincomponentが入っていてその中でroutertoをしてもその行き先がrouter-viewに入るみたい
export default {

 components:{toolbar,AppFooter,Login,Modal},//toolbarはtoolbar:toolbarの略


computed:{
    modalflag(){
        return this.$store.getters.getModalFlag;
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
    }
}

}
</script>

<style lang="scss" scoped>

.entiregrid{
    display:grid;
    grid-template-columns: 13rem 1fr;
    grid-template-rows: 10rem 1fr 10rem;
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
</style>
