<template>
    <div class="position-relative image-side">
        <p class="text-white h2">{{ $t('dashboards.magic-is-in-the-details') }}</p>
        <p class="white mb-0">
            {{message}}
            <br/>
            <span v-if="showRegisterLink">
                If you are not a member, please
                <router-link to="/user/register"
                             class="white font-italic font-weight-bold">
                    register</router-link>.
            </span>
            <span v-if="showLoginLink">
                If you are a member, please
                <router-link to="/user/login"
                             class="white font-italic font-weight-bold">
                    login</router-link>.
            </span>
        </p>
    </div>
</template>

<script>
    export default {
        name: "ImageSideBar",
        props: {
            message: {
                type: String,
                required: true,
            },
            showRegisterLink: {
                type: Boolean,
                default: true,
            },
            showLoginLink: {
                type: Boolean,
                default: false,
            },
        }
    }
</script>

<style scoped>

</style>