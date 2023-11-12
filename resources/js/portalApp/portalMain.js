import { createApp } from "vue";
import MainApp from "./MainApp.vue";
import Antd from "ant-design-vue";
import router from "./portalRoute";
import './portalMain.scss';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import store from "./stores/store";
import './setUpPusher.js'

import firebase from 'firebase/compat/app';

const firebaseConfig = {
  apiKey:  window.configFireBase.apiKey,
  authDomain: window.configFireBase.authDomain,
  projectId: window.configFireBase.projectId,
  storageBucket: window.configFireBase.storageBucket,
  messagingSenderId: window.configFireBase.messagingSenderId,
  appId: window.configFireBase.appId,
  measurementId: window.configFireBase.measurementId
};

firebase.initializeApp(firebaseConfig);

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
app.mount("#portalApp");
