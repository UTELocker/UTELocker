<template>
    <a-modal
        v-model:open="visible"
        :confirmLoading="isSubmitLoading"
        :width="800"
        :closable="false"
    >
        <template #title>
            <p style="font-size: 1.5rem; font-weight: 500; margin-bottom: 0;">
                Điều khoản đặt chỗ
            </p>
        </template>
        <template #footer>
            <a-row>
                <a-checkbox
                    v-model:checked="isConfirmPolicy"
                    style="margin-bottom: 1rem;"
                >
                    Tôi đã đọc và đồng ý với điều khoản
                </a-checkbox>
            </a-row>
            <a-row>
                <a-checkbox
                    v-model:checked="isDontShowAgain"
                    style="margin-bottom: 1rem;"
                >
                    Không hiển thị lại
                </a-checkbox>
            </a-row>
            <a-row>
                <a-button
                    @click="handleCancel"
                >
                    Quay về trang tìm kiếm
                </a-button>
                <a-button
                    type="primary"
                    @click="submit"
                    :loading="isSubmitLoading"
                    :disabled="!isConfirmPolicy"
                >
                    Đồng ý
                </a-button>
            </a-row>
        </template>
        <template #default>
            <div v-html="this.settings.config_policy"></div>
        </template>
    </a-modal>
</template>
<script>
import {defineComponent} from "vue";
import postBooking from "../mixins/apiBooking.js";
import {mapState} from "vuex";
import { Modal } from 'ant-design-vue';

export default defineComponent({
    name: "PolicyModal",
    mixins: [postBooking],
    props: {
        visible: {
            type: Boolean,
            default: false,
        },
        totalPrice: 0,
    },
    data() {
        return {
            isLoading: false,
            isSubmitLoading: false,
            endDate: null,
            startDate: null,
            isConfirmPolicy: false,
            isDontShowAgain: false,
        };
    },
    computed: {
        ...mapState({
            selectedSlots: (state) => state.moduleBooking.selectedSlots,
            settings: (state) => state.moduleBase.settings,
        }),
    },
    methods: {
        handleCancel() {
            return this.$router.push({name: 'booking'});
        },
        submit() {
            this.postBooking().then((res) => {
                if (res?.status === 'success') {
                    Modal.success({
                        title: 'Thành công',
                        content: 'Đặt chỗ thành công',
                        onOk: () => {
                            this.$router.push({name: 'booking'});
                        },
                    });
                } else {
                    const message = res?.message || 'Có lỗi xảy ra';
                    Modal.error({
                        title: 'Lỗi đặt chỗ',
                        content: message,
                        onOk: () => {
                            this.$router.push({name: 'booking'});
                        },
                    });
                }
            }).catch((e) => {
                const message = e?.message || 'Có lỗi xảy ra';
                Modal.error({
                    title: 'Lỗi đặt chỗ',
                    content: message,
                    onOk: () => {
                        this.$router.push({name: 'booking'});
                    },
                });
            });
        },
    },
    created() {
        this.startDate = this.$route.query.startDate;
        this.endDate = this.$route.query.endDate;
    },
});
</script>
<style scoped>
ol {
   list-style-type: decimal;
}
</style>
