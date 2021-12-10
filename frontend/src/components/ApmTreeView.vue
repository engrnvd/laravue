<template>
    <div class="apm-tree-view">
        <div class="apm-tree-item"
             :class="{'mb-4': item[childrenField]}"
             v-for="(item, index) in items"
             :key="index">
            <label class="d-flex align-items-center apm-tree-item-title"
                   :class="{'font-weight-bold': item[childrenField]}">
                <b-form-checkbox
                    v-model="item[itemCheckedField]"
                    @change="itemToggled(item)"
                    class="mr-2"
                />
                {{ item[itemTitleField] }}
            </label>

            <div class="apm-tree-children ml-4" v-if="item[childrenField]">
                <div class="apm-tree-child" v-for="(child, chIndex) in item[childrenField]" :key="chIndex">
                    <label class="d-flex align-items-center apm-tree-child-title">
                        <b-form-checkbox
                            v-model="child[childCheckedField]"
                            @change="childToggled(item, child)"
                            class="mr-2"
                        />
                        {{ child[childTitleField] }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ApmTreeView",
    props: {
        items: {},
        itemTitleField: {
            default: 'title'
        },
        itemCheckedField: {
            default: 'checked'
        },
        childrenField: {},
        childTitleField: {
            default: 'title'
        },
        childCheckedField: {
            default: 'checked'
        },
    },
    methods: {
        itemToggled(item) {
            if (!item[this.childrenField]) return;
            item[this.childrenField].forEach(child => this.$set(child, this.childCheckedField, !item[this.itemCheckedField]))
        },
        childToggled(item, child) {
            let checked = !child[this.childCheckedField];
            if (checked) {
                item[this.itemCheckedField] = true;
            } else {
                let checkedCount = item[this.childrenField].filter(ch => !!ch[this.childCheckedField]).length;
                if (checkedCount <= 1) item[this.itemCheckedField] = false;
            }
        },
    }
}
</script>
