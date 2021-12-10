<template>
    <b-row class="h-100">
        <b-colxx xxs="12" md="10" class="mx-auto my-auto">
            <b-card class="auth-card" no-body>
                <image-side-bar message="Please set your panel password."
                                :show-register-link="false" :show-login-link="false"/>
                <div class="form-side">
                    <form-logo/>
                    <h6 class="mb-4">Set Password</h6>

                    <b-form @submit.prevent="formSubmit" class="av-tooltip tooltip-label-bottom">
                        <b-form-group :label="$t('user.password')" class="has-float-label mb-4">
                            <b-form-input type="password" v-model="$v.form.password.$model"
                                          :state="!$v.form.password.$error"/>
                            <b-form-invalid-feedback v-if="!$v.form.password.required">
                                Please enter your password
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback
                                v-else-if="!$v.form.password.minLength || !$v.form.password.maxLength">
                                Your password must be between 6 and 16 characters
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback
                                v-else-if="!$v.form.password.AtLeastOneDigit || !$v.form.password.AtLeastOneCharacter">
                                Your password must contain at least one letter and one digit
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group :label="$t('Re-enter Password')" class="has-float-label mb-4">
                            <b-form-input type="password" v-model="$v.form.password_confirmation.$model"
                                          :state="!$v.form.password_confirmation.$error"/>
                            <b-form-invalid-feedback v-if="!$v.form.password_confirmation.required">
                                Please enter your password again
                            </b-form-invalid-feedback>
                            <b-form-invalid-feedback v-else-if="!$v.form.password_confirmation.sameAsPassword">
                                Your passwords do not match
                            </b-form-invalid-feedback>
                        </b-form-group>

                        <div class="d-flex justify-content-between align-items-center">
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
                                <span class="label">Verify and save password</span>
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
    import {AtLeastOneCharacter} from 'appmakers-vue/validators/AtLeastOneCharactor';
    import {AtLeastOneDigit} from 'appmakers-vue/validators/AtLeastOneDigit';
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
                    password_confirmation: "",
                    token: this.$route.query.token,
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
                    minLength: minLength(6),
                    AtLeastOneCharacter,
                    AtLeastOneDigit,
                },
                password_confirmation: {
                    required,
                    sameAsPassword: sameAs('password')
                },
            }
        },
        computed: {
            ...mapGetters('auth', ["processing", "loginError", "accountVerifySuccess"])
        },
        methods: {
            ...mapActions('auth', ["verifyAccount"]),
            formSubmit() {
                this.$v.form.$touch();
                if (!this.$v.form.$anyError) {
                    this.verifyAccount(this.form).then(res => {
                        if (this.accountVerifySuccess) this.$router.push('/user/login');
                    });
                }
            }
        },
        watch: {
            loginError(val) {
                if (val != null) {
                    this.$notify("error", "Error verifying account", val, {
                        duration: 3000,
                        permanent: false
                    });

                }
            },
            accountVerifySuccess(val) {
                if (val) {
                    this.$notify(
                        "success",
                        "Welcome",
                        "Your account successfully verified", {
                            duration: 3000,
                            permanent: false
                        }
                    );
                }
            }
        }
    };
</script>
