<template>
    <Navigation />
    <template v-if="!isMobile">
        <notification :isMobile="isMobile" />
        <Github />
    </template>
</template>
<script>
import {defineComponent} from "vue";
import Navigation from "./Navigation.vue";
import Github from "./Github.vue";
import Notification from "./Notification.vue";

export default defineComponent({
    name: "Menu",
    components: {
        Notification,
        Github,
        Navigation
    },
    props: ['isMobile'],
});
</script>
