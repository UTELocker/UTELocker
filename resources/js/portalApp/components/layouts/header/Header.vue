<template>
    <header id="header" :class="headerClassName">
        <a-row :style="{ flexFlow: 'nowrap', height: 64, position: 'relative' }">
            <a-col v-bind="colProps[0]">
                <Logo />
            </a-col>
            <a-col v-bind="colProps[1]" class="menu-row">
                <SearchBox
                    key="search"
                    :responsive="responsive"
                    @triggerFocus="onTriggerSearching"
                />
                <Menu v-if="!isMobile" />
            </a-col>
            <a-popover
                v-model:open="menuOpen"
                overlay-class-name="popover-menu"
                placement="bottomRight"
                trigger="click"
                arrow-point-at-center
            >
                <UnorderedListOutlined class="nav-phone-icon" />
                <template #content>
                    <Menu :is-mobile="isMobile" />
                </template>
            </a-popover>
        </a-row>
    </header>
</template>
<script>
import Logo from "./Logo.vue";
import {useRoute} from "vue-router";
import {computed, defineComponent, inject, ref} from "vue";
import {GLOBAL_CONFIG} from "../../../SymbolKey";
import Menu from "./Menu.vue";
import { UnorderedListOutlined } from '@ant-design/icons-vue';
import SearchBox from "./SearchBox.vue";

export default defineComponent({
    name: "Header",
    components: {
        SearchBox,
        Menu,
        Logo,
        UnorderedListOutlined,
    },
    setup() {
        const route = useRoute();
        const cancelButtonProps = {
            style: { display: 'none' },
        };

        const globalConfig = inject(GLOBAL_CONFIG);
        const isHome = computed(() => {
            return ['/portal', '/portal/'].includes(route.path);
        });

        const menuOpen = ref(false);
        const colProps = [
            {
                xxxl: 4,
                xxl: 4,
                xl: 5,
                lg: 6,
                md: 6,
                sm: 24,
                xs: 24,
            },
            {
                xxxl: 20,
                xxl: 20,
                xl: 19,
                lg: 18,
                md: 18,
                sm: 0,
                xs: 0,
            },
        ];

        const searching = ref(false);
        const onTriggerSearching = (value) => {
            searching.value = value;
        };

        return {
            colProps,
            menuOpen,
            globalConfig,
            isHome,
            cancelButtonProps,
            isMobile: globalConfig.isMobile,
            headerClassName: {
                clearfix: true
            },
            responsive: globalConfig.responsive,
            onTriggerSearching
        }
    },
});
</script>
<style lang="scss" src="./Header.scss"></style>
