<template>
    <Header />
    <div class="main-wrapper">
        <section>
            <router-view />
        </section>
        <Footer />
    </div>
</template>
<script>
import Header from "./header/Header.vue";
import {defineComponent, inject, ref} from "vue";
import {GLOBAL_CONFIG} from "../../SymbolKey";
import {useRoute} from "vue-router";
import {useWindowScroll} from "@vueuse/core";
import { CloseOutlined, MenuOutlined, LinkOutlined } from '@ant-design/icons-vue';
import Footer from "./Footer.vue";

export default defineComponent({
    name: "Layout",
    components: {
        Footer,
        Header,
        CloseOutlined,
        MenuOutlined,
        LinkOutlined,
    },
    setup() {
        const { y } = useWindowScroll();
        const visible = ref(false);
        const route = useRoute();
        const globalConfig = inject(GLOBAL_CONFIG);

        return {
            y,
            isMobile: globalConfig.isMobile,
            visible,
        };
    },
});

</script>
<style lang="scss" scoped>
.toc-affix {
    background-color: rgba(0, 0, 0, 0);
    backdrop-filter: blur(10px);
}

.toc-affix :deep(.ant-anchor) {
    font-size: 12px;
    max-width: 110px;

    .ant-anchor-ink::before {
        display: none;
    }
    .ant-anchor-ink-ball {
        display: none;
    }
}
</style>
