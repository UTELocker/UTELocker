<template>
    <Header />
    <div class="main-wrapper">
        <a-row>
            <template v-if="isMobile">
                <a-drawer
                    key="mobile-menu"
                    v-model:visible="visible"
                    :closable="false"
                    placement="left"
                    class="drawer drawer-left"
                    wrapper-class-name="drawer-wrapper"
                    width="60%"
                >
                    <WalletCard />
                    <Menu :menus="menuConstant" :active-menu-item="activeMenuItem" />
                </a-drawer>
                <div class="drawer-handle" @click="handleClickShowButton">
                    <close-outlined v-if="visible" :style="iconStyle" />
                    <MenuOutlined v-else :style="iconStyle" />
                </div>
            </template>
            <template v-else>
                <a-col :xxxl="4" :xxl="4" :xl="5" :lg="6" :md="6" :sm="24" :xs="24" class="main-menu">
                    <a-affix>
                        <section class="main-menu-inner">
                            <div>
                                <WalletCard />
                            </div>
                            <Menu :menus="menuConstant" :active-menu-item="activeMenuItem" />
                        </section>
                    </a-affix>
                </a-col>
            </template>
            <a-col :xxxl="20" :xxl="20" :xl="19" :lg="18" :md="18" :sm="24" :xs="24">
                <section :class="mainContainerClass">
                    <router-view />
                </section>
            </a-col>
        </a-row>
        <Footer />
    </div>
</template>
<script>
import Header from "./header/Header.vue";
import {computed, defineComponent, inject, ref, watch} from "vue";
import {GLOBAL_CONFIG} from "../../SymbolKey";
import {useRoute} from "vue-router";
import {useWindowScroll} from "@vueuse/core";
import { CloseOutlined, MenuOutlined, LinkOutlined } from '@ant-design/icons-vue';
import Footer from "./Footer.vue";
import Menu from "./Menu.vue";
import WalletCard from "./common/WalletCard.vue";
import menuConstant from "../../constants/menuConstant";
import OverviewApp from "../../overview/OverviewApp.vue";

export default defineComponent({
    name: "Layout",
    computed: {
        menuConstant() {
            return menuConstant
        },
    },
    components: {
        OverviewApp,
        WalletCard,
        Menu,
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

        const mainContainerClass = computed(() => {
            return {
                'main-container': true,
            };
        });

        const activeMenuItem = ref('/portal');

        watch(() => route.path, () => {
            activeMenuItem.value = route.path;
        });

        const handleClickShowButton = () => {
            visible.value = !visible.value;
        };

        return {
            y,
            isMobile: globalConfig.isMobile,
            visible,
            handleClickShowButton,
            mainContainerClass,
            iconStyle: {
                fontSize: '20px',
            },
            activeMenuItem,
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
