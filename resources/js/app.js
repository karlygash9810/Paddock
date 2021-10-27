require("./bootstrap");

import { createApp, h } from "vue";
import router from "./router";
import App from "./components/App";
import BootstrapVue3 from "bootstrap-vue-3";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-3/dist/bootstrap-vue-3.css";

const app = createApp({
    render: () => h(App)
});

app.use(BootstrapVue3).use(router).mount('#app')
