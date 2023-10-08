<template>
    <Header />
</template>
<script>
import Header from "./header/Header.vue";
import {defineComponent} from "vue";

export default defineComponent({
    name: "Layout",
    components: {
        Header,
    },
});

</script>
