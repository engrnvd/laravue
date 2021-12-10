<template>
    <b-modal id="createEmployeeModal"
             ref="createEmployeeModal"
             title="Add New Employee"
             hide-footer
             modal-class="modal-right">
        <apm-form action="/employees" v-model="form" @on-success="onSuccess">
            <apm-form-element field="first_name" label="First Name" :model="form">
                <b-form-input v-model="form.first_name"/>
            </apm-form-element>
            <apm-form-element field="last_name" label="Last Name" :model="form">
                <b-form-input v-model="form.last_name"/>
            </apm-form-element>
            <apm-form-element field="company_id" label="Company" :model="form">
                <v-select
                    v-model="form.company_id"
                    :options="companiesReq.data"
                    label="name"
                    :reduce="i => i._id"
                />
            </apm-form-element>
            <apm-form-element field="email" label="Email" :model="form">
                <b-form-input v-model="form.email"/>
            </apm-form-element>
            <apm-form-element field="phone" label="Phone" :model="form">
                <b-form-input v-model="form.phone"/>
            </apm-form-element>
            <b-button variant="outline-secondary" @click="hideModal" class="mr-2">Cancel</b-button>
            <b-button variant="primary" type="submit" class="mr-1">Save</b-button>
        </apm-form>
    </b-modal>
</template>

<script>
    export default {
        name: "CreateEmployeeModal",
        props: {companiesReq: Object},
        data() {
            return {
                form: {
                    first_name: '',
                    last_name: '',
                    company_id: '',
                    email: '',
                    phone: '',
                }
            }
        },
        methods: {
            hideModal() {
                this.$refs.createEmployeeModal.hide();
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

