<template>
    <div class="apm-rearrange-items">
        <b-link
            @click.prevent="showModal()"
            class="apm-rearrange-items-link">
            <i class="mdi mdi-format-list-bulleted-square"></i>
        </b-link>

        <b-modal id="apm-rearrange-items-modal" size="md" :title="title" ok-only ok-title="Close">
            <draggable v-model="vModel" @start="drag=true" @end="drag=false">
                <div class="d-flex align-items-center item" v-for="element in vModel" :key="element._id">
                    <b-form-checkbox v-model="element.visible"/>
                    {{ element.label }}
                </div>
            </draggable>
        </b-modal>
    </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
    name: "ApmRearrangeItems",
    components: {draggable},
    props: {
        title: {},
        value: {},
        id: {}
    },
    data() {
        return {
            vModel: []
        }
    },
    methods: {
        showModal() {
            this.$bvModal.show('apm-rearrange-items-modal');
        },
        getItemsFromStorage() {
            let items = localStorage.getItem(this.id);
            if (items) {
                items = JSON.parse(items);
                // if a column is added or subtracted, update the new columns
                if (items.length !== this.value.length) {
                    for (const item of items) {
                        let valItem = this.value.find(i => i.name === item.name)
                        if (valItem) valItem.visibility = item.visibility;
                    }
                    return null;
                }
                return items;
            }
            return null;
        },
        saveItemsInStorage(items) {
            localStorage.setItem(this.id, JSON.stringify(items));
        },
    },
    mounted() {
        let items = this.getItemsFromStorage();
        if (!items) items = this.value;
        this.vModel = items;
        this.$nextTick();
    },
    watch: {
        vModel: {
            handler(value) {
                this.saveItemsInStorage(value);
                this.$emit('input', value);
            },
            deep: true
        }
    }
}
</script>

<style scoped lang="scss">
.item {
    cursor: move;
    padding: 0.25rem;

    &:hover {
        background-color: #eee;
    }
}
</style>
