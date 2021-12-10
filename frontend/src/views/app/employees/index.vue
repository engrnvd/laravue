<template>
    <div>
        <piaf-breadcrumb heading="Employees"/>

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
                           apm-tooltip="Reload Employees"
                           tooltip-placement="right"
                           @click.prevent="employees.send()">
                            <i class="mdi mdi-sync"></i>
                        </a>
                        <apm-csv-downloader class="mr-3 view-icon"
                                            :data="employees.data.data"
                                            file-name="employees.csv"
                                            apm-tooltip="Download data as csv"
                                            :fields="csvFields">
                            <i class="iconsminds-data-download"></i>
                        </apm-csv-downloader>
                        <b-button v-b-modal.createEmployeeModal
                                  variant="outline-dark"
                                  size="xs"
                                  class="top-right-button"
                        >Add New Employee
                        </b-button>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <apm-pagination-info :resource="employees"/>
                    </div>
                </div>
            </b-collapse>
        </div>

        <create-employee-modal @on-create="employees.send()" :companies-req="companiesReq"/>

        <div class="separator mb-5"></div>

        <b-card class="mb-4 table-responsive">
            <table class="table b-table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>
                        <apm-filter
                            field-name="first_name"
                            field-label="First Name"
                            v-model="employees.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="last_name"
                            field-label="Last Name"
                            v-model="employees.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="company_id"
                            field-label="Company"
                            v-model="employees.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="email"
                            field-label="Email"
                            v-model="employees.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="phone"
                            field-label="Phone"
                            v-model="employees.config.params"/>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="employee in employees.data.data">
                    <td>{{ employee._id }}</td>
                    <td>
                        <apm-editable
                            type="text"
                            field="first_name"
                            :url="`/employees/${employee._id}`"
                            v-model="employee.first_name"
                        ></apm-editable>
                    </td>
                    <td>
                        <apm-editable
                            type="text"
                            field="last_name"
                            :url="`/employees/${employee._id}`"
                            v-model="employee.last_name"
                        ></apm-editable>
                    </td>
                    <td>
                        <apm-editable
                            v-if="companiesReq.loaded"
                            type="select"
                            field="company_id"
                            :url="`/employees/${employee._id}`"
                            v-model="employee.company_id"
                            :dd-options="companiesReq.data"
                            dd-label-field="name"
                            dd-value-field="_id"
                        ></apm-editable>
                    </td>
                    <td>
                        <apm-editable
                            type="text"
                            field="email"
                            :url="`/employees/${employee._id}`"
                            v-model="employee.email"
                        ></apm-editable>
                    </td>
                    <td>
                        <apm-editable
                            type="text"
                            field="phone"
                            :url="`/employees/${employee._id}`"
                            v-model="employee.phone"
                        ></apm-editable>
                    </td>
                    <td class="table-actions">
                        <apm-delete-btn :url="`/employees/${employee._id}`"
                                        @on-success="employees.send()"></apm-delete-btn>
                    </td>
                </tr>
                <tr v-if="!employees.loading && !employees.data.data.length">
                    <td colspan="50" class="text-secondary">No records found.</td>
                </tr>
                </tbody>
            </table>
            <apm-pagination :resource="employees" class="justify-content-center"/>
            <apm-loader v-if="employees.loading">
                <div class="loading"></div>
            </apm-loader>
        </b-card>
    </div>
</template>

<script>
import {Resource} from "appmakers-vue/services/http-resource-service";
import CreateEmployeeModal from "./create";

export default {
    name: "index",
    components: {CreateEmployeeModal},
    data() {
        return {
            employees: new Resource('/employees', 'get', {
                pagination: true
            }),
            csvFields: [
                {name: '_id', label: 'ID'},
                {name: 'first_name', label: 'First Name'},
                {name: 'last_name', label: 'Last Name'},
                {name: 'company_id', label: 'Company'},
                {name: 'email', label: 'Email'},
                {name: 'phone', label: 'Phone'},
            ],
            companiesReq: new Resource('/companies', 'get')
        }
    },
    mounted() {
        this.employees.send();
        this.companiesReq.send()
    },
    watch: {
        'employees.config.params': {
            handler() {
                this.employees.send({delay: 500});
            },
            deep: true
        }
    }
}
</script>

<style scoped>
</style>
