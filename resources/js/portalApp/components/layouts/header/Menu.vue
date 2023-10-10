<template>
    <Navigation />
    <template v-if="!isMobile">
        <Github />
    </template>
</template>
<script>
import {defineComponent} from "vue";
import Navigation from "./Navigation.vue";
import Github from "./Github.vue";

export default defineComponent({
    name: "Menu",
    components: {
        Github,
        Navigation
    },
    props: ['isMobile'],
});
</script>
