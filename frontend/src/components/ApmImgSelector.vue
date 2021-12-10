<template>
    <div class="apm-img-selector"
         @mouseenter="showEditIcon = true"
         @mouseleave="showEditIcon = false">
        <slot></slot>
        <div class="backdrop" v-if="showEditIcon" @click="selectImage">
            <img src="/assets/img/pencil.svg">
        </div>
        <div v-if="saveReq.loading" class="loader">
            <i class="mdi mdi-loading mdi-spin mdi-24px text-light"></i>
        </div>
        <input ref="fileInput" type="file" accept="image/*" @input="pickFile" v-show="false">
    </div>
</template>

<script>
import {Resource} from "appmakers-vue/services/http-resource-service";

export default {
    name: "ApmImgSelector",
    props: {
        value: {},
        url: {},
        disabled: {}
    },
    data: () => ({
        showEditIcon: false,
        saveReq: new Resource('', 'post')
    }),
    methods: {
        selectImage() {
            if (this.disabled) return;
            this.$refs.fileInput.click();
        },
        async pickFile() {
            let input = this.$refs.fileInput;
            let file = input.files;
            if (file && file[0]) {
                let reader = new FileReader;
                reader.onload = async (e) => {
                    this.update(e.target.result);
                    if (this.url) {
                        this.saveReq.url = this.url;
                        this.saveReq.send({
                            data: {img_data: e.target.result},
                            onError: res => this.$notify('error', 'Error while updating photo', res.data)
                        })
                    }
                }
                reader.readAsDataURL(file[0]);
            }
        },
        update(data) {
            this.$emit('input', data);
        },
    }
}
</script>

<style scoped lang="scss">

.apm-img-selector {
    position: relative;
    overflow: hidden;
    width: fit-content;

    .backdrop {
        width: 100%;
        height: 40%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        bottom: 0;
        cursor: pointer;

        img {
            height: 70%;
        }
    }

    .loader {
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        bottom: 0;
        left: 0;
    }
}

</style>
