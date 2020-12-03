<template>
    <div class="layout-wrapper layout-1">
        <notifications
            group="notifications-default"
            :duration="8000"
            :speed="1000"
            position="top right"
        />
        <vue-snotify />

        <div class="layout-inner" v-if="user">
            <app-layout-navbar />

            <div class="layout-container">
                <app-layout-sidenav />

                <div class="layout-content">
                    <div
                        class="router-transitions container-fluid flex-grow-1 container-p-y"
                    >
                        <router-view />
                    </div>

                    <app-layout-footer />
                </div>
            </div>
        </div>
        <div class="layout-overlay" @click="closeSidenav"></div>
    </div>
</template>

<script>
import LayoutNavbar from "./LayoutNavbar";
import LayoutSidenav from "./LayoutSidenav";
import LayoutFooter from "./LayoutFooter";

export default {
    name: "app-layout-1",
    components: {
        "app-layout-navbar": LayoutNavbar,
        "app-layout-sidenav": LayoutSidenav,
        "app-layout-footer": LayoutFooter
    },

    data: () => ({
        selectedTaskId: null,
        loading: false,
        reminders: [],
        options: []
    }),

    created() {        
        window.axios
            .get(
                `/api/users/profile`
            )
            .then(response => {
                this.$store.dispatch('profileInfo')
            })
            .catch(error => {
                console.log(error);
            });
    },

    mounted() {
        this.layoutHelpers.init();
        this.layoutHelpers.update();
        this.layoutHelpers.setAutoUpdate(true);
    },

    beforeDestroy() {
        this.layoutHelpers.destroy();
    },

    methods: {
        closeSidenav() {
            this.layoutHelpers.setCollapsed(true);
        },
        handleOpen(selectedTaskId) {
            this.selectedTaskId = selectedTaskId;
            this.$refs.taskEditModal.show();
        }
    },

     computed: {
    
        user: function(){
            return this.$store.getters.user
        }
        
    },
};
</script>
