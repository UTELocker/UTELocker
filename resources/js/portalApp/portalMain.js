import { createApp } from "vue";
import MainApp from "./MainApp.vue";
import Antd from "ant-design-vue";
import router from "./portalRoute";
import './portalMain.scss';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import store from "./stores/store";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const app = createApp(MainApp);

router.beforeEach((to, from, next) => {
    if (to.path !== from.path) {
        NProgress.start();
    }
    next();
});

router.afterEach((to, from) => {
    if (to.path !== from.path) {
        NProgress.done();
        document.documentElement.scrollTop = 0;
    }
});

app.use(Antd);
app.use(store);
app.use(router);
app.use(VueSweetalert2);
app.mount("#portalApp");
