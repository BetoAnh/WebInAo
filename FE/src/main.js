import "./assets/main.css";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import Antd from "ant-design-vue";
import "./style.css";
import { useBreadcrumb } from "./store/breadcrumb";
import { useCountCart } from "./store/countCart";
import VueKonva from "vue-konva";

const app = createApp(App);
app.provide("breadcrumb", useBreadcrumb());
app.provide("countCart", useCountCart());
app.use(VueKonva);
app.use(router);
app.use(Antd);
app.mount("#app");
