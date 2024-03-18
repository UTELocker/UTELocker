<template>
    <a-drawer
        :open="!isAccept"
        right width="400"
        :placement="'bottom'"
        tile="Auth Wallet"
        :keyboard="false"
        :closable="false"
        :maskClosable="false"
    >
        <div style="display: flex !important; justify-content: center !important;">
            <a-card title="Password" style="width: 500px">
                <div style="display: flex; flex-direction: row; justify-content: center;">
                    <v-otp-input
                        ref="otpInput"
                        v-model:value="form.password"
                        input-classes="otp-input"
                        separator=""
                        :num-inputs="6"
                        :should-auto-focus="true"
                        :input-type="'letter-numeric'"
                        :conditionalClass="['one', 'two', 'three', 'four', 'five', 'six']"
                        :placeholder="['*', '*', '*', '*', '*', '*']"
                    />
                </div>

                <a-button
                type="primary"
                :loading="loading"
                style="width: 100%; margin-top: 20px;"
                @click="submit"
                >
                Xác nhận
            </a-button>
            <div
                style="color: 'gray'; font-size: 12px;"
            >
                Nếu bạn quên mật khẩu, vui lòng liên hệ với chúng tôi qua email.
            </div>
            <div
                style="color: 'gray'; font-size: 12px; margin-bottom: 10px;"
            >
                Mật khẩu mặc định là 123456.
            </div>
        </a-card>
        </div>
    </a-drawer>
</template>
<script>
import { defineComponent } from 'vue';
import VOtpInput from "vue3-otp-input";
import { post } from '../../portalApp/helpers/api';
import { WALLET_API } from '../constants/walletConstant';
import { mapState, mapActions } from 'vuex';
import firebase from 'firebase/compat/app';
import 'firebase/compat/auth';
import { Modal } from 'ant-design-vue';

export default defineComponent({
    name: 'PasswordDrawer',
    components: {
        VOtpInput,
    },
    props: {
        show: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            form: {
                password: '',
            },
            loading: false,
            appVerifier : '',
        };
    },
    computed: {
        ...mapState({
            user: state => state.moduleBase.user,
            isAccept: state => state.moduleWallet.isAccept,
        }),
    },
    methods: {
        ...mapActions({
            accept: 'moduleWallet/accept',
        }),
        submit() {
            this.loading = true;
            post(WALLET_API.POST_AUTHENTICATE(),{
                password: this.form.password,
            })
            .then(res => {
                if (res.data.status == 'success') {
                    this.loading = false;
                    this.accept();
                } else {
                    Modal.error({
                        title: 'Error',
                        content: res.data.message,
                    });
                    this.loading = false;
                }
            })
            .catch(err => {
                this.loading = false;
                Modal.error({
                    title: 'Error',
                    content: err.message,
                });
            })
        },
    },
});
</script>
<style>
.otp-input {
  width: 40px;
  height: 40px;
  padding: 5px;
  margin: 0 10px;
  font-size: 20px;
  border-radius: 4px;
  border: 1px solid rgba(0, 0, 0, 0.3);
  text-align: center;
}
/* Background colour of an input field with value */
.otp-input.is-complete {
  background-color: #e4e4e4;
}
.otp-input::-webkit-inner-spin-button,
.otp-input::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input::placeholder {
  font-size: 15px;
  text-align: center;
  font-weight: 600;
}
</style>
