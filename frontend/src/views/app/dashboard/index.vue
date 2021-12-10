<template>
    <div>
        <b-row>
            <b-colxx xxs="12">
                <piaf-breadcrumb heading="Dashboard"/>
                <div class="separator mb-2"></div>
            </b-colxx>
        </b-row>
        <b-row>
            <b-colxx xxs="12">
                <b-card class="mb-4" title="Dashboard">
                </b-card>
            </b-colxx>
        </b-row>
    </div>
</template>

<script>
import {hideSideMenu} from "@/mixins/hide-side-menu";

export default {
    name: "Dashboard",
    //mixins: [hideSideMenu],
    data() {
        return {}
    },
    methods: {}
}
</script>
