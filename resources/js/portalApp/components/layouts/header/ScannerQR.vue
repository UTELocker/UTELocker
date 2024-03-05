<template>
    <a-avatar shape="square" size="large" style="background-color: #B5EBFF;" :class="'nav-qr-badge'" @click="this.$router.push({ name: 'scanner-qr' })">
        <QrcodeOutlined />
    </a-avatar>
</template>
<script>
import { defineComponent } from 'vue';
import { QrcodeOutlined } from '@ant-design/icons-vue';
export default defineComponent({
    name: 'ScannerQr',
    components: {
        QrcodeOutlined,
    },
});
</script>

<style>
.nav-qr-badge {
    position: absolute;
    top: 50%;
    right: 95px;
    transform: translateY(-50%);
    z-index: 1;
    cursor: pointer;
    font-size: 18px;
    color: var(--text-color);
}
</style>
