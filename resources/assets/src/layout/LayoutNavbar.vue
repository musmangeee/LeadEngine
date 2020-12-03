<template>
    <b-navbar
        toggleable="lg"
        :variant="getLayoutNavbarBg()"
        class="layout-navbar align-items-lg-center container-p-x"
    >
        <!-- Brand -->
        <b-navbar-brand to="/"><img :src="logo" height="40"/></b-navbar-brand>

        <!-- Sidenav toggle -->
        <b-navbar-nav
            class="align-items-lg-center mr-auto mr-lg-4"
            v-if="sidenavToggle"
        >
            <a
                class="nav-item nav-link px-0 ml-2 ml-lg-0"
                href="javascript:void(0)"
                @click="toggleSidenav"
            >
                <i class="ion ion-md-menu text-large align-middle" />
            </a>
        </b-navbar-nav>

        <!-- Navbar toggle -->
        <b-navbar-toggle target="app-layout-navbar"></b-navbar-toggle>

        <b-collapse is-nav id="app-layout-navbar">
            <!-- Divider -->
            <hr class="d-lg-none w-100 my-2" />

            <b-navbar-nav class="align-items-lg-center ml-auto">
                <b-nav-item-dropdown
                    no-caret
                    :right="!isRTL"
                    class="demo-navbar-notifications mr-lg-3"
                >
                    <template slot="button-content" class="item">
                        <i
                            class="ion ion-md-notifications-outline navbar-icon align-middle"
                        ></i>
                        <!-- <span class="badge badge-primary badge-dot indicator" v-if="unreadNotification > 0"></span> -->
                        <span
                            class="notify-badge"
                            v-if="unreadNotification > 0"
                            >{{ unreadNotification }}</span
                        >
                        <span class="d-lg-none align-middle"
                            >&nbsp; Notifications</span
                        >
                    </template>

                    <div
                        class="bg-primary text-center text-white font-weight-bold p-3"
                    >
                        {{ unreadNotification }} New Notifications
                    </div>
                    <b-list-group flush>
                        <b-list-group-item
                            href="javascript:void(0)"
                            class="media d-flex align-items-center bg-"
                            :class="{
                                'bg-lighter': notification.read_at == null
                            }"
                            v-for="notification in notifications"
                            :key="notification.id"
                        >
                            <div
                                class="ui-icon ui-icon-sm ion ion ion-md-megaphone bg-success border-0 text-white"
                            ></div>
                            <div
                                class="media-body line-height-condenced ml-3"
                                @click="showMessage(notification)"
                            >
                                <div class="text-dark font-weight-bold">
                                    {{ notification.data.title }}
                                </div>
                                <div class="text-light small">
                                    {{
                                        notification.created_at
                                            | moment("from", "now")
                                    }}
                                </div>
                            </div>
                        </b-list-group-item>
                    </b-list-group>

                    <a
                        href="javascript:void(0)"
                        class="d-block text-center text-light small p-2 my-1"
                        @click.prevent="markAllRead()"
                        >Show all notifications</a
                    >
                </b-nav-item-dropdown>

                <!-- Divider -->
                <div
                    class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1"
                >
                    |
                </div>

                <b-nav-item-dropdown :right="!isRTL" class="demo-navbar-user">
                    <template slot="button-content">
                        <span
                            class="d-inline-flex flex-lg-row-reverse align-items-center align-middle"
                        >
                            <span v-if="user.photo_path == null">
                                <i class="fa fa-user-circle fa-2x"></i>
                            </span>
                            <img
                                v-else
                                :src="user.photo_path"
                                alt
                                class="d-block ui-w-30 rounded-circle"
                            />
                            <span class="px-1 mr-lg-2 ml-2 ml-lg-0">{{
                                `${user.first_name} ${user.last_name}`
                            }}</span>
                        </span>
                    </template>

                    <b-dd-item>
                        <router-link to="/profile"
                            ><i class="ion ion-ios-person text-lightest"></i>
                            &nbsp;<span class="text-primary">My Profile</span>
                        </router-link>
                    </b-dd-item>
                    <!--<b-dd-item><i class="ion ion-ios-mail text-lightest"></i> &nbsp; Messages</b-dd-item>-->
                    <!--<b-dd-item><i class="ion ion-md-settings text-lightest"></i> &nbsp; Account settings</b-dd-item>-->
                    <b-dd-divider />
                    <b-dd-item @click.prevent="logout"
                        ><i class="ion ion-ios-log-out text-danger"></i> &nbsp;
                        Log Out
                    </b-dd-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </b-collapse>

        <!-- Notification Pop Up -->
        <b-modal
            centered
            id="notification-modal"
            ref="notification-modal"
            header-text-variant="dark"
            body-text-variant="dark"
            :title="notif.title"
            hide-footer
        >
            <div v-html="notif.description" class="text-dark"></div>
        </b-modal>
    </b-navbar>
</template>

<script>
import moment from "moment";

export default {
    name: "app-layout-navbar",

    props: {
        sidenavToggle: {
            type: Boolean,
            default: true
        }
    },

    data: function() {
        return {
            first_name: localStorage.getItem("first_name"),
            last_name: localStorage.getItem("last_name"),
            notifications: [],
            notif: {
                id: null,
                title: null,
                description: null,
                createdAt: null
            },
            unreadNotification: 0
        };
    },

    created() {
        this.listenForChanges();
        this.getLatestNotification();
    },

    computed: {
        userTimezone: function() {
            return localStorage.getItem("timezone");
        },
        logo: function() {
            return "/images/logo.png";
        },
        user: function(){
            return this.$store.getters.user
        },
        photo_path: function() {
            let photoPath = localStorage.getItem("photo_path");
            if (photoPath == "null") {
                return null;
            } else {
                return "/media/" + photoPath;
            }
        }
    },

    watch: {
        notifications: function(val) {
            this.unreadNotification = 0;
            this.notifications.forEach(notification => {
                if (notification.read_at === null) {
                    this.unreadNotification += 1;
                }
            });
        }
    },

    filters: {
        moment: function(mydate) {
            var date = new Date(mydate + " UTC");
            return moment(date.toString()).fromNow();
        }
    },
    methods: {
        secondsToHms(d) {
            d = Number(d);
            var h = Math.floor(d / 3600);
            var m = Math.floor((d % 3600) / 60);
            var s = Math.floor((d % 3600) % 60);

            var hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
            var mDisplay =
                m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
            var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
            return hDisplay + mDisplay + sDisplay;
        },
        moment: function(mydate) {
            return moment(mydate);
        },

        listenForChanges() {
            let self = this;

            /*Echo.private('App.User.' + localStorage.getItem('website_uuid') + '.' + localStorage.getItem('user_id'))
                .notification((notification) => {
                    self.getLatestNotification()
                })*/
        },

        toggleSidenav() {
            this.layoutHelpers.toggleCollapsed();
        },

        getLayoutNavbarBg() {
            return this.layoutNavbarBg;
        },

        logout() {
            this.$store.dispatch("logout")
        },

        showMessage(n) {
            let self = this;

            this.notif.id = n.id;
            this.notif.title = n.data.title;
            this.notif.description = n.data.description;
            this.notif.createdAt = n.created_at;
            this.$refs["notification-modal"].show();

            axios
                .get("/api/notifications/" + this.notif.id)
                .then(response => {
                    self.unreadNotification -= 1;

                    let i = self.notifications
                        .map(notification => notification.id)
                        .indexOf(n.id);

                    this.$delete(self.notifications, i);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        getLatestNotification() {
            let self = this;
            axios
                .get("/api/notifications/take/5")
                .then(response => {
                    self.notifications = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        markAllRead() {
            this.unreadNotification = 0;
            this.notifications = [];
            this.$router.push({ name: "notifications-list" });
        }
    }
};
</script>

<style>
.item {
    position: relative;
    text-align: center;
    vertical-align: middle;
}
.notify-badge {
    position: absolute;
    right: -20%;
    top: -1%;
    background: red;
    text-align: center;
    border-radius: 50%;
    color: white;
    padding: 0px 6px;
    border: 1px solid;
    font-size: 12px;
}
</style>
