import Token from "./Token"
import AppStorage from "./AppStorage"
import Axios from "axios";
import store from '../Store/store.js'

class User{
  login(data){
      Axios.post("api/auth/login",data)
      .then(res => this.responseAfterLogin(res))
      .catch(error => console.log(error.response.data))
  }
  hasToken(){
      const storeToken = AppStorage.getToken();
      if(storeToken){
          return Token.isValid(storeToken) ? true : false;
      }

          return false;

  }
  isLogeedIn(){
      return this.hasToken();
  }

  responseAfterLogin(res){
      const access_token = res.data.access_token
      const username = res.data.user
      const id = Token.payload(res.data.access_token).sub
      if(Token.isValid(access_token)){
          store.dispatch('storeToken',access_token)
          store.dispatch('storeUser',username)
          store.dispatch('storeId',id)

      }
  }
}

export default User = new User();//こうやってexportしないとfunctionが呼び出せない
