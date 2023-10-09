<template>
    <div id="search-box" :class="{ 'narrow-mode': responsive, focused: !!focused }">
        <SearchOutlined />
        <a-input
            ref="inputRef"
            :placeholder="searchPlaceholder"
            @focus="triggerFocus(true)"
            @blur="triggerFocus(false)"
        ></a-input>
    </div>
</template>
<script>
import { ref, defineComponent } from 'vue';
import { SearchOutlined } from '@ant-design/icons-vue';

export default defineComponent({
    name: 'SearchBox',
    components: {
        SearchOutlined,
    },
    props: ['responsive'],
    emits: ['triggerFocus'],
    setup(props, { emit }) {
        const inputRef = ref();
        const focused = ref(false);
        function triggerFocus(focus) {
            focused.value = focus;
            emit('triggerFocus', focus);
        }
        return {
            inputRef,
            focused,
            triggerFocus,
            searchPlaceholder: 'Search...',
        };
    },
});
</script>
