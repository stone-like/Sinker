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
    readflag:false,
    descriptionflag:false,
    signupflag:false,
    transition_name_router:"ReadToHome",
    transition_name_tool:"fade-up",
    tool_read_mode:false
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
    'offDescription'(state){
        state.descriptionflag = false;
    },
    'onDescription'(state){
        state.descriptionflag = true;
    },
    'HomeMode'(state){
        state.homeflag = true;
        state.readflag = false;
    },
    'ReadMode'(state){
        state.homeflag = false;
        state.readflag = true;
    },
    'Signup_Mutate'(state){
       state.signupflag = !state.signupflag;
    },
    'Set_Router'(state,payload=null){
        state.transition_name_router = payload;
    },
    'Set_Tool'(state,payload=null){
        state.transition_name_tool = payload;
        //こっちはtoolbarの変化。3mode
    },
    'TooltoRead'(state){
        //こっちはtoolbarの位置、2modeあってコンポーネントに入るときに切り替える
        state.tool_read_mode != state.tool_read_mode
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
    onDescriptionFlag:({commit}) => {
        commit('onDescription');
    },
    offDescriptionFlag:({commit}) => {
        commit('offDescription');
    },
    logoutAction:({commit}) => {
        commit('logout');
    },
    changeSignupFlag:({commit}) => {
        commit('Signup_Mutate');
    },
    changeTransition_Router:({commit},payload) => {
        commit('Set_Router',payload);
    },
    changeTransition_Tool:({commit},payload) => {
        commit('Set_Tool',payload);
    },
    changeToolRead:({commit}) => {
        commit('TooltoRead');
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
     getReadFlag:state => {
         return state.readflag;
     },
     getHomeFlag:state => {
        return state.homeflag;
    },
    getDescriptionFlag:state => {
        return state.descriptionflag;
    },
    getSignupFlag:state => {
       return state.signupflag;
   },
   getTransitionRouter:state => {
       return state.transition_name_router;
   },
   getTransitionTool:state => {
       return state.transition_name_tool;
   },
   getTool_Read_Mode:state => {
       return state.tool_read_mode;
   }
 };

export default {
    state,
    mutations,
    actions,
    getters,
}
