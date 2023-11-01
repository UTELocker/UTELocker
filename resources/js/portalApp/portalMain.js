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
  apiKey:  process.env.MIX_FIREBASE_API_KEY,
  authDomain: process.env.MIX_FIREBASE_AUTH_DOMAIN,
  projectId: process.env.MIX_FIREBASE_PROJECT_ID,
  storageBucket: process.env.MIX_FIREBASE_STORAGE_BUCKET,
  messagingSenderId: process.env.MIX_FIREBASE_MESSAGING_SENDER_ID,
  appId: process.env.MIX_FIREBASE_APP_ID,
  measurementId: process.env.MIX_FIREBASE_MEASUREMENT_ID,
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
