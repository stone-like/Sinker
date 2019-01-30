import Vue from 'vue'
import AppStorage from "../../Helpers/AppStorage"
import Token from "../../Helpers/Token"
import User from "../../Helpers/User"
//tokenとかusernameの破棄と保存はここを呼び出して行う、呼び出し側はHelper/Userとかでやって実際の処理はここ
const state={
    token:null,
    username:null,
    id:null,
    modalflag:false
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
     userLoggedIn:function(state){
         if(state.token){
             return Token.isValid(state.token) ? true :false;
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
     }
 };

export default {
    state,
    mutations,
    actions,
    getters,
}
