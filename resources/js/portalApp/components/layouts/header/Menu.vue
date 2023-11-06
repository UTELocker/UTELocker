<template>
    <Navigation />
    <template v-if="!isMobile">
        <help-call />
        <notification :isMobile="isMobile" />
        <Github />
    </template>
</template>
<script>
import {defineComponent} from "vue";
import Navigation from "./Navigation.vue";
import Github from "./Github.vue";
import Notification from "./Notification.vue";
import HelpCall from "./HelpCall.vue";

export default defineComponent({
    name: "Menu",
    components: {
        Notification,
        Github,
        Navigation,
        HelpCall,
    },
    props: ['isMobile'],
});
</script>
