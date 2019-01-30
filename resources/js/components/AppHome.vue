<template>
<div>
  <modal v-if="modalflag" @close="changeModalFlag"></modal>
  <toolbar></toolbar>
  <router-view></router-view>
  <app-footer></app-footer>
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
    }
},
methods:{
    changeModalFlag(){
        this.$store.dispatch('changeModalFlag');
    }
}

}
</script>

<style>

</style>
