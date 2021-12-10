<template>
    <div>
        <piaf-breadcrumb heading="Companies"/>

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
                           apm-tooltip="Reload Companies"
                           tooltip-placement="right"
                           @click.prevent="companies.send()">
                            <i class="mdi mdi-sync"></i>
                        </a>
                        <apm-csv-downloader class="mr-3 view-icon"
                                            :data="companies.data.data"
                                            file-name="companies.csv"
                                            apm-tooltip="Download data as csv"
                                            :fields="csvFields">
                            <i class="iconsminds-data-download"></i>
                        </apm-csv-downloader>
                        <b-button v-b-modal.createCompanyModal
                                  variant="outline-dark"
                                  size="xs"
                                  class="top-right-button"
                        >Add New Company
                        </b-button>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <apm-pagination-info :resource="companies"/>
                    </div>
                </div>
            </b-collapse>
        </div>

        <create-company-modal @on-create="companies.send()"/>

        <div class="separator mb-5"></div>

        <b-card class="mb-4 table-responsive">
            <table class="table b-table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>
                        <apm-filter
                            field-name="name"
                            field-label="Name"
                            v-model="companies.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="email"
                            field-label="Email"
                            v-model="companies.config.params"/>
                    </th>
                    <th>
                        <apm-filter
                            field-name="website"
                            field-label="Website"
                            v-model="companies.config.params"/>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="company in companies.data.data">
                    <td class="img-parent">
                        <apm-img-selector v-model="company.logo" :url="`companies/change-pic/${company._id}`">
                            <company-logo :company="company"/>
                        </apm-img-selector>
                        <span class="badge badge-light">{{ company._id }}</span>
                    </td>
                    <td>
                        <apm-editable
                            type="text"
                            field="name"
                            :url="`/companies/${company._id}`"
                            v-model="company.name"
                        ></apm-editable>
                    </td>
                    <td>
                        <apm-editable
                            type="text"
                            field="email"
                            :url="`/companies/${company._id}`"
                            v-model="company.email"
                        ></apm-editable>
                    </td>
                    <td>
                        <apm-editable
                            type="text"
                            field="website"
                            :url="`/companies/${company._id}`"
                            v-model="company.website"
                        ></apm-editable>
                    </td>
                    <td class="table-actions">
                        <apm-delete-btn :url="`/companies/${company._id}`"
                                        @on-success="companies.send()"></apm-delete-btn>
                    </td>
                </tr>
                <tr v-if="!companies.loading && !companies.data.data.length">
                    <td colspan="50" class="text-secondary">No records found.</td>
                </tr>
                </tbody>
            </table>
            <apm-pagination :resource="companies" class="justify-content-center"/>
            <apm-loader v-if="companies.loading">
                <div class="loading"></div>
            </apm-loader>
        </b-card>
    </div>
</template>

<script>
import {Resource} from "appmakers-vue/services/http-resource-service";
import CreateCompanyModal from "./create";
import ApmImgSelector from "../../../components/ApmImgSelector";
import CompanyLogo from "./CompanyLogo";

export default {
    name: "index",
    components: {CompanyLogo, ApmImgSelector, CreateCompanyModal},
    data() {
        return {
            companies: new Resource('/companies', 'get', {
                pagination: true
            }),
            csvFields: [
                {name: '_id', label: 'ID'},
                {name: 'name', label: 'Name'},
                {name: 'email', label: 'Email'},
                {name: 'logo', label: 'Logo'},
                {name: 'website', label: 'Website'},
            ],
        }
    },
    mounted() {
        this.companies.send();
    },
    watch: {
        'companies.config.params': {
            handler() {
                this.companies.send({delay: 500});
            },
            deep: true
        }
    }
}
</script>

<style scoped lang="scss">
.img-parent {
    text-align: center;
    position: relative;

    .badge {
        position: absolute;
        left: 0.25rem;
        top: 0.25rem
    }
}
</style>
