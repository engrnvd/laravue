<template>
    <div class="apm-xlsx-uploader">
        <a class="view-icon" apm-tooltip="Bulk upload using xlsx / csv" href
           @click.prevent="$bvModal.show(modalId)">
            <i class="mdi mdi-upload"></i>
        </a>
        <b-modal :id="modalId" title="Bulk upload using XLSX / CSV" ok-only ok-title="Close">
            <p v-if="sampleUrl">Step 1:
                <a :href="sampleUrl" target="_blank">
                    <i class="mdi mdi-download"></i> Download sample File
                </a>
            </p>

            <p v-if="sampleUrl">Step 2: <i class="mdi mdi-file-edit"></i> Edit the file and put your data in it</p>

            <p>
                <span v-if="sampleUrl">Step 3:</span>
                <a href @click.prevent="$refs.xlsxFile.click()">
                    <i class="mdi mdi-upload"></i> Upload your updated File
                </a>
            </p>

            <p class="text-danger text-center" v-if="req.error">Error: {{ req.error }}</p>

            <div class="d-flex align-items-center justify-content-center">
                <b-form-file ref="xlsxFile" @change="onChange" class="mr-4"/>
                <b-button @click="upload" :disabled="!file || req.loading">
                    <i v-if="req.loading" class="mdi mdi-spin mdi-loading"></i>
                    <i v-else>Upload</i>
                </b-button>
            </div>
        </b-modal>
    </div>
</template>

<script>
import {Resource} from 'appmakers-vue/services/http-resource-service';

export default {
    name: "ApmXlsxUploader",
    props: {
        url: String,
        sampleUrl: String,
        closeOnSuccess: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            req: new Resource(this.url, 'post'),
            file: null
        }
    },
    methods: {
        upload() {
            this.req.data = [];
            this.req.upload({
                onSuccess: res => {
                    if (this.closeOnSuccess) this.$bvModal.hide(this.modalId);
                    this.req.data = res.data;
                    this.$emit('on-success', res);
                    this.$notify("success", "Success", res.data);
                },
                onError: res => {
                    this.req.error = res.data;
                    this.$emit('on-error', res);
                },
                afterRequest: res => {
                    this.$emit('after-request', res);
                },
            }, this.$refs.xlsxFile.files, 'file', true);
        },
        onChange(e) {
            this.file = e.target?.files?.length ? e.target.files[0] : null;
        }
    },
    computed: {
        modalId() {
            return 'apm-xlsx-uploader-modal'
        }
    }
}
</script>
