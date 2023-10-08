<template>
    <header id="header" :class="headerClassName">
        <div class="alert-banner">
            Tải ứng dụng UTELocker ngay để trải nghiệm dịch vụ tốt nhất.
            <a target="_blank" href="https://utelocker.svute.com">Tải ngay!</a>
        </div>
        <a-row :style="{ flexFlow: 'nowrap', height: 64, position: 'relative' }">
            <a-col v-bind="colProps[0]">
                <Logo></Logo>
            </a-col>
            <a-col v-bind="colProps[1]" class="menu-row">
            </a-col>
        </a-row>
    </header>
</template>
<script>
import Logo from "./Logo.vue";
export default {
    name: "Header",
    components: {Logo},
    data() {
        return {
            headerClassName: {
                clearfix: true,
            },
            colProps: [
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
            ]
        }
    }
}
</script>
<style lang="scss" src="./Header.scss"></style>
<style scoped>
.alert-banner {
    position: relative;
    z-index: 100;
    padding: 16px;
    line-height: 28px;
    color: #8590a6;
    text-align: center;
    background-color: #141414;
}
.alert-banner {
    color: #fff;
    padding: 5px;
}
.alert-banner a {
    color: #fff;
    text-decoration: underline;
}
.alert-banner .close-icon {
    top: 12px;
}
</style>
