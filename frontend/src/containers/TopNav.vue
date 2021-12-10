<template>
    <nav class="navbar fixed-top">
        <div class="d-flex align-items-center navbar-left">
            <a href="#" class="menu-button d-none d-md-block"
               @click.prevent="changeSideMenuStatus({step :menuClickCount+1,classNames:menuType,selectedMenuHasSubItems})">
                <menu-icon/>
            </a>
            <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none"
               @click.prevent="changeSideMenuForMobile(menuType)">
                <mobile-menu-icon/>
            </a>
            <b-button v-if="currentUser && currentUser.customer && currentUser.customer.plan.plan_code===1"
                      variant="outline-primary"
                      class="ml-2"
                      @click="goToProfile('account')">Upgrade
            </b-button>
        </div>

        <router-link class="navbar-logo" tag="a" to="/">
            <span class="logo d-none d-xs-block"></span>
            <span class="logo-mobile d-block d-xs-none"></span>
        </router-link>

        <div class="navbar-right">
            <div class="d-none d-md-inline-block align-middle mr-3">
                <switches v-model="isDarkActive" apm-tooltip="Dark Mode" theme="custom" class="vue-switcher-small mb-0"
                          tooltip-placement="left"
                          color="primary"/>
            </div>
            <div class="header-icons d-inline-block align-middle">
                <div class="position-relative d-none d-sm-inline-block">
                    <div class="btn-group">
                        <b-button variant="empty" class="header-icon btn-sm" @click="toggleFullScreen">
                            <i :class="{'d-inline-block':true,'mdi mdi-fullscreen-exit':fullScreen,'mdi mdi-fullscreen':!fullScreen }"/>
                        </b-button>
                    </div>
                </div>
            </div>
            <div class="user d-inline-block">
                <b-dropdown class="dropdown-menu-right" right variant="empty" toggle-class="p-0" menu-class="mt-3"
                            no-caret>
                    <template slot="button-content">
                        <span class="name mr-1">
                            {{ currentUser.first_name }} {{ currentUser.last_name }}
                        </span>
                        <span>
                        <img :alt="currentUser.first_name" :src="currentUser.img"/>
                    </span>
                    </template>
                    <b-dropdown-text>
                        <p><i class="mdi mdi-email text-primary"></i> {{ currentUser.email }}</p>
                        <p><i class="mdi mdi-account text-primary"></i> {{ currentUser.role_name }}</p>
                    </b-dropdown-text>
                    <b-dropdown-divider></b-dropdown-divider>
                    <b-dropdown-item @click="goToProfile('profile')">
                        Profile
                    </b-dropdown-item>
                    <b-dropdown-item @click="goToProfile('theme-settings')">
                        Theme Settings
                    </b-dropdown-item>
                    <b-dropdown-divider/>
                    <b-dropdown-item @click="signOut">Sign out</b-dropdown-item>
                </b-dropdown>
            </div>
        </div>
    </nav>
</template>

<script>
import NotificationDropDown from "./NotificationDropDown";
import Switches from "vue-switches";

import {
    mapGetters,
    mapMutations,
    mapActions
} from "vuex";
import {
    MenuIcon,
    MobileMenuIcon
} from "../components/Svg";
import {
    searchPath,
    menuHiddenBreakpoint,
    localeOptions,
    defaultColor, currentUser
} from "@/constants/config";
import {
    getDirection,
    setDirection
} from "../utils";

export default {
    components: {
        "menu-icon": MenuIcon,
        "mobile-menu-icon": MobileMenuIcon,
        NotificationDropDown,
        switches: Switches
    },
    data() {
        return {
            selectedParentMenu: "",
            searchKeyword: "",
            isMobileSearch: false,
            isSearchOver: false,
            fullScreen: false,
            menuHiddenBreakpoint,
            searchPath,
            localeOptions,
            isDarkActive: false
        };
    },
    methods: {
        ...mapMutations('menu', ["changeSideMenuStatus", "changeSideMenuForMobile"]),
        ...mapActions('generic', ["setLang"]),
        ...mapActions('auth', ["signOut"]),
        goToProfile(path) {
            if (this.$route.path !== '/profile-settings/' + path) {
                this.$router.push({name: path, params: {}})
            }
        },
        search() {
            this.$router.push(`${this.searchPath}?search=${this.searchKeyword}`);
            this.searchKeyword = "";
        },
        searchClick() {
            if (window.innerWidth < this.menuHiddenBreakpoint) {
                if (!this.isMobileSearch) {
                    this.isMobileSearch = true;
                } else {
                    this.search();
                    this.isMobileSearch = false;
                }
            } else {
                this.search();
            }
        },
        handleDocumentforMobileSearch() {
            if (!this.isSearchOver) {
                this.isMobileSearch = false;
                this.searchKeyword = "";
            }
        },

        changeLocale(locale, direction) {
            const currentDirection = getDirection().direction;
            if (direction !== currentDirection) {
                setDirection(direction);
            }

            this.setLang(locale);
        },

        toggleFullScreen() {
            const isInFullScreen = this.isInFullScreen();

            var docElm = document.documentElement;
            if (!isInFullScreen) {
                if (docElm.requestFullscreen) {
                    docElm.requestFullscreen();
                } else if (docElm.mozRequestFullScreen) {
                    docElm.mozRequestFullScreen();
                } else if (docElm.webkitRequestFullScreen) {
                    docElm.webkitRequestFullScreen();
                } else if (docElm.msRequestFullscreen) {
                    docElm.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
            this.fullScreen = !isInFullScreen;
        },
        getThemeColor() {
            return localStorage.getItem("themeColor") ?
                localStorage.getItem("themeColor") :
                defaultColor;
        },
        isInFullScreen() {
            return (
                (document.fullscreenElement && document.fullscreenElement !== null) ||
                (document.webkitFullscreenElement &&
                    document.webkitFullscreenElement !== null) ||
                (document.mozFullScreenElement &&
                    document.mozFullScreenElement !== null) ||
                (document.msFullscreenElement && document.msFullscreenElement !== null)
            );
        }
    },
    computed: {
        ...mapGetters('auth', ["currentUser"]),
        ...mapGetters('menu', {
            menuType: "getMenuType",
            menuClickCount: "getMenuClickCount",
            selectedMenuHasSubItems: "getSelectedMenuHasSubItems"
        }),
    },
    beforeDestroy() {
        document.removeEventListener("click", this.handleDocumentforMobileSearch);
    },
    created() {
        const color = this.getThemeColor();
        this.isDarkActive = color.indexOf("dark") > -1;
    },
    watch: {
        "$i18n.locale"(to, from) {
            if (from !== to) {
                this.$router.go(this.$route.path);
            }
        },
        isDarkActive(val) {
            let color = this.getThemeColor();
            let isChange = false;
            if (val && color.indexOf("light") > -1) {
                isChange = true;
                color = color.replace("light", "dark");
            } else if (!val && color.indexOf("dark") > -1) {
                isChange = true;
                color = color.replace("dark", "light");
            }
            if (isChange) {
                localStorage.setItem("themeColor", color);
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        },
        isMobileSearch(val) {
            if (val) {
                document.addEventListener("click", this.handleDocumentforMobileSearch);
            } else {
                document.removeEventListener(
                    "click",
                    this.handleDocumentforMobileSearch
                );
            }
        }
    }
};
</script>

<style scoped>
</style>

<style lang="scss">
.user .dropdown-menu {
    min-width: 20rem;
}
</style>
