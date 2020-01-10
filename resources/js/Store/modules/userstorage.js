import Vue from 'vue'
import AppStorage from "../../Helpers/AppStorage"
import Token from "../../Helpers/Token"
import User from "../../Helpers/User"
import Axios from 'axios';
//tokenとかusernameの破棄と保存はここを呼び出して行う、呼び出し側はHelper/Userとかでやって実際の処理はここ
const initial_state = {
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
    tool_read_mode:false,
    tool_home_mode:true,
    search_list:{},
    apply_list:{},
    questions:{},
    categories:{},
    queryParam:{
        keywords:"",
        mode_main:"",
        mode_sub:""
    },
    isLoading:false,
    search_box_on:true,
    modalHeader:"",
    modalBody:"",
    modalFooter:""
};

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
    tool_read_mode:false,
    tool_home_mode:true,
    search_list:{},
    apply_list:{},
    questions:{},
    categories:{},
    queryParam:{
        keywords:"",
        mode_main:"",
        mode_sub:""
    },
    isLoading:false,
    search_box_on:true,
    modalHeader:"",
    modalBody:"",
    modalFooter:""
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
     'Set_Modal_Header'(state,payload=null){
        state.modalHeader = payload;
     },
     'Set_Modal_Body'(state,payload=null){
        state.modalBody = payload;
    },
    'Set_Modal_Footer'(state,payload=null){
        state.modalFooter = payload;
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
    'TooltoRead'(state,payload){
        //こっちはtoolbarの位置、2modeあってコンポーネントに入るときに切り替える
        state.tool_read_mode = payload;
    },
    'TooltoHome'(state,payload){
        //こっちはtoolbarの位置、2modeあってコンポーネントに入るときに切り替える
        state.tool_home_mode = payload;
    },
    'List_Mutate'(state,payload=null){
        state.search_list = payload;
    },
    'Apply_List'(state,payload=null){
        state.apply_list = payload;
    },
    'Query_Param_Mutate'(state,payload=null){
        state.queryParam = payload;
    },
    'Active_Loading'(state){
        state.isLoading = true;
    },
    'Set_Search_List'(state,payload){
        state.isLoading = false;
        //ここでfilterをかけたい
        state.search_list = payload;
    },
    'setModeSub'(state,payload){
        state.queryParam.mode_sub = payload;
    },
    'setModeMain'(state,payload){
        state.queryParam.mode_main = payload;
    },
    'setKeywords'(state,payload){
        state.queryParam.keywords = payload;
    },
    'Question_Array_Mutate'(state,payload){
        state.questions = payload;
    },
    'Category_Array_Mutate'(state,payload){
        state.categories = payload;
    },
    'SearchBox_Mutate'(state,payload){
        state.search_box_on = payload;
    },
    'Initial_return'(state){
        Object.assign(state,initial_state);
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
    changeModalFlag:({commit},payload) => {
        commit('Modal_Mutate');
        commit('Set_Modal_Header',payload[0])
        commit('Set_Modal_Body',payload[1])
        commit('Set_Modal_Footer',payload[2])
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
    changeToolRead:({commit},payload) => {
        commit('TooltoRead',payload);
    },
    changeToolHome:({commit},payload) => {
        commit('TooltoHome',payload);
    },
    changeSearchList:({commit},payload) => {
        commit('List_Mutate',payload);
    },
    changeApplyList:({commit},payload) => {
        commit('Apply_List',payload);
    },
    changeQueryParam:({commit},payload) => {
        commit('Query_Param_Mutate',payload);
    },
    setQuestionArray:({commit},payload) => {
        commit('Question_Array_Mutate',payload);
    },
    setCategoryArray:({commit},payload) => {
        commit('Category_Array_Mutate',payload);
    },
    setSearchBoxFlag:({commit},payload) => {
        commit('SearchBox_Mutate',payload);
    },
    setInitialstate:({commit}) => {
        commit('Initial_return');
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
         return state.username === "admin" ? true : false;
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
   },
   getTool_Home_Mode:state => {
    return state.tool_home_mode;
   },
   getSearch_List:state => {
       return state.search_list;
   },
   getApply_List:state => {
       return state.apply_list;
   },
   getQueryParams:state => {
       return state.queryParam;
   },
   getIsLoading:state => {
       return state.isLoading;
   },
   getQuestion_Array:state => {
       return state.questions;
   },
   getCategory_Array:state => {
    return state.categories;
},
  getSearch_Flag:state => {
      return state.search_box_on;
  },
  getModalHeader:state => {
      return state.modalHeader;
  },
  getModalBody:state => {
      return state.modalBody;
  },
  getModalFooter:state => {
      return state.modalFooter;
  }
 };

export default {
    state,
    mutations,
    actions,
    getters,
}
