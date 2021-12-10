<template>
<b-row class="h-100">
    <b-colxx xxs="12" md="10" class="mx-auto my-auto">
        <b-card class="auth-card" no-body>
            <image-side-bar message="Set new password for you account."/>
            <div class="form-side">
                <form-logo/>
                <h6 class="mb-4">Set your new login password for {{userEmail}}</h6>

                <b-form @submit.prevent="formSubmit" class="av-tooltip tooltip-label-bottom">
                    <b-form-group :label="$t('user.password')" class="has-float-label mb-4">
                        <b-form-input type="password" v-model="$v.form.password.$model" :state="!$v.form.password.$error" />
                        <b-form-invalid-feedback v-if="!$v.form.password.required">Please enter your password</b-form-invalid-feedback>
                        <b-form-invalid-feedback v-else-if="!$v.form.password.minLength || !$v.form.password.maxLength">Your password must be between 4 and 16 characters</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group :label="$t('Re-enter Password')" class="has-float-label mb-4">
                        <b-form-input type="password" v-model="$v.form.passwordAgain.$model" :state="!$v.form.passwordAgain.$error" />
                        <b-form-invalid-feedback v-if="!$v.form.passwordAgain.required">Please enter your password again</b-form-invalid-feedback>
                        <b-form-invalid-feedback v-else-if="!$v.form.passwordAgain.sameAsPassword">Your inputs does not match</b-form-invalid-feedback>

                    </b-form-group>

                    <div class="d-flex justify-content-between align-items-center">
                        <router-link tag="a" to="/user/login">
                            <i class="mdi mdi-arrow-left"></i> Back to Login
                        </router-link>
                        <b-button type="submit" variant="primary" size="lg" :disabled="processing" :class="{'btn-multiple-state btn-shadow': true,
                    'show-spinner': processing,
                    'show-success': !processing && loginError===false,
                    'show-fail': !processing && loginError }">
                            <span class="spinner d-inline-block">
                                <span class="bounce1"></span>
                                <span class="bounce2"></span>
                                <span class="bounce3"></span>
                            </span>
                            <span class="icon success">
                                <i class="simple-icon-check"></i>
                            </span>
                            <span class="icon fail">
                                <i class="simple-icon-exclamation"></i>
                            </span>
                            <span class="label">{{ $t('user.reset-password-button') }}</span>
                        </b-button>
                    </div>
                </b-form>
            </div>
        </b-card>
    </b-colxx>
</b-row>
</template>

<script>
import {
    mapGetters,
    mapActions
} from "vuex";
import {
    validationMixin
} from "vuelidate";
import FormLogo from './FormLogo';
import ImageSideBar from './ImageSideBar';
const {
    required,
    maxLength,
    minLength,
    email,
    sameAs
} = require("vuelidate/lib/validators");

export default {
    data() {
        return {
            form: {
                password: "",
                passwordAgain: ""
            },
        };
    },
    components: {
        FormLogo,
        ImageSideBar,
    },
    mixins: [validationMixin],
    validations: {
        form: {
            password: {
                required,
                maxLength: maxLength(16),
                minLength: minLength(4)
            },
            passwordAgain: {
                required,
                sameAsPassword: sameAs('password')

            },
        }
    },
    computed: {
        ...mapGetters('auth', ["currentUser", "processing", "loginError", "resetPasswordSuccess"]),
        userEmail() {
            return this.$route.query.email;
        },
    },
    methods: {
        ...mapActions('auth', ["resetPassword"]),
        formSubmit() {
            this.$v.form.$touch();
            if (!this.$v.form.$anyError) {
                this.resetPassword({
                    password: this.form.password,
                    password_confirmation: this.form.passwordAgain,
                    email: this.userEmail,
                    token: this.$route.query.reset_token
                }).then(res=>{

                });
            }
        }
    },
    watch: {
        loginError(val) {
            if (val != null) {
                this.$notify("error", "Reset Password Error", val, {
                    duration: 3000,
                    permanent: false
                });

            }
        },
        resetPasswordSuccess(val) {
            if (val) {
                this.$notify(
                    "success",
                    "Reset Password Success",
                    "Reset password success", {
                        duration: 3000,
                        permanent: false
                    }
                );
            }
        }
    }
};
</script>
