<template>
    <div id="app">
        <BlockUI message="" :html="html" v-if="loading" />
        <router-view @showLoader="showLoader" @hideLoader="hideLoader" />
    </div>
</template>

<style src="@/vendor/styles/bootstrap.scss" lang="scss"></style>
<style src="@/vendor/styles/appwork.scss" lang="scss"></style>
<style src="@/vendor/styles/theme-corporate.scss" lang="scss"></style>
<style src="@/vendor/styles/colors.scss" lang="scss"></style>
<style src="@/vendor/styles/uikit.scss" lang="scss"></style>
<style src="@/vendor/libs/spinkit/spinkit.scss" lang="scss"></style>
<style
    src="@/vendor/libs/vue-notification/vue-notification.scss"
    lang="scss"
></style>
<style src="./style.scss" lang="scss"></style>
<style src="./demo.css" lang="css"></style>

<script>
export default {
    name: "app",
    metaInfo: {
        title: "Index",
        titleTemplate: "AIM Lead Engine - %s"
    },
    data: () => ({
        loading: false,
        html: `
                <div class="sk-folding-cube sk-primary">
                    <div class="sk-cube1 sk-cube"></div>
                    <div class="sk-cube2 sk-cube"></div>
                    <div class="sk-cube4 sk-cube"></div>
                    <div class="sk-cube3 sk-cube"></div>
                 </div>
                <h5 class="text-center mb-0">LOADING...</h5>
            `,
        reminders: [],
        options: []
    }),

    methods: {
        showLoader() {
            this.loading = true;
        },
        hideLoader() {
            this.loading = false;
        }
    },
    created() {
        if (process.env.NODE_ENV == "production") {
            //Do something here if production environment is detected
        }

        window.axios.interceptors.response.use(
            response => {
                // intercept the global error
                return response;
            },
            function(error) {
                if (error.response.status === 401) {
                    window.ability.update([]);
                    localStorage.clear();
                    window.location.href = "/login";
                }
                // Do something with response error
                return Promise.reject(error);
            }
        );
    }
};
</script>
