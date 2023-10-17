<template>
    <a-page-header
        title="Overview"
        sub-title="List of all your bookings activities"
        style="border: 1px solid rgb(235, 237, 240); border-radius: 0.5rem;"
        :back-icon="false"
    >
        <template #extra>
            <a-button type="primary" @click="() => $router.push({name: 'booking'})">
                Book Now
            </a-button>
        </template>
    </a-page-header>
    <a-space
        direction="vertical"
        :style="{
            width: '100%',
            margin: '1rem 0',
        }"
    >
        <a-row v-if="isLoading">
            <a-col :span="24">
                <a-skeleton active />
            </a-col>
        </a-row>
        <a-row v-else>
            <a-col :span="24">
                <a-list
                    :grid="{ gutter: 16, xs: 1, sm: 1, md: 2, lg: 2, xl: 3, xxl: 3 }"
                    :dataSource="bookingActivities"
                >
                    <template #renderItem="{ item }">
                        <a-list-item>
                            <a-card>
                                <a-row>
                                    <a-col :span="6">
                                        <div
                                            :style="{
                                                display: 'flex',
                                                flexDirection: 'column',
                                                justifyContent: 'center',
                                                alignItems: 'center',
                                            }"
                                        >
                                            <h3>{{item.slot_code}}</h3>
                                            <a-avatar
                                                :size="64"
                                                src="https://zos.alipayobjects.com/rmsportal/ODTLcjxAfvqbxHnVXCYX.png"
                                            />
                                        </div>
                                    </a-col>
                                    <a-col :span="10">
                                        <a-row>
                                            <a-col :span="24">
                                                <h3>{{item.title}}</h3>
                                            </a-col>
                                        </a-row>
                                        <a-row>
                                            <a-col :span="24">
                                                <p>Slot Code: {{item.slot_code}}</p>
                                                <p>Remaining Time: {{calcRemainingTime(item)}}</p>
                                                <p>Status: {{item.status}}</p>
                                            </a-col>
                                        </a-row>
                                    </a-col>
                                    <a-col :span="8"
                                        :style="{
                                            display: 'flex',
                                            flexDirection: 'column',
                                            justifyContent: 'center',
                                            alignItems: 'center',
                                        }"
                                    >
                                        <div>
                                            <a-button
                                                type="primary"
                                                shape="round"
                                                size="large"
                                                @click="handleClickShowButton(item)"
                                            >
                                                Unlock
                                            </a-button>
                                        </div>
                                    </a-col>
                                </a-row>
                                <a-divider />
                                <a-collapse ghost>
                                    <a-collapse-panel header="Booking settings">
                                        <a-button
                                            type="primary"
                                            size="large"
                                            :style="{
                                                width: '100%',
                                                marginBottom: '0.5rem',
                                            }"
                                            @click="() => $router.push({name: 'booking'})"
                                        >
                                            Extend
                                        </a-button>
                                        <a-button
                                            type="primary"
                                            size="large"
                                            danger
                                            :style="{
                                                width: '100%',
                                            }"
                                            @click="() => $router.push({name: 'booking'})"
                                        >
                                            Cancel
                                        </a-button>
                                    </a-collapse-panel>
                                </a-collapse>
                            </a-card>
                        </a-list-item>
                    </template>
                </a-list>
            </a-col>
            <a-drawer
                title="Unlock Pin Code"
                placement="bottom"
                :open="isShowDrawer"
                @close="onCloseDrawer"
                :style="{
                    position: 'absolute',
                    bottom: '0',
                }"
            >
                <h1>{{ chosenBooking.pin_code }}</h1>
            </a-drawer>
        </a-row>
    </a-space>
</template>
<script>
import {defineComponent} from "vue";
import {mapActions, mapState} from "vuex";
export default defineComponent({
    name: "OverviewApp",
    data() {
        return {
            isLoading: false,
            isShowDrawer: false,
            chosenBooking: {},
        }
    },
    computed: {
        ...mapState({
            bookingActivities: (state) => state.moduleBase.bookingActivities,
        }),
    },
    methods: {
        ...mapActions({
            getBookingActivities: 'moduleBase/loadBookingActivities',
        }),
        calcRemainingTime(booking) {
            return "1 hour";
        },
        handleClickShowButton(booking) {
            this.isShowDrawer = true;
            this.chosenBooking = booking;
        },
        onCloseDrawer() {
            this.isShowDrawer = false;
        },
    },
    created() {
        this.isLoading = true;
        this.getBookingActivities().then(() => {
            this.isLoading = false;
        });
    },
});
</script>
<style lang="scss">
.ant-list .ant-list-item {
    padding: 0;
}
</style>
