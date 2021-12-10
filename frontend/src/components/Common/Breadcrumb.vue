<template>
    <span>
      <h1 v-if="heading && heading.length>0">{{ heading }}</h1>
      <b-nav class="pt-0 breadcrumb-container d-none d-sm-block d-lg-inline-block">
          <b-breadcrumb :items="items"/>
      </b-nav>
    </span>
</template>

<script>
    import _ from "lodash";

    export default {
        props: ['heading'],
        data() {
            return {
                items: []
            }
        },
        methods: {
            getUrl(path, sub, index) {
                var pathToGo = '/' + path.split(sub)[0] + sub;
                if (pathToGo === '/app') {
                    pathToGo = '/'
                }
                return pathToGo
            },
            updateBreadCrumbs(route) {
                this.items = [];
                let path = route.path.substr(1);
                let rawPaths = path.split('/');

                for (var pName in route.params) {
                    if (rawPaths.includes(route.params[pName])) {
                        rawPaths = rawPaths.filter(x => x !== route.params[pName])
                    }
                }
                rawPaths.map((sub, index) => {
                    this.items.push({
                        text: sub.replace(/\-/g, ' ').replace(/\b\w/g, l => l.toUpperCase()),
                        to: this.getUrl(path, sub, index)
                    })
                });
            }
        },
        mounted() {
            this.updateBreadCrumbs(this.$route);
        },
        watch: {
            $route(to, from) {
                this.updateBreadCrumbs(to);
            },
        }
    }
</script>
