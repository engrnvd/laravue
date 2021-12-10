<template>
    <apm-form-element :label="label" :field="field" :model="model">
        <v-select
            :options="options"
            :value="value"
            label="name"
            :reduce="c => {return !modelAsObject ? c._id : c}"
            :disabled="!req.loaded || disabled"
            @input="val => $emit('input', val)"
            :placeholder="placeholder"
        />
    </apm-form-element>
</template>

<script>
export default {
    name: "RemoteSelector",
    props: {
        req: {},
        value: {},
        label: {},
        model: {},
        field: {},
        placeholder: String,
        filter: {
            type: Function,
            default: item => true,
        },
        modelAsObject: {type: Boolean, default: false},
        disabled: {type: Boolean, default: false},
    },
    computed: {
        options() {
            return this.req.data.filter(this.filter);
        }
    },
    created() {
        if (!this.req.loaded) {
            this.req.send();
        }
    }
}
</script>

<style scoped lang="scss">

</style>
