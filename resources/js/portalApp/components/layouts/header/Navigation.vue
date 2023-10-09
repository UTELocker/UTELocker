<template>
    <a-menu
        id="nav"
        class="menu-site"
        :mode="menuMode"
        :selected-keys="[activeMenuItem]"
        disabled-overflow
    >
        <a-menu-item key="portal">
            <router-link to="/portal">Portal</router-link>
        </a-menu-item>
        <a-menu-item key="wallet">
            <router-link to="/portal/wallet">UTEPay</router-link>
        </a-menu-item>
    </a-menu>
</template>
<script>
import {computed, defineComponent, inject, ref, watch} from "vue";
import {GLOBAL_CONFIG} from "../../../SymbolKey";
import {useRoute} from "vue-router";

export default defineComponent({
    name: "Navigation",
    setup() {
        const globalConfig = inject(GLOBAL_CONFIG);
        const menuMode = computed(() => {
            return globalConfig.isMobile.value ? 'inline' : 'horizontal';
        });
        const route = useRoute();
        const activeMenuItem = ref('portal');

        watch(
            () => route.path,
            pathname => {
                const modules = pathname.split('/');
                if (pathname === '/portal/wallet') {
                    activeMenuItem.value = 'wallet';
                } else {
                    activeMenuItem.value = 'portal';
                }
            },
            { immediate: true },
        );

        return {
            isMobile: globalConfig.isMobile,
            menuMode,
            activeMenuItem,
        }
    },
});
</script>
<style scoped lang="scss">
#nav {
    height: 100%;
    font-size: 14px;
    border: 0;

    &.ant-menu-horizontal {
        border-bottom: none;

        & > .ant-menu-item,
        & > .ant-menu-submenu {
            min-width: (40px + 12px * 2);
            height: var(--header-height);
            padding-right: 12px;
            padding-left: 12px;
            line-height: var(--header-height);

            &::after {
                top: 0;
                right: 12px;
                bottom: auto;
                left: 12px;
                border-width: var(--menu-item-border);
            }
        }

        & .ant-menu-submenu-title .anticon {
            margin: 0;
        }

        & > .ant-menu-item-selected {
            a {
                color: var(--primary-color);
            }
        }
    }

    & > .ant-menu-item,
    & > .ant-menu-submenu {
        text-align: center;
    }
}

.header-link {
    color: var(--site-text-color);
}

.ant-menu-item-active .header-link {
    color: var(--primary-color);
}

.popover-menu {
    width: 300px;

    .ant-popover-inner-content {
        padding: 0;

        #nav {
            .ant-menu-item,
            .ant-menu-submenu {
                text-align: left;
            }

            .ant-menu-item-group-title {
                padding-left: 24px;
            }

            .ant-menu-item-group-list {
                padding: 0 16px;
            }

            .ant-menu-item,
            a {
                color: #333;
            }
        }
    }
}
</style>
