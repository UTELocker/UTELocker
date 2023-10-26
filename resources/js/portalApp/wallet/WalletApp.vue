<template>
    <a-card class="header-wallet-card">
        <div :style="{
            display: 'flex',
            justifyContent: 'space-between',
        }">
            <div>
                <p style="font-size: medium;">
                    Current Balance
                    <a-tooltip placement="top">
                        <template #title>
                            <span v-if="!isVisibleBalance">Click to show balance</span>
                            <span v-else>Click to hide balance</span>
                        </template>
                        <EyeOutlined v-if="!isVisibleBalance" @click="toggleIsVisibleBalance" />
                        <EyeInvisibleOutlined v-else @click="toggleIsVisibleBalance" />
                    </a-tooltip>
                </p>
                <h1>{{this.getWalletBalance()}}</h1>
                <p style="font-size: medium;">Points: <strong>{{this.getWalletPoints()}}</strong></p>
            </div>
            <div
                v-if="!isMobile"
                class="wallet-action"
            >
                <a-button type="primary" @click="this.$router.push({name: 'wallet.topup'})">Top Up</a-button>
                <a-button type="primary">Withdraw</a-button>
            </div>
        </div>
    </a-card>
</template>
<script>
import {defineComponent, inject} from "vue";
import {EyeOutlined, EyeInvisibleOutlined} from '@ant-design/icons-vue';
import walletMix from "../mixins/walletMix";
import {GLOBAL_CONFIG} from "../SymbolKey";

export default defineComponent({
    name: "WalletApp",
    mixins: [walletMix],
    components: {
        EyeOutlined,
        EyeInvisibleOutlined
    },
    setup() {
        const globalConfig = inject(GLOBAL_CONFIG);

        return {
            isMobile: globalConfig.isMobile,
        }
    }
});
</script>
<style scoped lang="scss">
.header-wallet-card {
    width: 100%;

    .ant-card-body {
        display: flex;
        justify-content: space-between;
    }
}
.wallet-action {
    display: flex;
    align-items: center;
    justify-content: center;

    button {
        margin-left: 10px;
    }
}
</style>
