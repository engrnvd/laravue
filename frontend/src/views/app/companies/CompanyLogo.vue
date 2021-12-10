<template>
    <div class="company-logo"
         :style="`background-image: url('${src}')`"
         :title="company.name"
    ></div>
</template>

<script>
export default {
    name: "CompanyLogo",
    props: {company: {}},
    computed: {
        src() {
            return this.company.logo.match(/^data\:.+/) ? this.company.logo : `${this.$env.apiUrl.replace(/\/$/, '')}${this.company.logo}`
        }
    }
}
</script>

<style lang="scss">
.company-logo {
    height: 3rem;
    width: 3rem;
    border-radius: 0.5rem;
    background-size: cover;
    background-position: center;
}

.company-logo-sm {
    height: 2rem;
    width: 2rem;
}
</style>
