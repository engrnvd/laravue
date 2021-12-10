<template>
    <span>
        <b-link @click.prevent="showModal()" apm-tooltip="Mark it verified">
            <i class="mdi mdi-email-check"></i>
        </b-link>
        <b-modal scrollable title="Set Password"
                 ok-title="Mark as Verified"
                 @ok="save"
                 size="sm"
                 footer-class="p-1"
                 header-class="p-3"
                 v-model="showEditModal">
            <apm-form :action="`/verify-user/${user._id}`" v-model="form" @on-success="onSuccess"
                      ref="verifyEmailForm">
            <apm-form-element field="password" label="Password" :model="form">
                <b-form-input type="password" v-model="form.password"/>
            </apm-form-element>
            <apm-form-element field="password_confirmation" label="Confirm Password" :model="form">
                <b-form-input type="password" placeholder="Confirm Password" v-model="form.password_confirmation"/>
            </apm-form-element>
            </apm-form>
        </b-modal>
    </span>
</template>

<script>

    export default {
        name: "VerifyEmailModel",
        props: {
            user: Object,
        },
        data() {
            return {
                showEditModal: false,
                form: {
                    password: '',
                    password_confirmation: '',
                },
            }
        },
        methods: {
            showModal() {
                this.showEditModal = !this.showEditModal;
            },
            save(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.$refs.verifyEmailForm.saveForm();
            },
            onSuccess(res) {
                this.$emit('verified', res.data);
                this.$notify('success', 'Done', 'Saved');
                this.showEditModal = false;
            },
        },
    }
</script>