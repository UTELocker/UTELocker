<template>
    <a-list
        v-if="comments.length"
        :data-source="comments"
        :header="`${comments.length} ${comments.length > 1 ? 'replies' : 'reply'}`"
        item-layout="horizontal"
        :style="{
            maxHeight: '500px',
            overflowY: 'scroll',
            overflowX: 'hidden',
        }"
    >
        <template #renderItem="{ item }">
            <a-list-item>
                <a-comment
                    :author="item.user_name"
                    :avatar="handleSrc(item.user_avatar)"
                    :content="item.content"
                    :datetime="item.created_at"
                />
            </a-list-item>
        </template>
    </a-list>
    <a-comment>
        <template #avatar>
            <a-avatar :src="handleSrc(this.user.avatar)" />
        </template>
        <template #content>
        <a-form-item>
            <a-textarea v-model:value="value" :rows="4" />
        </a-form-item>
        <a-form-item>
            <a-button html-type="submit" :loading="submitting" type="primary" @click="handleSubmit">
                Gửi
            </a-button>
        </a-form-item>
        </template>
    </a-comment>
</template>
<script>
import { defineComponent } from 'vue';
import dayjs from 'dayjs';
import { post } from '../helpers/api';
import { API } from '../constants/helpCallConstant';
import { mapState } from 'vuex';
import {USER_FOLDERS} from "../constants/userConstant";

export default defineComponent({
    name: 'HelpCallComments',
    components: {},
    props: {
        helpCall: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            comments: [],
            submitting: false,
            value: '',
        };
    },
    computed: {
        ...mapState({
            user: (state) => state.moduleBase.user,
        }),
    },
    methods : {
        handleSubmit() {
            if (!this.value) {
                return;
            }
            this.submitting = true;
            post(API.POST_HELP_CALL_COMMENT(this.helpCall.id),{
                content: this.value,
            })
                .then((res) => {
                    this.submitting = false;
                    this.value = '';
                    this.comments = [
                        {
                            user_avatar: this.user.avatar,
                            user_name: this.user.name,
                            content: res.data.data.content,
                            created_at: dayjs().format('YYYY-MM-DD HH:mm:ss'),
                        },
                        ...this.comments,
                    ];
                })
                .catch((err) => {
                    this.submitting = false;
                });
        },
        handleSrc(fileName) {
            if (fileName == null) {
                return '/images/default/avatarDefault.png';
            }
            return `${USER_FOLDERS}${fileName}`;
        }
    },
    created() {
        if (this.helpCall.comments) {
            this.comments = this.helpCall.comments.map((comment) => {
                return {
                    ...comment,
                    created_at: dayjs(comment.created_at).format('YYYY-MM-DD HH:mm:ss'),
                };
            });
        }
    },
});
</script>
