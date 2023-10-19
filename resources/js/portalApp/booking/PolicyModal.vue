<template>
    <a-modal
        v-model:visible="visible"
        :confirmLoading="isSubmitLoading"
        :width="800"
        :closable="false"
    >
        <template #title>
            <p style="font-size: 1.5rem; font-weight: 500; margin-bottom: 0;">
                Policy
            </p>
        </template>
        <template #footer>
            <a-row>
                <a-checkbox
                    v-model:checked="isConfirmPolicy"
                    style="margin-bottom: 1rem;"
                >
                    I have read and agree to the policy
                </a-checkbox>
            </a-row>
            <a-row>
                <a-checkbox
                    v-model:checked="isDontShowAgain"
                    style="margin-bottom: 1rem;"
                >
                    Don't show again
                </a-checkbox>
            </a-row>
            <a-row>
                <a-button
                    @click="handleCancel"
                >
                    Return page booking
                </a-button>
                <a-button
                    type="primary"
                    @click="submit"
                    :loading="isSubmitLoading"
                    :disabled="!isConfirmPolicy"
                >
                    Confirm
                </a-button>
            </a-row>
        </template>
        <template #default>
            <a-row>
                <a-col :span="24">
                    <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0;">
                        1. You can cancel your booking 1 hour before the booking time.
                    </p>
                </a-col>
                <a-col :span="24">
                    <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0;">
                        2. If you cancel your booking after 1 hour before the booking time, you will be charged 50% of the total price.
                    </p>
                </a-col>
                <a-col :span="24">
                    <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0;">
                        3. If you do not show up, you will be charged 100% of the total price.
                    </p>
                </a-col>
            </a-row>
        </template>
    </a-modal>
</template>
<script>
import {defineComponent} from "vue";
import postBooking from "../mixins/apiBooking.js";
import {mapState} from "vuex";

export default defineComponent({
    name: "PolicyModal",
    mixins: [postBooking],
    props: {
        visible: {
            type: Boolean,
            default: false,
        },
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
        }),
    },
    methods: {
        handleCancel() {
            return this.$router.push({name: 'booking'});
        },
        submit() {
            this.postBooking();
        },
    },
    created() {
        this.startDate = this.$route.query.startDate;
        this.endDate = this.$route.query.endDate;
    },
});
</script>
