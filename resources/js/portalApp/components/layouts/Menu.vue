<template>
    <a-config-provider :theme="{ components: { Menu: { colorItemBg: colorBgContainer } } }">
        <a-menu
            :inline-indent="30"
            class="aside-container menu-site"
            mode="inline"
            :selected-keys="[activeMenuItem]"
        >
            <template v-for="m in menus">
                <template v-if="m.children">
                    <a-menu-item-group :key="m.order" :title="m.title">
                        <template v-for="n in m.children">
                            <a-menu-item v-if="n.path" :key="n.path">
                                <router-link :to="n.path">
                                    <span>{{ n.title }}</span>
                                </router-link>
                            </a-menu-item>
                        </template>
                    </a-menu-item-group>
                </template>
                <template v-else>
                    <a-menu-item :key="m.path">
                        <router-link :to="m.path">
                            {{ m.title }}
                        </router-link>
                    </a-menu-item>
                </template>
            </template>
        </a-menu>
    </a-config-provider>
</template>
<script>
import {computed, defineComponent, inject} from "vue";
import useSiteToken from "../../hooks/useSiteToken";

export default defineComponent({
    name: "Menu",
    props: {
        menus: {
            type: Object,
            default: () => {}
        },
        activeMenuItem: {
            type: String,
            default: '/portal',
        },
    },
    setup(props) {
        const siteToken = useSiteToken();
        const themeMode = inject('themeMode');
        const colorBgContainer = computed(() => siteToken.value.token.colorBgContainer);
        return {
            colorBgContainer,
            themeMode,
        }
    },
});
</script>
