<template>
    <a-style-provider :hash-priority="hashPriority">
        <a-config-provider :theme="themeConfig">
            <SiteToken>
                <Layout />
            </SiteToken>
        </a-config-provider>
    </a-style-provider>
</template>
<script>
import SiteToken from "./SiteToken.vue";
import {computed, defineComponent, ref, watch, provide} from "vue";
import {theme as antdTheme} from 'ant-design-vue';
import {useRoute} from "vue-router";
import useMediaQuery from "./hooks/useMediaQuery";
import {GLOBAL_CONFIG} from "./SymbolKey";
import Layout from "./components/layouts/Layout.vue";

const getAlgorithm = (themes = []) =>
    themes
        .filter(theme => !!theme)
        .map(theme => {
            if (theme === 'dark') {
                return antdTheme.darkAlgorithm;
            }
            return antdTheme.defaultAlgorithm;
        });

export default defineComponent({
    name: "MainApp",
    components: {
        Layout,
        SiteToken,
    },
    setup() {
        const route = useRoute();
        const colSize = useMediaQuery();
        const isMobile = computed(() => colSize.value === 'sm' || colSize.value === 'xs');
        const theme = ref((localStorage.getItem('theme')) || 'light');
        const themeConfig = computed(() => {
            return { algorithm: getAlgorithm([...new Set([theme.value])]) };
        });
        const hashPriority = ref('low');
        watch(hashPriority, () => {
            location.reload();
        });

        const responsive = computed(() => {
            if (colSize.value === 'xs') {
                return 'crowded';
            } else if (colSize.value === 'sm') {
                return 'narrow';
            }
            return null;
        });

        const globalConfig = {
            isMobile,
            responsive,
        };

        const changeTheme = (t) => {
            theme.value = t;
            localStorage.setItem('theme', t);
        };

        provide('themeMode', {
            theme,
            changeTheme,
        });

        provide(GLOBAL_CONFIG, globalConfig);

        watch(
            theme,
            () => {
                if (theme.value === 'dark') {
                    document.getElementsByTagName('html')[0].setAttribute('data-doc-theme', 'dark');
                    document.getElementsByTagName('body')[0].setAttribute('data-theme', 'dark');
                    document.getElementsByTagName('html')[0].style.colorScheme = 'dark';
                } else {
                    document.getElementsByTagName('html')[0].setAttribute('data-doc-theme', 'light');
                    document.getElementsByTagName('body')[0].setAttribute('data-theme', 'light');
                    document.getElementsByTagName('html')[0].style.colorScheme = 'light';
                }
            },
            { immediate: true },
        );
        return { globalConfig, themeConfig, hashPriority };
    },
    methods: {
        ...mapActions({
            loadSettings: 'moduleBase/loadSettings',
            loadUser: 'moduleBase/loadUser',
        }),
    },
    created() {
        this.loadUser();
        this.loadSettings();
    }
});
</script>
