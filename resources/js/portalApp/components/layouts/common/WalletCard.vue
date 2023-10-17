<template>
    <div class="container">
        <a-card :style="{ width: '100%', backgroundColor: '#f0f2f5' }">
            <a-row :style="{marginBottom: '10px'}">
                <a-col :span="12" :style="{
                    borderRight: '1px solid #e8e8e8',
                    textAlign: 'center',
                }">
                    <p><strong>Wallet</strong></p>
                    <p>250,000đ</p>
                </a-col>
                <a-col :span="12" :style="{
                    textAlign: 'center',
                }">
                    <p><strong>Points</strong></p>
                    <p>250,000đ</p>
                </a-col>
            </a-row>
            <a-row>
                <a-col :span="24">
                    <a-card>
                        <a-card-grid :style="{
                            width: '50%',
                            textAlign: 'center',
                            cursor: 'pointer',
                            padding: '0',
                        }"
                             :hoverable="true"
                        >
                            <p>Top Up</p>
                        </a-card-grid>
                        <a-card-grid :style="{
                            width: '50%',
                            textAlign: 'center',
                            cursor: 'pointer',
                            padding: '0',
                        }"
                             :hoverable="true"
                        >
                            <p>UTEPay</p>
                        </a-card-grid>
                    </a-card>
                </a-col>
            </a-row>
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
    height: 125px;
}
.ant-card-bordered {
    border-radius: 0;
}

</style>
