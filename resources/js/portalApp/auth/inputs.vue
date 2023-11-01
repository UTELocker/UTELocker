<template>
    <div id="inputs" class="inputs" @keyup="listenKeyUp" @input="listenInput">
        <input class="input" type="text"
            inputmode="numeric" maxlength="1" />
        <input class="input" type="text"
            inputmode="numeric" maxlength="1" />
        <input class="input" type="text"
            inputmode="numeric" maxlength="1" />
        <input class="input" type="text"
            inputmode="numeric" maxlength="1" />
        <input class="input" type="text"
            inputmode="numeric" maxlength="1" />
        <input class="input" type="text"
            inputmode="numeric" maxlength="1" />
    </div>
</template>
<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'Inputs',
    data() {
        return {
            values: [],
        }
    },
    methods: {
        listenInput(e) {
            const target = e.target;
            const val = target.value;

            if (isNaN(val)) {
                target.value = "";
                return;
            }

            if (val != "") {
                const next = target.nextElementSibling;
                if (next) {
                    next.focus();
                }
            }
        },
        listenKeyUp(e) {
            const target = e.target;
            const key = e.key.toLowerCase();

            this.values.push(key);
            if (key == "backspace" || key == "delete") {
                target.value = "";
                const prev = target.previousElementSibling;
                if (prev) {
                    prev.focus();
                }
                this.values.pop();
            }
            this.$emit("setInput", this.values);
        }
    },
});
</script>

<style scoped>

.inputs {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}
.input {
    width: 40px;
    border: none;
    border-bottom: 3px solid rgba(0, 0, 0, 0.5);
    margin: 0 10px;
    text-align: center;
    font-size: 36px;
    cursor: not-allowed;
    pointer-events: none;
}

.input:focus {
    border-bottom: 3px solid orange;
    outline: none;
}

.input:nth-child(1) {
    cursor: pointer;
    pointer-events: all;
}
</style>
