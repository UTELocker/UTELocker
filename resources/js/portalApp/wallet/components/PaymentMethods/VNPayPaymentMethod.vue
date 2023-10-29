<template>
    <a-form
        :model="formState"
    >

    </a-form>
</template>
<script>
import {defineComponent, reactive} from "vue";

export default defineComponent({
    name: "VNPayPaymentMethod",
    data() {
        return {
            formState: reactive({
                amount: 0,

            })
        }
    },
});
</script>
