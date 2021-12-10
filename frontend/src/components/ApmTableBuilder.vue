<template>
    <div class="apm-table-builder">
        <table v-if="columns.length || (value && value.length)" class="table">
            <thead>
            <tr>
                <th v-for="(column, i) in columns" :key="i">
                    <input
                        v-if="state.editColumn === i"
                        class="form-control"
                        v-autofocus
                        @blur="state.editColumn = -1"
                        @keypress.enter="state.editColumn = -1"
                        @input="updateColumn"
                        v-model="columns[i]">
                    <div v-else>
                        <span @click="state.editColumn = i">{{ column }}</span>
                        <a v-if="!readOnly" class="col-delete-btn" href @click.prevent="deleteColumn(i)">
                            <i class="mdi mdi-trash-can"></i>
                        </a>
                    </div>
                </th>
                <th v-if="!readOnly">
                    <a href @click.prevent="addColumn">
                        <i class="mdi mdi-plus-circle"></i>
                    </a>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, index) in value" :key="index">
                <td v-for="column in columns" :key="column">
                    <input
                        v-if="state.editRow === index"
                        class="form-control"
                        @keypress.enter="state.editRow = -1"
                        v-model="value[index][column]">
                    <div v-else @click="state.editRow = index">{{ row[column] }}</div>
                </td>
                <td v-if="!readOnly">
                    <div v-if="state.editRow === index">
                        <a href @click.prevent="state.editRow = -1">Done</a>
                    </div>
                    <div v-else>
                        <a href @click.prevent="state.editRow = index"><i class="mdi mdi-pencil"></i></a>&nbsp;&nbsp;
                        <a href @click.prevent="value.splice(index, 1)"><i class="mdi mdi-trash-can"></i></a>&nbsp;&nbsp;
                    </div>
                </td>
            </tr>
            <tr v-if="!readOnly">
                <td colspan="100">
                    <a href @click.prevent="addRow"><i class="mdi mdi-plus-circle"></i> Add row</a>
                </td>
            </tr>
            </tbody>
        </table>

        <b-input-group v-else>
            <input
                class="form-control"
                @keypress.enter="createColumns"
                placeholder="Enter comma separated list of fields for each item in the list"
                v-model="columnList">
            <b-input-group-append>
                <b-button :disabled="!columnList" @click="createColumns"><i class="mdi mdi-check"></i></b-button>
            </b-input-group-append>
        </b-input-group>
    </div>
</template>

<script>
export default {
    name: "ApmTableBuilder",
    props: {
        value: Array,
        readOnly: {type: Boolean, default: false}
    },
    data() {
        return {
            columns: [],
            columnList: '',
            state: {
                editColumn: -1,
                editRow: -1,
            }
        }
    },
    methods: {
        addRow() {
            let row = {};
            for (let column of this.columns) {
                row[column] = '';
            }
            this.value.push(row);
            this.state.editRow = this.value.length - 1;
        },
        updateColumn(e) {
            for (let row of this.value) {
                this.$set(row, e.srcElement.value, row[e.srcElement._value] || '');
                delete row[e.srcElement._value];
            }
            this.$emit('input', [...this.value]);
        },
        addColumn() {
            this.columns.push('');
            this.state.editColumn = this.columns.length - 1;
            this.$emit('input', [...this.value]);
        },
        createColumns() {
            if (!this.columnList.length) return;
            this.columns = this.columnList.split(/\, ?/);
            if (!this.value.length) {
                this.addRow();
            }
        },
        deleteColumn(i) {
            for (let row of this.value) {
                delete row[this.columns[i]];
            }
            this.columns.splice(i, 1);
            this.$emit('input', [...this.value]);
        }
    },
    mounted() {
        if (this.value && this.value.length) {
            for (let row of this.value) {
                for (let key in row) {
                    if (!this.columns.includes(key)) this.columns.push(key);
                }
            }
        }
    }
}
</script>

<style lang="scss">
.apm-table-builder {
    table {
        .form-control {
            width: auto;
            height: 1rem;
        }
    }
}

</style>
