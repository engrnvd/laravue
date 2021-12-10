<template>
    <b-link
        :disabled="req.loading"
        variant="link"
        @click="downloadReport"
        apm-tooltip="Download data as csv">
        <span v-if="req.loading">
            <i class="mdi mdi-loading mdi-spin"></i>
        </span>
        <span v-else>
            <slot>
                <i class="mdi mdi-cloud-download-outline mdi-24px"></i>
            </slot>
        </span>
    </b-link>
</template>

<script>
export default {
    name: "ApmCsvDownloadBtn",
    props: {
        req: {},
        params: {},
        columns: {},
    },
    methods: {
        downloadReport() {
            let params = {...this.params};
            delete params.perPage;
            delete params.page;
            if (this.columns) {
                params.columns = this.columns.filter(c => c.visible).map(c => c.name)
            }
            this.req.send({
                params: params,
                onSuccess: (res) => {
                    let moment = require('moment');
                    let filename = this.req.url
                        .replace(/^\//, '')
                        .replace(/\?.*/, '')
                        .replace(/\//g, '-')
                        .replace(/download-csv/, '')
                        + moment().format('YYYY-MM-DD-hh-mm-a') + '.csv';
                    let csvString = res.data;
                    let blob = new Blob([csvString], {type: 'text/csv;charset=utf-8;'});
                    if (navigator.msSaveBlob) { // IE 10+
                        navigator.msSaveBlob(blob, filename);
                    } else {
                        let link = document.createElement("a");
                        if (link.download !== undefined) {
                            // feature detection
                            // Browsers that support HTML5 download attribute
                            let url = URL.createObjectURL(blob);
                            link.setAttribute("href", url);
                            link.setAttribute("download", filename);
                            link.style.visibility = 'hidden';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        } else {
                            this.$notify("error", "Deprecated browser", "You are using a very old browser. Please upgrade it in order to download the file.");
                        }
                    }
                },
                onError: (res) => {
                    this.$notify('error', 'Failed', res.data);
                },
            })
        },
    }
}
</script>
