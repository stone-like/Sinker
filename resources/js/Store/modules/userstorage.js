import Vue from 'vue'
import AppStorage from "../../Helpers/AppStorage"
import Token from "../../Helpers/Token"
import User from "../../Helpers/User"
//tokenとかusernameの破棄と保存はここを呼び出して行う、呼び出し側はHelper/Userとかでやって実際の処理はここ
const state={
    token:null,
    username:null,
    id:null,
    modalflag:false,
    homeflag:true,
    readflag:false
 };

 const mutations ={
     'Token_Mutate'(state,payload=null){
         state.token = payload;
     },
     'User_Mutate'(state,payload=null){
         state.username = payload;
     },
     'Id_Mutate'(state,payload=null){
         state.id = payload;
     },
     'Modal_Mutate'(state){
         state.modalflag = !state.modalflag;
     },
     'Grid_Mutate'(state){
        state.gridflag = !state.gridflag;
    },
    'HomeMode'(state){
        state.homeflag = true;
        state.readflag =  false;
    },
    'ReadMode'(state){
        state.homeflag = false;
        state.readflag = true;
    }
 };

 const actions ={
    deleteToken:({commit}) => {
        commit('Token_Mutate');
    },
    storeToken:({commit},payload) => {
        commit('Token_Mutate',payload);
    },
    deleteUser:({commit}) => {
     commit('User_Mutate');
 },
    storeUser:({commit},payload) => {
     commit('User_Mutate',payload);
 },
    deleteId:({commit}) => {
     commit('Id_Mutate');
 },
    storeId:({commit},payload) => {
     commit('Id_Mutate',payload);
 },
    changeModalFlag:({commit}) => {
        commit('Modal_Mutate');
    },
    changeHomeMode:({commit}) => {
        commit('HomeMode');
    },
    changeReadMode:({commit}) => {
        commit('ReadMode');
    },
    logoutAction:({commit}) => {
        commit('logout');
    }
 };

 const getters = {
     getToken:state => {
         return state.token;
     },
     getUser:state =>{
         return state.username;
     },
     getId:state =>{
         return state.id;
     },
     userLoggedIn:function(state,getters){
         if(state.token){
             return Token.isValid(state.token) ? true :User.logout();
         }
     },
     checkown:(state) => (id) => {
         return state.id == id ? true : false;
     },
     checkadmin:state =>{
         return state.id === 21 ? true : false;
     },
     getModalFlag:state => {
         return state.modalflag;
     },
     getHomeFlag:state => {
        return state.homeflag;
    },
     getReadFlag:state => {
         return state.readflag;
     }
 };

export default {
    state,
    mutations,
    actions,
    getters,
}
