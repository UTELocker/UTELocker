<template>
    <dollar-two-tone
        :two-tone-color="'#4eff00'"
        v-if="type === 'payment'"
    />
    <carry-out-two-tone
        :two-tone-color="'#2a8eff'"
        v-else-if="type === 'booking'"
    />
    <setting-two-tone
        :two-tone-color="'#d4ff14'"
        v-else-if="type === 'super_admin'"
    />
    <hdd-two-tone
        :two-tone-color="'#ff7300'"
        v-else-if="type === 'locker_system'"
    />
    <sliders-two-tone
        :two-tone-color="'#00ffd1'"
        v-else-if="type === 'site_group'"
    />
    <warning-two-tone
        :two-tone-color="'#ff0000'"
        v-else-if="type === 'report'"
    />
</template>
<script>
import {defineComponent} from "vue";
import {
    CarryOutTwoTone,
    DollarTwoTone,
    HddTwoTone,
    SettingTwoTone,
    SlidersTwoTone,
    WarningTwoTone
} from "@ant-design/icons-vue";

export default defineComponent({
    name: "IconNotification",
    components: {HddTwoTone, DollarTwoTone, SlidersTwoTone, WarningTwoTone, CarryOutTwoTone, SettingTwoTone},
    props: ['type'],
})
</script>
