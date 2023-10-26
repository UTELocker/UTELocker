<template>
    <div v-html="html"></div>
</template>
<script>
import {defineComponent} from "vue";

export default defineComponent({
    name: "HtmlRender",
    props: {
        html: {
            type: String,
            required: true,
        },
    },
});
</script>
