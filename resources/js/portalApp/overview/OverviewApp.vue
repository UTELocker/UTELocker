<template>
    <template v-if="isOverview()">
        <div>
            <a-row>
                <a-col :span="24">
                    <a-card>
                        <h1>Overview</h1>
                    </a-card>
                </a-col>
            </a-row>
        </div>
    </template>
    <template v-else>
        <router-view/>
    </template>
</template>
<script>
import {defineComponent} from "vue";
import {useRoute} from "vue-router";
export default defineComponent({
    name: "OverviewApp",
    components: {
    },
    setup() {
        const isOverview = () => {
            const route = useRoute();
            return route.path === '/portal' || route.path === '/portal/overview';
        };

        return {
            isOverview,
        };
    },
});
</script>
