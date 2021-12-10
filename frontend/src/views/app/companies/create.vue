<template>
    <b-modal id="createCompanyModal"
             ref="createCompanyModal"
             title="Add New Company"
             hide-footer
             modal-class="modal-right">
        <apm-form
            action="/companies"
            v-model="form"
            :attachments="form.attachments"
            post-single-attachment
            @on-success="onSuccess">
            <apm-form-element field="name" label="Name" :model="form">
                <b-form-input v-model="form.name"/>
            </apm-form-element>
            <apm-form-element field="email" label="Email" :model="form">
                <b-form-input v-model="form.email"/>
            </apm-form-element>
            <apm-form-element field="attachments" label="Logo" :model="form" :floating="false">
                <b-form-file v-model="form.attachments" accept="image/*"/>
            </apm-form-element>
            <apm-form-element field="website" label="Website" :model="form">
                <b-form-input v-model="form.website"/>
            </apm-form-element>
            <b-button variant="outline-secondary" @click="hideModal" class="mr-2">Cancel</b-button>
            <b-button variant="primary" type="submit" class="mr-1">Save</b-button>
        </apm-form>
    </b-modal>
</template>

<script>
    export default {
        name: "CreateCompanyModal",
        props: {},
        data() {
            return {
                form: {
                    name: '',
                    email: '',
                    attachments: [],
                    website: '',
                }
            }
        },
        methods: {
            hideModal() {
                this.$refs.createCompanyModal.hide();
            },
            onSuccess(res) {
                this.hideModal();
                this.$emit('on-create', res.data);
            }
        }
    }
</script>

<style scoped>

</style>

