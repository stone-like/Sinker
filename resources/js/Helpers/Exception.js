import store from '../Store/store.js'


class Exception{
    handle(error){
        this.isExpired(error.response.data.error)
    }

    isExpired(error){
      if(error == "Token is invalid"){
          store.dispatch('deleteToken')
          store.dispatch('deleteId')
          store.dispatch('deleteUser')
          localStorage.clear()
          window.location="/forum"

      }
    }
}

export default Exception=new Exception()
