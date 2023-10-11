<template>
    <div class="container">
        <a-card :style="{ width: '100%' }">
            <p>@ngtrdai</p>
            <p>Balance: 450,000đ</p>
            <p>Promotion: 450,000đ</p>
        </a-card>
    </div>
    <div class="placeholder"></div>
</template>
<script>
import {defineComponent} from "vue";

export default defineComponent({
    name: "WalletCard",
    setup(props) {},
});
</script>
<style scoped>
.container {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 9;
    width: 100%;
    height: 100px;
}
.placeholder {
    height: 116px;
}
.ant-card-bordered {
    border-radius: 0;
}

</style>
