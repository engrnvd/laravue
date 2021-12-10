<template>
    <div id="app-container" :class="containerClasses">
        <top-nav/>
        <sidebar/>
        <main class="d-flex flex-column flex-grow-1">
            <div :class="{'container-fluid pt-4 d-flex flex-column flex-grow-1':true,'is-mobile':isMobile}">
                <router-view/>
            </div>
        </main>
        <footer-component/>
        <background-tasks-progress/>
    </div>
</template>

<script>
import Sidebar from '../../containers/Sidebar'
import TopNav from '../../containers/TopNav'
import Footer from '../../containers/Footer'
import {
    mapGetters, mapActions, mapMutations
} from 'vuex'
import BackgroundTasksProgress from "../../components/BackgroundTasksProgress";

export default {
    components: {
        BackgroundTasksProgress,
        'top-nav': TopNav,
        'sidebar': Sidebar,
        'footer-component': Footer
    },
    data() {
        return {
            tokenVerified: false,
        }
    },
    computed: {
        containerClasses() {
            return 'd-flex flex-column flex-grow-1 ' + this.getMenuType;
        },
        ...mapGetters('menu', ['getMenuType', 'menuHidden']),
        ...mapGetters('generic', ['isMobile']),
        ...mapGetters('auth', ['currentUser']),
    },
    methods: {
        ...mapActions('auth', ['verifyToken']),
        updatePageTitle(route) {
            let items = [];
            let path = route.path.substr(1);
            let rawPaths = path.split('/');

            for (var pName in route.params) {
                if (rawPaths.includes(route.params[pName])) {
                    rawPaths = rawPaths.filter(x => x !== route.params[pName])
                }
            }

            rawPaths.forEach(sub => {
                items.push(sub.replace(/\-/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));
            });

            document.head.querySelector('title').innerHTML = this.$env.appName + ' | ' + items.join(' | ');
        }
    },
    mounted() {
        // check if the login token is still valid
        if (!this.tokenVerified) {
            this.verifyToken();
            this.tokenVerified = true;
        }
    },
    updated() {
        if (!this.tokenVerified) {
            this.verifyToken();
            this.tokenVerified = true;
        }
    },
    watch: {
        $route(route) {
            this.updatePageTitle(route);
        }
    }
}
</script>

<style scoped lang="scss">
.is-mobile {
    padding: 0 !important;
}
</style>

<style lang="scss">
.ms-teams-app {
    main {
        margin: 0 !important;
    }

    .container-fluid {
        padding-top: 0 !important;
    }

    .page-footer {
        display: none;
    }

    .conversation-page {
        margin-bottom: 0 !important;
    }
}
</style>
