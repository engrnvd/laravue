<template>
    <div>
        <piaf-breadcrumb heading="Profile Settings"/>
        <div class="separator mb-2"></div>
        <b-card class="mb-4">
            <b-tabs content-class="mt-4">
                <b-tab class="text-center" title="Profile" :active="!!$route.path.match('profile')">
                    <div class="card-body w-40">
                        <a href="#"
                           class="router-link-exact-active active">
                            <img :src="currentUser.img" alt="Profile pic"
                                 class="img-thumbnail list-thumbnail rounded-circle border-0 mb-4">
                            <h6 class="mb-1">{{ fullName }}</h6>
                            <p class="text-muted text-small mb-4">Admin</p>
                        </a>
                        <table class="table b-table table-hover">
                            <thead class="text-justify">
                            <tr>
                                <th>First Name</th>
                                <td class="text-right">
                                    <apm-editable
                                        type="text"
                                        field="first_name"
                                        :url="`update-user/${currentUser._id}`"
                                        v-model="currentUser.first_name"
                                        @on-success="onUserUpdate"
                                    ></apm-editable>
                                </td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td class="text-right">
                                    <apm-editable
                                        type="text"
                                        field="last_name"
                                        :url="`update-user/${currentUser._id}`"
                                        v-model="currentUser.last_name"
                                        @on-success="onUserUpdate"
                                    ></apm-editable>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td class="text-right">
                                    {{currentUser.email}}
                                </td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td class="text-right">
                                    <apm-editable
                                            type="phone"
                                            field="phone"
                                            :url="`update-user/${currentUser._id}`"
                                            v-model="currentUser.phone"
                                            @on-success="onUserUpdate"
                                    ></apm-editable>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </b-tab>
                <b-tab class="text-center" title="Password">
                    <div class="form-side w-40">
                        <h3 class="mb-5">Change Password</h3>
                        <form v-on:submit.prevent="updatePassword()">
                            <fieldset class="form-group has-float-label mb-4" id="__BVID__29">
                                <legend tabindex="-1" class="col-form-label pt-0" id="__BVID__29__BV_label_">
                                    Current Password
                                </legend>
                                <input type="password" class="form-control is-valid"
                                       v-model="data.currentPassword" required>
                            </fieldset>
                            <fieldset class="form-group has-float-label mb-4">
                                <legend class="col-form-label pt-0">
                                    New Password
                                </legend>
                                <input type="password" class="form-control is-valid" required
                                       v-model="data.newPassword">
                            </fieldset>
                            <fieldset class="form-group has-float-label mb-4">
                                <legend class="col-form-label pt-0">Confirm Password</legend>
                                <input type="password" class="form-control is-valid" required
                                       v-model="data.confirmPassword">
                            </fieldset>
                            <div class="text-left">
                                <button type="submit"
                                        class="btn btn-primary btn-lg btn-multiple-state btn-shadow"><span
                                    class="label">Update</span></button>
                            </div>
                        </form>
                    </div>
                </b-tab>
                <b-tab class="text-center" title="Theme Settings"
                       :active="!!$route.path.match('theme-settings')">
                    <color-switcher></color-switcher>
                </b-tab>
            </b-tabs>
        </b-card>
    </div>
</template>

<script>
    import {http} from "appmakers-vue/services/http-service";
    import {mapMutations, mapState, mapGetters, mapActions} from "vuex";
    import ColorSwitcher from "../../../../components/Common/ColorSwitcher";

    export default {
        name: "index",
        components: {
            ColorSwitcher,
        },
        data() {
            return {
                data: {
                    currentPassword: '',
                    newPassword: '',
                    confirmPassword: '',
                    token: localStorage.getItem('token')
                }
            }
        },
        methods: {
            ...mapActions('auth', ['saveUser']),
            updatePassword: function () {
                if (this.data.newPassword !== this.data.confirmPassword) {
                    return this.notify("error", "Error", "password does not match.");
                }
                if (this.data.newPassword === this.data.currentPassword) {
                    return this.notify("error", "Error", "Please use different password.");
                }
                http.post('update-password', this.data).then(response => {
                    this.notify("success", "Success", response.data);
                }).catch(error => {
                    this.notify("error", "Error", error.data.message);
                });
            },
            notify: function (state, header, msg) {
                this.$notify(state, header, msg, {
                    duration: 3000,
                    permanent: false
                });
            },
            onUserUpdate(user) {
                console.log('user', user);
                this.saveUser(user.data);
            },
        },
        computed: {
            fullName: function () {
                return `${this.currentUser.first_name} ${this.currentUser.last_name}`
            },
            ...mapGetters('auth', ['currentUser'])
        },
    }
</script>

<style scoped>
</style>
