import {createApp} from 'vue';
import router from "./router";
import {createPinia} from "pinia";
import App from './App.vue';
import './reset.css'
import './index.css'
import ErrorService from "./services/ErrorService.js";

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

// app.config.errorHandler = (error) => ErrorService.onError(error);

app.mount('#app');

