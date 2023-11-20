<template>
    <Navigation  :isMobile="isMobile" />
    <template v-if="!isMobile">
        <help-call />
        <notification :isMobile="isMobile" />
        <User />
    </template>
</template>
<script>
import {defineComponent} from "vue";
import Navigation from "./Navigation.vue";
import User from "./User.vue";
import Notification from "./Notification.vue";
import HelpCall from "./HelpCall.vue";

export default defineComponent({
    name: "Menu",
    components: {
        Notification,
        User,
        Navigation,
        HelpCall,
    },
    props: ['isMobile'],
});
</script>
