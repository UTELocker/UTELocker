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
        <div style="display: flex !important; justify-content: center !important;" v-if="!is2FA">
            <a-card title="Password" style="width: 500px">
                <div style="display: flex; flex-direction: row; justify-content: center;">
                    <v-otp-input
                        ref="otpInput"
                        v-model:value="form.password"
                        input-classes="otp-input"
                        separator=""
                        :num-inputs="6"
                        :should-auto-focus="true"
                        :input-type="letter-numeric"
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
        <div style="display: flex !important; justify-content: center !important;" v-if="is2FA">
            <a-card title="OTP" style="width: 500px">
                <div>
                    Nhập mã OTP được gửi về số điện thoại {{ user.mobile }}.
                </div>
                <div>
                    Mã OTP sẽ hết hạn sau <span>{{ timerCount }}</span> giây.
                    Nếu bạn chưa nhận được mã vui lòng nhấn <a @click="sendOTP" :style="{
                        color: timerCount <= 0 ? 'blue' : 'gray',
                        cursor: timerCount <= 0 ? 'pointer' : 'not-allowed',
                    }">đây</a> để gửi lại.
                </div>

                <div style="display: flex; flex-direction: row; justify-content: center; margin-top: 10px">
                    <v-otp-input
                        ref="otpInput"
                        v-model:value="otp"
                        input-classes="otp-input"
                        separator=""
                        :num-inputs="6"
                        :should-auto-focus="true"
                        :input-type="letter-numeric"
                        :conditionalClass="['one', 'two', 'three', 'four', 'five', 'six']"
                        :placeholder="['*', '*', '*', '*', '*', '*']"
                    />
                </div>

                <div id="recaptcha-container"></div>
                <a-button
                    type="primary"
                    :loading="loading"
                    style="width: 100%; margin-top: 20px;"
                    @click="verifyOtp"
                >
                    Xác nhận
                </a-button>
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
            is2FA: false,
            appVerifier : '',
            otp: '',
            test: '',
            timerCount: 60,
        };
    },
    computed: {
        ...mapState({
            user: state => state.moduleBase.user,
            isAccept: state => state.moduleWallet.isAccept,
        }),
    },
    watch: {
        timerCount: {
            handler(value) {
                if (value > 0 && this.is2FA) {
                    setTimeout(() => {
                        this.timerCount--;
                    }, 1000);
                }

            },
            immediate: true
        }
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
                    this.is2FA = true;
                    this.renderRecaptcha();
                    setTimeout(()=>{
                        this.sendOTP();
                        this.timerCount--;
                    },1000)
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

        renderRecaptcha() {
            setTimeout(()=>{
                let vm = this
                window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                    'size': 'invisible',
                    'callback': function(response) {

                    },
                    'expired-callback': function() {

                    }
                });
                this.appVerifier =  window.recaptchaVerifier
            },1000)
        },

        formatMobile(mobile) {
            return `+84${mobile.slice(1)}`
        },

        sendOTP() {
            let appVerifier = this.appVerifier
            let vm = this
            const phoneNumber = this.formatMobile(this.user.mobile);
            console.log('phoneNumber', phoneNumber)
            firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
                .then(function (confirmationResult) {
                    window.confirmationResult = confirmationResult;
                    vm.timerCount = 60;
                }).catch(function (error) {
                    console.log('error send otp', error)
                    Modal.error({
                        title: 'Error',
                        content: error.message,
                    });
            });
        },

        verifyOtp(){
            let vm = this
            this.loading = true;
            window.confirmationResult.confirm(this.otp).then(function (result) {
                vm.accept();
            }).catch(function (error) {
                console.log('error verify otp', error)
                Modal.error({
                    title: 'Error',
                    content: error.message,
                });
            });
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
