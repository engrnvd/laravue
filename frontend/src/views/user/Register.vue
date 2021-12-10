<template>
    <b-row class="h-100">
        <b-colxx xxs="12" md=10 class="mx-auto my-auto">
            <b-card class="auth-card" no-body>
                <image-side-bar message="Please use this form to register."
                                :show-register-link="false" :show-login-link="true"/>
                <div class="form-side">
                    <!--<form-logo/>-->
                    <h4 class="mb-4">{{ $t('user.register')}}</h4>
                    <p class="alert alert-success" v-if="showMessage">
                        {{ form.first_name }}, Thank you for your interest. We have sent an email to {{ form.email }}
                        with instructions on how to activate your account.
                    </p>
                    <apm-form
                        action="customers"
                        v-model="form"
                        @on-success="showMessage = true"
                        @on-error="onError"
                        retain-values
                        v-else>
                        <apm-form-element field="first_name" label="First Name" :model="form">
                            <b-form-input v-model="form.first_name"/>
                        </apm-form-element>
                        <apm-form-element field="last_name" label="Last Name" :model="form">
                            <b-form-input v-model="form.last_name"/>
                        </apm-form-element>
                        <apm-form-element field="company_name" label="Organization Name" :model="form">
                            <b-form-input v-model="form.company_name"/>
                        </apm-form-element>
                        <apm-form-element field="email" label="Email" :model="form">
                            <b-form-input v-model="form.email"/>
                            <b-modal v-model="emailExists" title="Email already exists">
                                {{ form.email }} is already associated with an account.
                                You can can either login or if you forgot your password, reset below.
                                <template #modal-footer="{ ok, cancel, hide }">
                                    <router-link
                                        to="/user/login"
                                        class="btn btn-primary"
                                    >Login</router-link>
                                    <router-link
                                        to="/user/forgot-password"
                                        class="btn btn-primary"
                                    >Forgot Password</router-link>
                                    <b-button variant="outline-secondary" @click="cancel()">Use a different Email</b-button>
                                </template>
                            </b-modal>
                        </apm-form-element>
                        <apm-form-element field="phone" label="Mobile Number" :model="form">
                            <b-form-input v-model="form.phone"/>
                        </apm-form-element>
                        <b-button variant="outline-secondary" class="mr-2">
                            Cancel
                        </b-button>
                        <b-button variant="primary" type="submit" class="mr-1">Register
                        </b-button>
                    </apm-form>
                </div>
            </b-card>
        </b-colxx>
    </b-row>
</template>
<script>
    import FormLogo from './FormLogo';
    import ImageSideBar from './ImageSideBar';
    export default {
        data() {
            return {
                showMessage: false,
                emailExists: false,
                form: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    company_name: '',
                    gather_only: false,
                    address: ''
                }
            }
        },
        components: {
            FormLogo,
            ImageSideBar,
        },
        mounted() {
            if (this.$route.query.product && this.$route.query.product.toLowerCase() === 'gather') {
                this.form.gather_only = true;
            }
        },
        methods: {
            onError(res) {
                this.emailExists =
                    res.data.errors
                    && res.data.errors.email.length
                    && res.data.errors.email[0] === "The email has already been taken.";
                console.log('res', res.data.errors);
            }
        },
    }
</script>
