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
                <inputs
                    @setInput="(val) => form.password = val"
                />

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
                    Nếu bạn chưa nhận được mã vui lòng nhấn <a @click="sendOTP">đây</a> để gửi lại.
                </div>

                <inputs
                    @setInput="(val) => otp = val"
                />
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
import Inputs from './inputs.vue';
import { post } from '../../portalApp/helpers/api';
import { WALLET_API } from '../constants/walletConstant';
import { mapState, mapActions } from 'vuex';
import firebase from 'firebase/compat/app';
import 'firebase/compat/auth';
import { Modal } from 'ant-design-vue';

export default defineComponent({
    name: 'PasswordDrawer',
    components: {
        Inputs,
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
                password: this.form.password.join(''),
            })
            .then(res => {
                this.loading = false;
                this.is2FA = true;
                this.renderRecaptcha();
                setTimeout(()=>{
                    this.sendOTP();
                },1000)
            })
            .catch(err => {
                this.loading = false;
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

        sendOTP() {
            let appVerifier = this.appVerifier
            const phoneNumber = '+84382349463';
            firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
                .then(function (confirmationResult) {
                    window.confirmationResult = confirmationResult;
                }).catch(function (error) {
                    Modal.error({
                        title: 'Error',
                        content: error.message,
                    });
            });
        },

        verifyOtp(){
            let vm = this
            this.loading = true;
            window.confirmationResult.confirm(this.otp.join('')).then(function (result) {
                vm.accept();
            }).catch(function (error) {
                Modal.error({
                    title: 'Error',
                    content: error.message,
                });
            });
        },
    },
});
</script>
