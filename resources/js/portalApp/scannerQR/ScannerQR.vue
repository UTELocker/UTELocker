<template>
    <a-space
        :size="5"
        direction="vertical"
        :style="{width: '100%', marginTop: '20px'}"
    >
        <a-page-header
            title="Quét QR Code"
            sub-title="Quét mã QR để truy cập"
            style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
            :back-icon="false"
        >
        </a-page-header>
        <div>
        <qrcode-stream
            :paused="paused"
            @detect="onDetect"
            @camera-on="onCameraOn"
            @camera-off="onCameraOff"
            @error="onError"
            :style="{ marginTop:'20px !important', width: 'auto !important', height: '70% !important'}"
            >
            <a-button @click="switchCamera" :style="{ background: 'transparent', height: 'auto', margin:'20px' }">
                <SwapOutlined :style="{ fontSize: '100px',}" />
            </a-button>
            <div
              v-show="showScanConfirmation"
              class="scan-confirmation"
            >
                <CheckCircleTwoTone :style="{ fontSize: '100px' }" />
            </div>
        </qrcode-stream>
        <a-modal
            v-model:visible="isShowModal"
            title="QR Code Result"
            @cancel="reloadScanner"
            @ok="reloadScanner"
        >
            <pre>{{ result }}</pre>
        </a-modal>
    </div>
    </a-space>
</template>

<script>
    import { CheckCircleTwoTone, SwapOutlined } from '@ant-design/icons-vue'
    import { QrcodeStream } from 'vue-qrcode-reader'
    const patternResultAccess = [
        'https://utelocker.dataviz.io.vn/portal/[0-9].$',
    ];

    export default {
        name: "ScannerQR",
        components: {
            QrcodeStream,
            CheckCircleTwoTone,
            SwapOutlined,
        },

        data() {
            return {
                paused: false,
                result: '',
                showScanConfirmation: false,
                isShowModal: false,
            }
        },

        methods: {
            switchCamera() {
                switch (this.facingMode) {
                    case 'environment':
                        this.facingMode = 'user'
                        break
                    case 'user':
                        this.facingMode = 'environment'
                        break
                }
            },
            onCameraOn() {
                this.showScanConfirmation = false
            },

            onCameraOff() {
                this.showScanConfirmation = true
            },

            onError(error) {
                const triedFrontCamera = this.facingMode === 'user'
                const triedRearCamera = this.facingMode === 'environment'

                const cameraMissingError = error.name === 'OverconstrainedError'

                if (triedRearCamera && cameraMissingError) {
                    this.noRearCamera = true
                }

                if (triedFrontCamera && cameraMissingError) {
                    this.noFrontCamera = true
                }

                console.error(error)
            },

            async onDetect(detectedCodes) {
                this.result = detectedCodes.map((code) => code.rawValue)
                this.paused = true

                if (patternResultAccess.some((pattern) => new RegExp(pattern).test(this.result))) {
                    this.showScanConfirmation = true

                    window.location.href = this.result

                } else {
                    this.isShowModal = true
                    this.result = "Vui lòng quét mã QR đúng định dạng!"
                }
            },

            reloadScanner() {
                this.paused = false
                this.showScanConfirmation = false
                this.isShowModal = false
            }
        }
    }
</script>

  <style scoped>
  .scan-confirmation {
    position: absolute;
    width: 100%;
    height: 100%;

    background-color: rgba(255, 255, 255, 0.8);

    display: flex;
    flex-flow: row nowrap;
    justify-content: center;
  }
  </style>
