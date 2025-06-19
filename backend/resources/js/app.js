import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import axios from 'axios';
import GlobalError from './components/GlobalError.vue';

import '../css/app.css';

const app = createApp(App);

app.component('GlobalError', GlobalError);
app.use(router);
app.use(store);
app.mount('#app');
