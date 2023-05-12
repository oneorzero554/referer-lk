import {defineStore} from "pinia";
import AuthService from "../services/Api/AuthService.js";

export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            user: null,
            isAuth: false
        }
    },
    actions: {
        login({email, password}) {
            return AuthService.login(email, password)
                .then(({data}) => {
                    this.isAuth = true;
                    this.user = data.data.user;
                    localStorage.setItem('token', data.data.access_token);
                })
        },
        logout() {
            AuthService.logout()
                .then(({data}) => {
                    localStorage.removeItem('token');
                    location.reload()
                })
        },
        me() {
            return AuthService.me()
                .then(({data}) => {
                    this.isAuth = true;
                    this.user = data.data
                })
        }
    }
})