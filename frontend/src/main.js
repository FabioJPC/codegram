import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/reset.css'
import './assets/global.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import pinia from './pinia.js'

const app = createApp(App);

app.use(router);

app.use(pinia);

app.mount('#app');
