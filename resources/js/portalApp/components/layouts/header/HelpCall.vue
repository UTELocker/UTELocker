<template>
    <a-dropdown :trigger="['click']"  :arrow="{ pointAtCenter: true }" :placement="'bottomRight'">
    <a class="ant-dropdown-link" @click.prevent>
        <a-badge>
            <a-avatar shape="square" size="large" style="background-color: #034da9;">
                <CustomerServiceTwoTone two-tone-color="#FFFF" />
            </a-avatar>
        </a-badge>
    </a>
    <template #overlay>
      <a-menu>
        <a-menu-item v-for="item in menu" :key="item.key">
            <router-link :to="item.path">
                <span>{{ item.title }}</span>
            </router-link>
        </a-menu-item>
      </a-menu>
    </template>
  </a-dropdown>
</template>
<script>
import { defineComponent } from 'vue';
import { CustomerServiceTwoTone } from '@ant-design/icons-vue';
import { HELP_CALL_MENU_USER, HELP_CALL_MENU_ADMIN } from '../../../constants/helpCallConstant';
import { mapState, useStore } from 'vuex';
import { USER_TYPE } from '../../../constants/userConstant';
export default defineComponent({
    name: 'HelpCall',
    components: {
        CustomerServiceTwoTone,
    },
    setup() {
        const store = useStore()
        const user = store.state.moduleBase.user
        const menu = user.type === USER_TYPE.ADMIN ? HELP_CALL_MENU_ADMIN : HELP_CALL_MENU_USER

        return {
            menu
        };
    },
    computed: {
        ...mapState({
            user: (state) => state.moduleBase.user,
        }),
    },
    methods: {

    },
});
</script>
