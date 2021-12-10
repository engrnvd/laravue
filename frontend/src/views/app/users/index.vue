<template>
    <div>
        <piaf-breadcrumb heading="Users"/>

        <div class="mb-3 mt-2 d-block">
            <b-button variant="empty"
                      class="pt-0 pl-0 d-inline-block d-md-none"
                      v-b-toggle.displayOptions>
                Options <i class="simple-icon-arrow-down align-middle"/>
            </b-button>
            <b-collapse id="displayOptions" class="d-md-block">
                <div class="d-flex justify-content-start align-items-center">
                    <div class="d-flex justify-content-start align-items-center flex-grow-1">
                        <a class="mr-2 view-icon" href
                           apm-tooltip="Reload Users"
                           tooltip-placement="right"
                           @click.prevent="users.send()">
                            <i class="mdi mdi-sync"></i>
                        </a>
                        <apm-csv-downloader
                            class="mr-3 view-icon"
                            :data="users.data.data"
                            file-name="users.csv"
                            apm-tooltip="Download data as csv"
                            :fields="csvFields">
                            <i class="iconsminds-data-download"></i>
                        </apm-csv-downloader>
                        <b-button v-b-modal.createUserModal
                                  variant="outline-dark"
                                  size="xs"
                                  class="top-right-button"
                        >Add New User
                        </b-button>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <apm-pagination-info :resource="users"/>
                    </div>
                </div>
            </b-collapse>
        </div>

        <create-user-modal @on-create="users.send()"/>

        <div class="separator mb-5"></div>

        <b-card class="mb-4 table-responsive">
            <table class="table b-table table-hover">
                <thead>
                <tr>
                    <th>
                        <apm-filter
                            field-name="first_name"
                            field-label="First Name"
                            v-model="users.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="last_name"
                            field-label="Last Name"
                            v-model="users.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="email"
                            field-label="Email"
                            v-model="users.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="phone"
                            field-label="Phone"
                            v-model="users.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="email_verified_at"
                            field-label="Verified At"
                            v-model="users.config.params"
                            field-type="date"
                        />
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users.data.data" :key="user._id">
                    <td>
                        <apm-editable
                            type="text"
                            field="first_name"
                            :url="`/users/${user._id}`"
                            v-model="user.first_name"
                        ></apm-editable>
                    </td>
                    <td>
                        <apm-editable
                            type="text"
                            field="last_name"
                            :url="`/users/${user._id}`"
                            v-model="user.last_name"
                        ></apm-editable>
                    </td>
                    <td>{{ user.email }}</td>
                    <td>
                        <apm-editable
                            type="text"
                            field="phone"
                            :url="`/users/${user._id}`"
                            v-model="user.phone"
                        ></apm-editable>
                    </td>
                    <td>{{ user.email_verified_at }}</td>
                    <td class="table-actions">
                        <VerifyEmailModel :user="user" v-if="!user.email_verified_at" @verified="updateUser"/>
                        <apm-delete-btn :url="`/users/${user._id}`" @on-success="users.send()"></apm-delete-btn>
                    </td>
                </tr>
                <tr v-if="!users.loading && !users.data.data.length">
                    <td colspan="50" class="text-secondary">No records found.</td>
                </tr>
                </tbody>
            </table>
            <apm-pagination :resource="users" class="justify-content-center"/>
            <apm-loader v-if="isLoading">
                <div class="loading"></div>
            </apm-loader>
        </b-card>
    </div>
</template>

<script>
import {Resource} from "appmakers-vue/services/http-resource-service";
import CreateUserModal from "./create";

export default {
    name: "index",
    components: {CreateUserModal},
    data() {
        return {
            users: new Resource('/users', 'get', {
                pagination: true
            }),
            csvFields: [
                {name: '_id', label: 'ID'},
                {name: 'first_name', label: 'First Name'},
                {name: 'last_name', label: 'Last Name'},
                {name: 'email', label: 'Email'},
                {name: 'phone', label: 'Phone'},
                {name: 'email_verified_at', label: 'Email Verified At'},
            ],
        }
    },
    computed: {
        isLoading() {
            return this.users.loading;
        },
    },
    mounted() {
        this.users.send();
    },
    watch: {
        'users.config.params': {
            handler() {
                this.users.send({delay: 500});
            },
            deep: true
        }
    }
}
</script>

<style scoped>
</style>
