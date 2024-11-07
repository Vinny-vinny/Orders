import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import router from './routes/index'
import VueSweetalert2 from "vue-sweetalert2";
import vSelect from "vue-select";
import useAuth from './composables/auth';

import 'sweetalert2/dist/sweetalert2.min.css';
import 'vue-select/dist/vue-select.css';

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

const app = createApp({
    created() {
        useAuth().getUser()
    }
});

app.use(pinia)
app.use(router)
app.use(VueSweetalert2)
app.component('Pagination', Bootstrap5Pagination)
app.component("v-select", vSelect);
app.mount('#app')
