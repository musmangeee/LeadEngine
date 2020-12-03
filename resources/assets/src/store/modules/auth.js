import axios from "axios";

const state = {
    roles: localStorage.getItem("roles") || null,
    permissions: localStorage.getItem("permission") || null,
    options: localStorage.getItem("options") || null,
    user: localStorage.getItem("user") || null,
    loggedIn: false,
    errors: null
};

const getters = {
    options: state => state.options,
    permissions: state => state.permissions,
    roles: state => state.roles,
    loggedIn: state => !!state.token,
    user: state => state.user,
    errors: state => state.errors
};

const actions = {
    async profileInfo({ commit }) {
        return axios
            .get("/api/users/profile")
            .then(result => {
                commit("authSuccess", {
                    token: result.data.token,
                    roles: result.data.roles,
                    permissions: result.data.permissions,
                    options: result.data.options,
                    user: result.data.user
                });

                localStorage.setItem(
                    "roles",
                    JSON.stringify(result.data.roles)
                );
                localStorage.setItem(
                    "permissions",
                    JSON.stringify(result.data.permissions)
                );
                window.ability.update(
                    JSON.parse(localStorage.getItem("permissions"))
                );

                localStorage.setItem(
                    "options",
                    JSON.stringify(result.data.options)
                );
            })
            .catch(error => {
                console.log(error);
            });
    },
    async login({ commit }, credentials) {
        return axios
            .post("/api/login", {
                email: credentials.email,
                password: credentials.password
            })
            .then(result => {
                commit("authSuccess", {
                    token: result.data.token,
                    roles: result.data.roles,
                    permissions: result.data.permissions,
                    options: result.data.options
                });
                localStorage.setItem("accessToken", result.data.token);

                window.axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${result.data.token}`;
            })
            .catch(err => {
                commit("authFailed", {
                    message: err.response.data.message
                });
            });
    },
    logout({ commit }) {
        return axios
            .post("/logout")
            .then(() => {
                commit("authLogout")
                window.ability.update([])
                localStorage.clear()
                window.location.href = "/login";
            })
            .catch(err => {
                console.log(err)
            });
    }
};

const mutations = {
    authSuccess(state, data) {
        state.user = data.user;
        state.roles = data.roles;
        state.permissions = data.permissions;
        state.options = data.options;
        state.loggedIn = true;
        state.errors = null;
    },
    authLogout(state) {
        state.loggedIn = false;
        state.errors = null;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
