import "./assets/main.css";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import Antd from "ant-design-vue";
import store from "./store/store";
import "./style.css";
import { useBreadcrumb } from "./store/breadcrumb";
import VueKonva from "vue-konva";

const app = createApp(App);
app.provide("breadcrumb", useBreadcrumb());
app.use(VueKonva);
app.use(router);
app.use(Antd);
app.use(store);
app.mount("#app");
