<template>
 <div class="entiregrid">
  <modal v-if="modalflag" @close="changeModalFlag"></modal>
  <toolbar class="toolbar"></toolbar>
  <router-view class="router-view"></router-view>
  <app-footer class="footer"></app-footer>
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
    setHomeflag(){
        return {'homeflag': this.$store.getters.getHomeFlag}
    },
    setReadflag(){
        return {'readflag': this.$store.getters.getReadFlag}
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


    @if(var(readflag)){
       grid-column:1/1;
       grid-row:1/-1;
    }@else if(var(homeflag)){
       grid-column: 1/3;
       grid-row:1/2;
    }

}
.router-view{
    @if(var(readflag)){
      grid-column:2/-1;
      grid-row:1/3;
    }@else if(var(homeflag)){
        grid-column:1/-1;
        grid-row:2/3;
    }
}
.footer{
     @if(var(readflag)){
      grid-column:2/-1;
      grid-row:3/4;
    }@else if(var(homeflag)){
        grid-column:1/-1;
        grid-row:3/4;
    }
}
</style>
