<template>
    <Navigation />
    <notification :isMobile="isMobile" />
    <template v-if="!isMobile">
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
