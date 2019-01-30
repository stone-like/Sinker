import store from '../Store/store.js'
class AppStorage{

    storeToken(token){
        localStorage.setItem('token',token)
    }

    storeUser(user){
        localStorage.setItem('user',user)
    }

    storeId(id){
        localStorage.setItem('id',id)
    }

    store(token,user,id){
        this.storeToken(token)
        this.storeUser(user)
        this.storeId(id)
    }

    clear(){
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        localStorage.removeItem('id')
    }

    getToken(){
        return localStorage.getItem('token');
    }

    getId(){
        return localStorage.getItem('id');
    }

    getUser(){
        return localStorage.getItem('user');
    }
}

export default AppStorage = new AppStorage();
