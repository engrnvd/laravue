<template>
    <b-dropdown class="dropdown-menu-right" right variant="empty" toggle-class="p-0"
                menu-class="mt-3">
        <template slot="button-content">
                        <span :style="{ color:isDarkActive?'#c0702f':'black'}" id="user-status"
                              apm-tooltip="user status"
                              tooltip-placement="left">
                            <span>{{status}}</span>
                            <span
                                    v-if="statusOptions.agent_status"> {{statusOptions.agent_status.auxCodeDuration|duration("%h%:%m%:%s%")}}</span></span>
        </template>
        <b-dropdown-item v-for="status in statusOptions.statuses" :key="status._id"
                         @click="updateStatus(status)"
                         :active="statusOptions.agent_status.status_id==status.code"
                         v-if="showStatus(status.code)"
        >{{status.name}}
        </b-dropdown-item>
    </b-dropdown>
</template>

<script>
    import {http} from "appmakers-vue/services/http-service";

    export default {
        name: "UserStatus",
        data() {
            return {
                statusOptions: [],
                status: "",
            }
        },
        mounted() {
            this.loadUserStatuses();
            setInterval(this.updateAuxCodeDuration, 1000);
        },
        methods: {
            showStatus(code) {
                return code !== 14 && code !== 16 && code !== 17 && code !== 3 && code !== 18;
            },
            updateAuxCodeDuration() {
                if (this.statusOptions && this.statusOptions.agent_status)
                    this.statusOptions.agent_status.auxCodeDuration++;
            },
            loadUserStatuses() {
                http.post('load-user-statuses', {
                    active: 1
                }).then(response => {
                    this.statusOptions = response.data;
                    for (let prop in this.statusOptions.statuses) {
                        if (this.statusOptions.statuses[prop]['code'] == this.statusOptions.agent_status.status_id)
                            this.status = this.statusOptions.statuses[prop]['name'];
                    }
                }).catch(error => {
                    this.$notify("error", "Error while loading user status", error.data, {
                        duration: 3000,
                        permanent: false
                    });
                })
            },
            updateStatus(status) {
                this.status = status.name;
                http.post('update-user-status', {
                    token: localStorage.getItem('token'),
                    status_id: status.code.toString()
                }).then(response => {
                    this.statusOptions.agent_status.auxCodeDuration = 0;
                    this.loadUserStatuses();
                    this.$notify("success", "Success", "Status updated.", {
                        duration: 3000,
                        permanent: false
                    });
                }).catch(error => {
                    this.$notify("error", "Error", "Error while updating user status.", {
                        duration: 3000,
                        permanent: false
                    });
                });

            },
        },
    }
</script>

<style scoped>

</style>
