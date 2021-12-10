import {mapActions} from 'vuex';
export const hideSideMenu = {
    methods: {
        ...mapActions('menu', ['hideSideMenu']),
    },
    mounted() {
        this.hideSideMenu();
    }
};
