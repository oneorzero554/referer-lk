import $api from "../../http/index.js";

export default class AuthService {
    static async registration(data) {
        return $api.post('/auth/registration', data)
    }

    static async login(email, password) {
        return $api.post('/auth/login', {email, password})
    }

    static async me() {
        return $api.get('/auth/me')
    }

    static async logout() {
        return $api.post('/auth/logout')
    }

    static async refresh() {
        return $api.post('/auth/refresh')
    }
}