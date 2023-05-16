import {defineStore} from "pinia";
import AuthService from "../services/Api/AuthService.js";

export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            user: null,
            isAuth: false,
            isVerified: false
        }
    },
    actions: {
        login({email, password}) {
            return AuthService.login(email, password)
                .then(({data}) => {
                    this.auth(data.data.access_token, data.data.user);
                })
        },
        registration(data) {
            return AuthService.registration(data)
                .then(({data}) => {
                    this.auth(data.data.access_token, data.data.user);
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
                    this.verified(data.data)
                    this.user = data.data
                })
        },
        auth(token, user) {
            this.isAuth = true;
            this.verified(user)
            this.user = user;
            localStorage.setItem('token', token)
        },
        verified(user) {
            if (user.is_verified) {
                this.isVerified = true;
            }
        }
    }
})