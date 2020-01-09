import store from "../Store/store.js";

class Exception {
    handle(error) {
        this.isExpired(error.response.data.error);
    }

    isExpired(error) {
        if (error == "Token is expired") {
            store.dispatch("deleteToken");
            store.dispatch("deleteId");
            store.dispatch("deleteUser");
            localStorage.clear();
            window.location = "/welcome";

            //notificationを出してあげる
        }
    }
}

export default (Exception = new Exception());
