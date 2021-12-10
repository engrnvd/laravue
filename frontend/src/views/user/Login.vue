<template>
<b-row class="h-100">
    <b-colxx xxs="12" md="10" class="mx-auto my-auto">
        <b-card class="auth-card" no-body>
            <image-side-bar message="Please use your credentials to login."/>
            <div class="form-side">
                <form-logo/>
                <h6 class="mb-4">{{ $t('user.login-title')}}</h6>

                <b-form @submit.prevent="formSubmit" class="av-tooltip tooltip-label-bottom">
                    <b-form-group :label="$t('user.email')" class="has-float-label mb-4">
                        <b-form-input ref="email" type="text" :value="$v.form.email.$model" @input="trimEmail" :state="!$v.form.email.$error" />
                        <b-form-invalid-feedback v-if="!$v.form.email.required">Please enter your email address</b-form-invalid-feedback>
                        <b-form-invalid-feedback v-else-if="!$v.form.email.email">Please enter a valid email address</b-form-invalid-feedback>
                        <b-form-invalid-feedback v-else-if="!$v.form.email.minLength">Your email must be minimum 4 characters</b-form-invalid-feedback>
                    </b-form-group>

                    <apm-form-element field="password" label="Password" :model="form">
                        <b-input-group>
                            <b-form-input :type="showPassword?'text':'password'" v-model="$v.form.password.$model" :state="!$v.form.password.$error" />
                            <b-form-invalid-feedback v-if="!$v.form.password.required">Please enter your password</b-form-invalid-feedback>
                            <b-form-invalid-feedback v-else-if="!$v.form.password.minLength || !$v.form.password.maxLength">Your password must be between 4 and 16 characters</b-form-invalid-feedback>
                            <b-input-group-append is-text>
                                <b-link @click="showPassword=!showPassword"
                                        :apm-tooltip="showPassword?'Hide Password':'Show Password'">
                                    <i class="text-primary mdi mdi-18px" :class="showPassword?'mdi-eye-off':'mdi-eye'"></i>
                                </b-link>
                            </b-input-group-append>
                        </b-input-group>
                    </apm-form-element>

                    <b-form-checkbox v-model="form.remember_me">Remember Me</b-form-checkbox>

                    <div class="d-flex justify-content-between align-items-center">
                        <router-link tag="a" to="/user/forgot-password">Forgot Password?</router-link>
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
                            <span class="label">{{ $t('user.login-button') }}</span>
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
import {env} from "@/env";
const {
    required,
    maxLength,
    minLength,
    email
} = require("vuelidate/lib/validators");

export default {
    data() {
        return {
            showPassword: false,
            form: {
                email: "",
                password: "",
                remember_me: false,
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
            email: {
                required,
                email,
                minLength: minLength(4)
            }
        }
    },
    computed: {
        ...mapGetters('auth', ["currentUser", "processing", "loginError"]),
    },
    methods: {
        ...mapActions('auth', ["login"]),
        formSubmit() {
            this.$v.$touch();
            this.$v.form.$touch();
            this.login(this.form);
        },
        trimEmail(email) {
            this.$refs.email.$el.value = this.$v.form.email.$model = email.replace(/\s/g, '');
        }
    },
    watch: {
        currentUser(user) {
            if (user && user._id && user._id.length > 0) {
                this.$router.push("/");
            }
        },
        loginError(val) {
            if (val != null) {
                this.$notify("error", "Login Error", val, {
                    duration: 3000,
                    permanent: false
                });

            }
        }
    }
};
</script>
