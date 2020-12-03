<template>
  <sidenav :orientation="orientation" :class="curClasses">
    <!-- Inner -->
    <div class="sidenav-inner" :class="{ 'py-1': orientation !== 'horizontal' }">
      <sidenav-router-link icon="fas fa-chart-pie" to="/" :exact="true">Dashboard</sidenav-router-link>
      <sidenav-router-link
        icon="fas fa-satellite-dish"
        to="/leads/live-stream"
        :exact="true"
      >Live Lead Stream</sidenav-router-link>
      <sidenav-router-link icon="fas fa-hand-holding-usd" to="/leads" :exact="true">Lead Management</sidenav-router-link>
      <sidenav-router-link icon="fas fa-exclamation-triangle" to="/leads/failed-list" :exact="true">Error Lead Intake</sidenav-router-link>
      <sidenav-menu  icon="fas fa-chart-line"> 
          <template slot="link-text">Reports Management</template>
      
         <sidenav-router-link  to="/reports/daily-lead-report" :exact="true">Daily Lead Report</sidenav-router-link>
         <sidenav-router-link  to="/reports/daily-count-by-provider" :exact="true">Daily Count by Provider</sidenav-router-link>
         <sidenav-router-link  to="/reports/daily-count-by-portfolio" :exact="true">Daily Count by Portfolio</sidenav-router-link>
         <sidenav-router-link  to="/reports/count-by-buyer" :exact="true">Count by Buyer</sidenav-router-link>
      
      </sidenav-menu>
     

      <sidenav-divider class="mb-1" />
      <div
        v-if="
                    roles.includes('Admin') ||
                        ability.can('read', 'Setting-company') ||
                        ability.can('update', 'Setting-company') ||
                        ability.can('read', 'User') ||
                        ability.can('update', 'User') ||
                        ability.can('create', 'User') ||
                        ability.can('delete', 'User')
                "
      >
        <sidenav-header class="small font-weight-semibold">Administration</sidenav-header>

        <sidenav-menu
          v-if="
              ability.can('read', 'Buyer') || ability.can('read', 'Buyer-panda-channel') || ability.can('read', 'Buyer-plat-channel') || ability.can('read', 'Buyer-vertical-channel')
          "
          icon="fas fa-hands-helping"
          :active="isMenuActive('/buyer') || isMenuOpen('/buyer') || isMenuActive('/buyer/create') || isMenuOpen('/buyer/create')
          || isMenuActive('/buyer-panda-channel') || isMenuOpen('/buyer-panda-channels') || isMenuActive('/buyer-panda-channel/create') || isMenuOpen('/buyer-panda-channel/create')
          || isMenuActive('/buyer-plat-channel') || isMenuOpen('/buyer-plat-channel') || isMenuActive('/buyer-plat-channel/create') || isMenuOpen('/buyer-plat-channel/create')
          || isMenuActive('/buyer-vertical-channel') || isMenuOpen('/buyer-vertical-channel') || isMenuActive('/buyer-vertical-channel/create') || isMenuOpen('/buyer-vertical-channel/create')
          "
          :open="
            isMenuActive('/buyer') || isMenuOpen('/buyer') || isMenuActive('/buyer/create') || isMenuOpen('/buyer/create')
          || isMenuActive('/buyer-panda-channel') || isMenuOpen('/buyer-panda-channels') || isMenuActive('/buyer-panda-channel/create') || isMenuOpen('/buyer-panda-channel/create')
          || isMenuActive('/buyer-plat-channel') || isMenuOpen('/buyer-plat-channel') || isMenuActive('/buyer-plat-channel/create') || isMenuOpen('/buyer-plat-channel/create')
          || isMenuActive('/buyer-vertical-channel') || isMenuOpen('/buyer-vertical-channel') || isMenuActive('/buyer-vertical-channel/create') || isMenuOpen('/buyer-vertical-channel/create')
          "
        >
          <template slot="link-text">Buyer Management</template>
          <sidenav-router-link v-if="ability.can('read','Buyer')" to="/buyers" :exact="true">View Buyers</sidenav-router-link>
          <sidenav-router-link v-if="ability.can('create','Buyer')" to="/buyers/create" :exact="true">Add Buyer</sidenav-router-link>

<!--
          <sidenav-router-link v-if="ability.can('read','Buyer-vertical-channel')" to="/buyer-vertical-channels" :exact="true">View Vertical Channels</sidenav-router-link>
          <sidenav-router-link
            v-if="ability.can('create','Buyer-vertical-channel')"
            to="/buyer-vertical-channels/create"
            :exact="true"
          >Add Vertical Channel</sidenav-router-link>

          <sidenav-router-link v-if="ability.can('read','Buyer-panda-channel')" to="/buyer-panda-channels" :exact="true">View Panda Channels</sidenav-router-link>
          <sidenav-router-link v-if="ability.can('create','Buyer-panda-channel')" to="/buyer-panda-channels/create" :exact="true">Add Panda Channel</sidenav-router-link>

          <sidenav-router-link v-if="ability.can('read','Buyer-plat-channel')" to="/buyer-plat-channels" :exact="true">View Plat Channels</sidenav-router-link>
          <sidenav-router-link v-if="ability.can('create','Buyer-plat-channel')" to="/buyer-plat-channels/create" :exact="true">Add Plat Channel</sidenav-router-link>
-->
        </sidenav-menu>

            <sidenav-menu
          v-if="ability.can('read','Portfolio') || ability.can('create','Portfolio')"
          icon="fas fa-chart-bar"
          :active="isMenuActive('/portfolios')"
          :open="isMenuOpen('/portfolios')"
        >
          <template slot="link-text">Portfolio Management</template>
          <sidenav-router-link v-if="ability.can('read','Portfolio')" to="/portfolios" :exact="true">View Portfolios</sidenav-router-link>
          <sidenav-router-link v-if="ability.can('create','Portfolio')" to="/portfolios/create" :exact="true">Add Portfolio</sidenav-router-link>
        </sidenav-menu>


        <sidenav-menu
           v-if="ability.can('read','Provider') || ability.can('create','Provider')"
          icon="far fa-address-card"
          :active="isMenuActive('/provider') || isMenuActive('/provider/create') || isMenuActive('/vertical-channels') || isMenuActive('/vertical-channels/create') || isMenuActive('/panda-channels') || isMenuActive('/panda-channels/create')"
          :open="isMenuOpen('/provider') || isMenuOpen('/provider/create') || isMenuOpen('/vertical-channels') || isMenuOpen('/vertical-channels/create') || isMenuOpen('/panda-channels') || isMenuOpen('/panda-channels/create')"
        >
          <template slot="link-text">Provider Management</template>

          <sidenav-router-link v-if="ability.can('read','Provider')"  to="/providers" :exact="true">View Providers</sidenav-router-link>
          <sidenav-router-link v-if="ability.can('create','Provider')"  to="/providers/create" :exact="true">Add Provider</sidenav-router-link>
        </sidenav-menu>
        <!--
        <sidenav-menu
           v-if="ability.can('read','Integration') || ability.can('create','Integration')"
          icon="fas fa-tools"
          :active="isMenuActive('/integration') || isMenuActive('/integration/create')"
          :open="isMenuOpen('/integration') || isMenuOpen('/integration/create')"
        >
          <template slot="link-text">Integration Management</template>

          <sidenav-router-link v-if="ability.can('read','Integration')"  to="/integrations" :exact="true">View Integrations</sidenav-router-link>
          <sidenav-router-link v-if="ability.can('create','Integration')"  to="/integrations/create" :exact="true">Add Integration</sidenav-router-link>
        </sidenav-menu>
        <sidenav-router-link icon="fas fa-route" to="/" :exact="true">Routing Management</sidenav-router-link>
        -->
        <sidenav-router-link v-if="ability.can('read','Redirect')" icon="fas fa-directions" to="/redirects/" :exact="true">Redirect Management</sidenav-router-link>
        <sidenav-menu
          icon="ion ion-ios-people"
          :active="isMenuActive('/user')"
          :open="isMenuOpen('/user')"
          v-if="
                        roles.includes('Admin') ||
                            ability.can('read', 'User') ||
                            ability.can('update', 'User')
                    "
        >
          <template slot="link-text">Users</template>
          <sidenav-router-link
            to="/user/list"
            :exact="true"
            v-if="ability.can('read', 'User')"
          >View Users</sidenav-router-link>
          <sidenav-router-link
            to="/user/create"
            :exact="true"
            v-if="ability.can('create', 'User')"
          >Add User</sidenav-router-link>
        </sidenav-menu>

        <sidenav-menu
         v-if="ability.can('read','Fake-lead') || ability.can('read','Ip-ban') || ability.can('read','Redirect-setting')"
         icon="fas fa-cog" :open="false">
          <template slot="link-text">Settings</template>
            <sidenav-router-link v-if="ability.can('read','Fake-lead')" to="/settings/fake-lead" :exact="true">Fake/Test Lead</sidenav-router-link>
            <sidenav-router-link v-if="ability.can('read','Ip-ban')" to="/settings/ip-bans" :exact="true">IP Bans</sidenav-router-link>
            <sidenav-router-link to="/settings/intake-management" :exact="true">Intake Management</sidenav-router-link>
            <!--<sidenav-router-link to="/settings/role-management" :exact="true">Role Management</sidenav-router-link>-->
            <sidenav-router-link v-if="ability.can('read','Redirect-setting')" to="/redirect-settings" :exact="true">Redirect Settings</sidenav-router-link>
        </sidenav-menu>
      </div>
    </div>
  </sidenav>
</template>

<script>
import {
  Sidenav,
  SidenavRouterLink,
  SidenavMenu,
  SidenavHeader,
  SidenavDivider
} from "@/vendor/libs/sidenav";

export default {
  name: "app-layout-sidenav",
  components: {
    Sidenav,
    SidenavRouterLink,
    SidenavMenu,
    SidenavHeader,
    SidenavDivider
  },

  props: {
    orientation: {
      type: String,
      default: "vertical"
    }
  },

  created() {},

  computed: {
    p() {
      return localStorage.getItem("website_p");
    },
    roles() {
      return this.$store.getters.roles;
    },
    ability() {
      return window.ability;
    },
    curClasses() {
      let bg = this.layoutSidenavBg;

      if (
        this.orientation === "horizontal" &&
        (bg.indexOf(" sidenav-dark") !== -1 ||
          bg.indexOf(" sidenav-light") !== -1)
      ) {
        bg = bg
          .replace(" sidenav-dark", "")
          .replace(" sidenav-light", "")
          .replace("-darker", "")
          .replace("-dark", "");
      }

      return (
        `bg-${bg} ` +
        (this.orientation !== "horizontal"
          ? "layout-sidenav"
          : "layout-sidenav-horizontal container-p-x flex-grow-0")
      );
    }
  },

  methods: {
    isMenuActive(url) {
      return this.$route.path.indexOf(url) === 0;
    },

    isMenuOpen(url) {
      return (
        this.$route.path.indexOf(url) === 0 && this.orientation !== "horizontal"
      );
    },

    toggleSidenav() {
      this.layoutHelpers.toggleCollapsed();
    }
  }
};
</script>
