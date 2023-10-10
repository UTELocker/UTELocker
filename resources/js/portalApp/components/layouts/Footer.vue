<template>
    <footer id="footer">
        <div style="padding: 10px 144px">
            CopyRight © 2023
            <a href="https://beian.miit.gov.cn/#/Integrated/index" target="_blank">
                UTELocker
            </a>
        </div>
    </footer>
</template>
<script>
import dayjs from 'dayjs';
import { computed } from 'vue';
export default {
    props: {
        isCN: Boolean,
    },
    setup() {
        const showJeecg = computed(() => {
            return dayjs().isBefore(dayjs('2023-09-10'));
        });
        return {
            dayjs,
            showJeecg,
        };
    },
};
</script>
