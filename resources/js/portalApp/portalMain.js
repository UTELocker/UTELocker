import { createApp } from "vue";
import MainApp from "./MainApp.vue";
import Antd from "ant-design-vue";
import router from "./portalRoute";
import 'ant-design-vue/dist/reset.css';

const app = createApp(MainApp);
app.use(Antd);
app.use(router);
app.mount("#portalApp");
