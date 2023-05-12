import axios from "axios";
import ErrorService from "../services/ErrorService.js";

export const API_URL = 'http://localhost:8000/api';

const headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
}

const $api = axios.create({
    withCredentials: true,
    baseURL: API_URL,
    headers: headers
});

$api.interceptors.request.use(config => {
    const token = localStorage.getItem('token')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }

    return config;
});

$api.interceptors.response.use(res => {
    return res
}, async (err) => {

    const originalRequest = err.config;

    if (err.response.status === 401 && err.config && !err.config._isRetry) {

        originalRequest._isRetry = true;

        try {
            const res = await axios.get(`${API_URL}/auth/refresh`, {
                withCredentials: true,
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            })
            localStorage.setItem('token', res.data.data.access_token);
            return $api.request(err.config);
        } catch (e) {
            localStorage.removeItem('token')
            location.reload()
        }
    }

    ErrorService.onApiError(err)

    throw err;
})

export default $api;