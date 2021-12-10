<template>
    <b-dropdown variant="empty" size="sm" right toggle-class="header-icon notificationButton"
                menu-class="position-absolute mt-3 notificationDropdown" no-caret>
        <template slot="button-content">
            <b-spinner small variant="primary" v-if="notifications.loading"/>
            <span v-else>
                <i class="simple-icon-bell"/>
                <span class="count">{{notifications.data.length}}</span>
            </span>
        </template>
        <vue-perfect-scrollbar :settings="{ suppressScrollX: true, wheelPropagation: false }">
            <div class="d-flex flex-row mb-3 pb-3 border-bottom"
                 v-for="(n,index) in notifications.data"
                 :key="index">
                <img :src="dpUrl"
                     class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle"/>

                <div class="pl-2 position-relative notifications" @click="onSelectNotification(n)">
                    <p class="font-weight-medium mb-1">{{n.title}}</p>
                    <p class="text-muted mb-0 text-small truncate">{{n.message}}</p>
                    <p class="text-muted mb-0 text-small">{{n.created_at}}</p>
                </div>
                <b-link class="position-absolute text-primary mark-as-read-btn"
                        apm-tooltip="Mark as read" tooltip-placement="left"
                        @click="markAsRead(n._id)">
                    <b-spinner small variant="primary" v-show="readingNotification(n._id)"/>
                    <i v-show="!readingNotification(n._id)" class="mdi mdi-circle"></i>
                </b-link>
            </div>
            <div class="d-flex flex-row mb-3 pb-3 border-bottom" v-if="notifications.data.length<1">
                <p class="font-weight-medium mb-1">No notifications</p>
            </div>
        </vue-perfect-scrollbar>
    </b-dropdown>
</template>

<script>
    import {
        mapGetters,
        mapMutations,
        mapActions
    } from "vuex";
    import {env} from "../env";

    export default {
        data() {
            return {
                dpUrl: env.defaultDpUrl,
            };
        },
        mounted() {
            if (!this.notifications.loaded) this.load();
        },
        methods: {
            ...mapActions('notifications', [
                'load',
                'onSelectNotification',
                'markAsRead',
            ]),
            readingNotification(id) {
                return this.readNotification.loading && this.readNotification.config.data.id === id;
            },
        },
        computed: {
            ...mapGetters('notifications', [
                'notifications',
                'readNotification',
            ]),
        },
    };
</script>

<style scoped>
    .notifications {
        cursor: pointer;
    }

    .mark-as-read-btn {
        right: 0.5rem;
    }
</style>

